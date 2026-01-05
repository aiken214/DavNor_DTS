<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class MyProfilePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function updateStation(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:sections,id', // Ensure the station_id is valid
        ]);

            // Get the authenticated user
            $user = Auth::user();

            // Update the user's section_id with the new station_id
            $user->section_id = $request->station_id;
            $user->save();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'My Station is updated successfully.');
    }

    
}
