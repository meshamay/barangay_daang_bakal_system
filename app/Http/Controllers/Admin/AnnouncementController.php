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
     * Archive the specified announcement.
     */
    public function archive(Request $request, Announcement $announcement)
    {
        $announcement->status = 'archived';
        $announcement->save();

        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Archive Announcement',
            'description' => 'Archived announcement: ' . $announcement->title,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement archived successfully.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $dateFilter = $request->query('date');
        $search = trim((string) $request->query('search', ''));

        $query = Announcement::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");

                try {
                    $searchDate = Carbon::parse($search)->toDateString();
                    $q->orWhereDate('start_date', $searchDate)
                      ->orWhereDate('end_date', $searchDate)
                      ->orWhereDate('created_at', $searchDate);
                } catch (\Throwable $exception) {
                }
            });
        }

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
                case 'archived':
                    $query->where('status', 'archived');
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
            'dateFilter',
            'search'
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
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Add Announcement',
            'description' => 'Added a new announcement',
        ]);
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
        
        return redirect()->route('admin.announcements.index')->with('edit_id', $announcement->getKey());
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
            $imagePath = $announcement->getAttribute('image_path');
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $validated['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $announcement->update($validated);
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Edit Announcement',
            'description' => 'Updated an announcement’s details',
        ]);
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $imagePath = $announcement->getAttribute('image_path');
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}