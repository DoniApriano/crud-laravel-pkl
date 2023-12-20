<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            $locations = Location::latest()->get();
            return DataTables::of($locations)->make(true);
        }
        return view('location');
    }

    public function allLocation()
    {
        $locations = Location::get();
        return view('all_location', compact(['locations']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $lokasi = Location::create([
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        if ($lokasi) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'data'    => $lokasi
            ]);
        } else {
            return redirect()->back()->with('error', 'Koordinat gagal disimpan!');
        }
    }
}
