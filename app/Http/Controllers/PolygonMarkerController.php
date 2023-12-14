<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PolygonMarkerController extends Controller
{
    public function index()
    {
        return view('polygon_marker');
    }
}
