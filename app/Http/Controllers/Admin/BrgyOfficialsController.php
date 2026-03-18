<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayOfficials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrgyOfficialsController extends Controller
{
    public function index()
    {
        $officials = BarangayOfficials::orderBy('last_name')->paginate(10);

        return view('admin.brgyOfficials.index', compact('officials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('officials', 'public');
        }
        
        $data['created_by'] = auth()->id();

        $official = BarangayOfficials::create($data);
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Add Barangay Official',
            'description' => 'Added a new barangay official',
        ]);
        return back()->with('success', 'Official added successfully.');
    }

    public function update(Request $request, BarangayOfficials $brgyOfficial)
    {
        $data = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($brgyOfficial->photo_path) {
                Storage::disk('public')->delete($brgyOfficial->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('officials', 'public');
        }

        $brgyOfficial->update($data);
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Edit Barangay Official',
            'description' => 'Updated a barangay official’s details',
        ]);
        return back()->with('success', 'Official updated successfully.');
    }

    public function destroy(BarangayOfficials $brgyOfficial)
    {
        if ($brgyOfficial->photo_path) {
            Storage::disk('public')->delete($brgyOfficial->photo_path);
        }

        $brgyOfficial->delete();
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete Barangay Official',
            'description' => 'Deleted a barangay official',
        ]);
        return back()->with('success', 'Official deleted successfully.');
    }
}
