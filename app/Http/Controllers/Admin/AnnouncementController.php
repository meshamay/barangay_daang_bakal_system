<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $dateFilter = $request->query('date');

        $query = Announcement::query();

        if ($dateFilter) {
            $date = Carbon::parse($dateFilter)->startOfDay();
            $query->whereDate('start_date', '<=', $date)
                  ->where(function ($q) use ($date) {
                      $q->whereDate('end_date', '>=', $date)
                        ->orWhereNull('end_date');
                  });
        }

        if ($statusFilter) {
            $today = Carbon::today();
            switch (strtolower($statusFilter)) {
                case 'ongoing':
                    $query->ongoing();
                    break;
                case 'ended':
                    $query->ended();
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', $today)->where('status', '!=', 'inactive');
                    break;
                case 'inactive':
                    $query->where('status', 'inactive');
                    break;
            }
        }

        $announcements = $query->latest()->paginate(10)->appends($request->query());
        $totalAnnouncements = Announcement::count();
        $ongoingCount = Announcement::ongoing()->count();
        $endedCount = Announcement::ended()->count();

        return view('admin.announcements.index', compact(
            'announcements',
            'totalAnnouncements',
            'ongoingCount',
            'endedCount',
            'statusFilter',
            'dateFilter'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'in:active,inactive',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:2048',
            'priority' => 'nullable|integer|min:0',
        ]);

        
        $validated['status'] = $validated['status'] ?? 'active';

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $validated['created_by'] = auth()->id(); 

        $announcement = Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    
    public function show(Announcement $announcement)
    {
        // Redirect to index since we are using a modal for viewing details
        return redirect()->route('admin.announcements.index');
    }

    
    public function edit(Announcement $announcement)
    {
        
        return redirect()->route('admin.announcements.index')->with('edit_id', $announcement->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'in:active,inactive',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:2048',
            'priority' => 'nullable|integer|min:0',
        ]);

        $validated['status'] = $validated['status'] ?? $announcement->status ?? 'active';

        if ($request->hasFile('image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $validated['image'] = $request->file('image')->store('announcements', 'public');
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}