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
        $officials = BarangayOfficials::orderBy('last_name')->get();

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

        BarangayOfficials::create($data);

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

        return back()->with('success', 'Official updated successfully.');
    }

    public function destroy(BarangayOfficials $brgyOfficial)
    {
        if ($brgyOfficial->photo_path) {
            Storage::disk('public')->delete($brgyOfficial->photo_path);
        }

        $brgyOfficial->delete();

        return back()->with('success', 'Official deleted successfully.');
    }
}
