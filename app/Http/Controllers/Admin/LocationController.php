<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('admin.locations.index', [
            'locations' => Location::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.locations.form', ['location' => new Location(['is_active' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        Location::create($this->validated($request));

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi ditambahkan.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.form', ['location' => $location]);
    }

    public function update(Request $request, Location $location)
    {
        $location->update($this->validated($request));

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi diperbarui.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:40'],
            'opening_hours' => ['nullable', 'string', 'max:1000'],
            'map_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
