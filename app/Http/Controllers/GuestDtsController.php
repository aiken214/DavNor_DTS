<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DtsSection;
use App\Models\DtsDocType;
use App\Models\DtsGuestdocument;
use App\Models\User;
use App\Models\DtsSystemSetting;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class GuestDtsController extends Controller
{
    
    public function createGuestDocument()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
       
        $sections = DtsSection::where('is_public_dropdown', 1)->orderBy('name', 'asc')->get();
       
        $docTypes = DtsDocType::where('for_guest', 1)->orderBy('menu_sequence', 'asc')->orderBy('description', 'asc')->get();
        return view('guest-dts-form', compact('sections', 'docTypes'));
    }

    public function storeGuestDocument(Request $request)
    {
        $systemSetting = DtsSystemSetting::first();
      
        $request->validate([
            'guestname' => 'required',
            'doctype_id' => 'required|exists:dts_doc_types,id',
            'doc_description' => 'required',
            'organization' =>'nullable',
            'actions_needed' => 'required',
            'to_section_id' => 'required|exists:dts_sections,id',
            'to_user_id' => 'required|exists:users,id',

        ]);

        $guestDocument = new DtsGuestdocument();
        $guestDocument->doctype_id = $request->doctype_id;
        $guestDocument->doc_description = $request->doc_description;
        $guestDocument->organization = $request->organization;
        $guestDocument->from_section_id = NULL;
        $guestDocument->submittedby = $request->guestname;
        $guestDocument->receiver_section_id = $request->to_section_id;
        $guestDocument->intended_receiver_id = $request->to_user_id;
        $guestDocument->actions_needed = $request->actions_needed;       
        $guestDocument->created_at= now();
        $guestDocument->save();

        return redirect()->route('guest-dts-confirmation', $guestDocument->id);
    }

    public function confirmation($id)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $guestDocument = DtsGuestdocument::with(['docType', 'receiverSection', 'intendedReceiver'])->findOrFail($id);

        $refNo = 'GD-' . str_pad($guestDocument->id, 6, '0', STR_PAD_LEFT);
        $qrCode = QrCode::size(150)->style('round')->generate($refNo);

        return view('guest-dts-confirmation', compact('guestDocument', 'qrCode', 'refNo'));
    }

    public function getUserBySecId($sectionId)
    {
        $users = User::where('section_id', $sectionId)->select('id', 'name')->orderBy('name', 'asc')->get();
        return response()->json($users);
    }
}
