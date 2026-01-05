<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DtsSystemSetting;
use App\Models\DtsDocument;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\HtmlString;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use PDF;

class BarcodePrintController extends Controller
{
   public function printSlip($docId){
    abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    $systemSetting =DtsSystemSetting::first();
         $document = DtsDocument::findOrFail($docId);
              $data = [
            'document' => $document,
            'systemSetting' => $systemSetting,
          ];
          //Set a Custom Paper Size to 74mm x 105mm (or equivalent in inches: 2.91in x 4.13in) 1/4 of a short bond || paper->setPaper([0, 0, 4.5 * 72, 6 * 72], 'portrait');
        $pdf = PDF::loadView('dts.qr-slip', $data);
        return $pdf->stream('dts-qrslip.pdf');
    }
    

    public function printTopRight($docId){
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $document = DtsDocument::findOrFail($docId);
        $data = [
            'document' => $document,
        ];
        $pdf = PDF::loadView('dts.qr-top-right', $data);
        return $pdf->stream('dts-qr-topright.pdf');
    }

    public function printBottomRight($docId){
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $document = DtsDocument::findOrFail($docId);
        $data = [
            'document' => $document,
        ];
        $pdf = PDF::loadView('dts.qr-bottom-right', $data);
        return $pdf->stream('dts-qr-bottomright.pdf');
    }

    public function printBottomLeft($docId){
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $document = DtsDocument::findOrFail($docId);
        $data = [
            'document' => $document,
        ];
        $pdf = PDF::loadView('dts.qr-bottom-left', $data);
        return $pdf->stream('dts-qr-bottomleft.pdf');
    }


    
}
