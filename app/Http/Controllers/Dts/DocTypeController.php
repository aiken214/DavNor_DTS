<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsDocType;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class DocTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('dts_doctype_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $systemSetting =DtsSystemSetting::first();
        
          
        $docTypes = DtsDocType::orderBy('description', 'asc')->get();
        return view('dts.dts-doctypes', compact('docTypes', 'systemSetting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {       
        abort_if(Gate::denies('dts_doctype_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $systemSetting =DtsSystemSetting::first();
        return view('dts.create-doctype', compact('systemSetting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('dts_doctype_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'description' => 'required|unique:dts_doc_types,description',
            'for_guest' => 'required',
        ]);

        $docType = new DtsDocType();
        $docType->description = $validated['description'];
        $docType->for_guest = $validated['for_guest'];
        $docType->save();
        return redirect()->route('dts.doc-types.index')->with('success', 'Document Type created successfully');
        
        
    }

    /**
     * Display the specified resource.
     */
    public function show(DtsDocType $dtsDocType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(Gate::denies('dts_doctype_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       $doctype= DtsDocType::where('id', $id)->first();      
        return view('dts.edit-doctype', compact('doctype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        abort_if(Gate::denies('dts_doctype_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'id' => 'required|integer',
            'description' => 'required',
            'for_guest' => 'required',
        ]);

        $docType = DtsDocType::find($validated['id']);
        $docType->description = $validated['description'];
        $docType->for_guest = $validated['for_guest'];
        $docType->save();
        return redirect()->route('dts.doc-types.index')->with('success', 'Document Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DtsDocType $dtsDocTypes)
    {
        abort_if(Gate::denies('dts_doctype_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($dtsDocTypes->documents()->count() > 0) {
            return redirect()->route('dts.doc-types.index')->with('error', 'Cannot delete this document type because it has associated documents.');
        }

        $dtsDocTypes->delete();
        return redirect()->route('dts.doc-types.index')->with('success', 'Document Type deleted successfully');
    }
}
