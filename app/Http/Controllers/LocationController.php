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

        $location = Location::create([
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $location
        ]);
    }


    public function show($id)
    {
        $location = Location::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $location
        ]);
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'latitude_edit' => 'required|numeric',
            'longitude_edit' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $latitude = $request->input('latitude_edit');
        $longitude = $request->input('longitude_edit');

        $location = Location::findOrFail($id);

        $location->update([
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Ubah!',
            'data'    => $location
        ]);
    }
}
