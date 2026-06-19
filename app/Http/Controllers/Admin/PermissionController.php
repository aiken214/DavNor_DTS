<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\AuthGates;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Redirect;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;




class PermissionController extends Controller

{ 

    public function index(){
      abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
      // Retrieve the aggregated count data for the user's section
      $docCount = DB::table('section_document_counts')
      ->where('section_id', Auth::user()->section_id)
      ->first(); 
      $guestdocCount = $docCount ? $docCount->guestdoc_count : 0;
      $incomingCount = $docCount ? $docCount->count_incomming : 0;
      $receivedCount = $docCount ? $docCount->count_received : 0;
      $forwardedCount = $docCount ? $docCount->count_forwarded : 0;           
      $deferredCount = $docCount ? $docCount->count_deferred : 0;
        $result= DB::table('permissions')->get();

        if (view()->exists('admin.permissions.index')) {
          return view('admin.permissions.index', compact('result', 'docCount', 'guestdocCount', 'incomingCount', 'receivedCount', 'forwardedCount', 'deferredCount'));
        }

        return response()->json($result);
    
    }

    public function edit(){
        echo "edit";
    }
}
