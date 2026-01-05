<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DtsSystemSetting;
use App\Models\DtsOrganization;
use App\Models\DtsDocRoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\DtsSection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class SystemSettingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dts_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $mySection = NULL;
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
 
         $systemName= DtsSystemSetting::select('custom_system_name')->where('id',1)->first()->custom_system_name;
        $setting = DtsSystemSetting::with('organization')->find(1);    
        $organizations = DtsOrganization::all();   
        return view("admin.system-setting", compact('setting', 'systemName', 'mySection', 'myAllSections', 'organizations' ));
    }

 

    public function update(Request $request, DtsSystemSetting $dtsSystemSetting)
    {
        abort_if(Gate::denies('dts_settings_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validatedData = $request->validate([
            'org_dts_code' => 'nullable|string|max:255',
            'custom_system_name' => 'nullable|string|max:255',
            'number_of_padding' => 'nullable|integer',
            'allow_auto_park' => 'nullable|boolean',
            'auto_parkdays' => 'nullable|integer',
            'logo_at' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_light_at' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'login_image_at' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'allow_fileupload' => 'nullable|boolean',
            'allow_guest_docform' => 'nullable|boolean',
        ]);

        // Update the settings with validated data
        $dtsSystemSetting->update($validatedData);

        // Handle file uploads for logo and login image
        if ($request->hasFile('logo_at')) {
            $logoPath = 'storage/'.$request->file('logo_at')->store('logos', 'public');
            $dtsSystemSetting->update(['logo_at' => $logoPath]);
        }
        if ($request->hasFile('logo_light_at')) {
            $logoLightPath = 'storage/' . $request->file('logo_light_at')->store('logo_light', 'public');
            $dtsSystemSetting->update(['logo_light_at' => $logoLightPath]);
        }

        if ($request->hasFile('login_image_at')) {
            $loginImagePath = 'storage/'.$request->file('login_image_at')->store('login_images', 'public');
            $dtsSystemSetting->update(['login_image_at' => $loginImagePath]);
        }

       
        

        // Redirect back with a success message
        return redirect()->route('admin.system-settings.index')->with('success', 'Settings updated successfully.');
    }



    
    public function parkRoutes(Request $request)
    {
        abort_if(Gate::denies('dts_settings_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $systemSetting =DtsSystemSetting::first();    
        $message = NULL;               
          $deferredParked=  DB::table('dts_doc_routes')
                     ->where('status_id',5)
                     ->where('deferred_date','<',Carbon::now()->subDays(  $systemSetting->auto_parkdays)->startOfDay())
                     ->update(['status_id' => 9, 'date_parked' => Carbon::now(), 'end_remarks' => 'Parked due to long delay to take action']);
        if($deferredParked){
            $message = "Deferred documents parked. ";
        }
         
        $incomingParked= DB::table('dts_doc_routes')
                     ->where('status_id',1)
                     ->where('date_forwarded','<',Carbon::now()->subDays(  $systemSetting->auto_parkdays)->startOfDay())
                     ->update(['status_id' => 7, 'date_parked' => Carbon::now(), 'end_remarks' => 'Parked due to long delay to take action']);
        if($incomingParked){
            $message .= " Incoming documents parked. ";
        }
        $pendingParked = DtsDocRoute::where('status_id', 2)
                 ->where('date_accepted', '<', Carbon::now()->subDays($systemSetting->auto_parkdays)->startOfDay())
                 ->update([
                 'status_id' => 8,
                 'date_parked' => Carbon::now(),
                 'end_remarks' => 'Parked due to long delay to take action'
                 ]);
        if($pendingParked){
            $message .= " Pending documents parked. ";
        }

   if($deferredParked || $incomingParked || $pendingParked){
      return redirect()->route('admin.system-settings.index')->with('success', $message);
    }else{
      return redirect()->route('admin.system-settings.index')->with('error', 'No document parked.');
    }
    
    
    }
}
