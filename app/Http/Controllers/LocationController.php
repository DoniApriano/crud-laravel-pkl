<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function index()
    {
        $locations = Location::get();
        return view('location', compact(['locations']));
    }

    public function allLocation()
    {
        $locations = Location::get();
        return view('all_location', compact(['locations']));
    }

    public function store(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $lokasi = Location::create([
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        if($lokasi){
            return redirect()->back()->with('success', 'Koordinat berhasil disimpan!');
        } else {
            return redirect()->back()->with('error', 'Koordinat gagal disimpan!');
        }

    }
}
