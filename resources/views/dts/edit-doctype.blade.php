@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Edit DTS Document Types</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">
            <a href="{{ route('admin.users.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                Users
            </a>
        </li>
        <li class="fw-medium">
            @if(isset($mySection) && $mySection != NULL)
            <div class="btn-group dropstart">
                <button class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $mySection }}
                </button>
                <ul class="dropdown-menu">
                    @if(isset($myAllSections))
                    @foreach($myAllSections as $section)
                        <li>
                            <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900"
                               href="javascript:void(0)"
                               onclick="submitSectionForm('{{ $section->id }}')">
                               {{ $section->name }}
                            </a>
                        </li>
                    @endforeach
                    @endif
                </ul>
            </div>
            
            <!-- Form to submit the selected section_id -->
            <form id="section-form" method="POST" action="{{ route('user.updateStation') }}" style="display: none;">
                @csrf
                <input type="hidden" name="station_id" id="station-id">
            </form>
            @endif
        </li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-6">
                <h5 class="card-title mb-0">Edit Doc Type</h5>
            </div>
            <div class="col-sm-6">
                <div class="btn-toolbar justify-content-end">
                    
                    <a href="{{ route('dts.doc-types.index') }}" class="btn btn-warning btn-sm">Document Types</a>
                   
                </div>
            </div>  
        </div>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('dts.doc-types.update') }}">
            @csrf
           

            <div class="row">
                <div class="col-sm-8">
                    <form>
                        <div class="row mb-3">
                          <label for="typename" class="col-sm-3 col-form-label">Description</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" id="typename" name="description" value="{{ $doctype->description }}"  required>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="selectforGuest" class="col-sm-3 col-form-label">Form DropDown for Guest</label>
                          <div class="col-sm-9">
                            <select name="for_guest" id="selectforGuest" class="form-control">
                                <option value="1" {{ $doctype->for_guest == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $doctype->for_guest == 0 ? 'selected' : '' }}>No</option>

                            </select>

                            <input type="hidden" class="form-control" name="id" value={{ $doctype->id }}  required>
                            
                          </div>
                        </div>
                       
                        
                        <div class="row mb-3">
                          <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary float-end">Save Changes</button>
                          </div>
                        </div>
                      </form>


                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header"> 
                        </div>
                        <div class="card-body">
                            
                          
                        </div>
                    </div>
                   
                </div>
            </div>

     
           
        </form>
    </div>
</div>

@endsection
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%', // Ensures full width
            placeholder: "Select Section" // Placeholder text
        });
       
    });
   
</script>

@endsection