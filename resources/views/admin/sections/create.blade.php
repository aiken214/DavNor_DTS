@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Section Management</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
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
        <div class="row align-items-center">
            <div class="col-sm-6">
            <h5 class="card-title mb-0">Section Create</h5>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('admin.sections.index') }}" class="btn btn-warning float-end">Back to Section List</a>
            </div>
        </div>
       
    </div>
    <div class="card-body">

        
        <form action="{{ route('admin.sections.store') }}" method="POST" >
            @csrf
            <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Section Name</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" id="name" name="name" required>
            </div>
            </div>
            <div class="row mb-3">
                <label for="is_record_management" class="col-sm-3 col-form-label">Is Record Management</label>
                <div class="col-sm-9">
                    <div class="form-check form-check-inline">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input" type="radio" name="is_record_management" id="is_record_management_yes" value="1" checked>
                            <label class="form-check-label ms-2" for="is_record_management_yes">Yes</label>
                        </div>
                    </div>
                <div class="form-check form-check-inline">
                    <div class="d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="is_record_management" id="is_record_management_no" value="0">
                    <label class="form-check-label ms-2" for="is_record_management_no">No</label>
                    </div>
                </div>
                </div>
                </div>
        
                   
                <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-3 pt-0">Route Dropdown</legend>
                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input" type="radio" name="is_public_dropdown" id="is_public_dropdown_yes" value="1" checked>
                                <label class="form-check-label ms-2" for="is_public_dropdown_yes">Included to Guest DTS Route dropdown</label>
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input" type="radio" name="is_public_dropdown" id="is_public_dropdown_no" value="0">
                                <label class="form-check-label ms-2" for="is_public_dropdown_no">Excluded to Guest DTS Route dropdown</label>
                            </div>
                        </div>
                    </div>
                </fieldset>
          
          
          <hr class="mb-20">
            <div class="row mb-3">
                <div class="col-sm-3"></div>
                <div class="col-sm-8 text-end">
                    <button type="submit" class="btn btn-primary">Add New Section</button>
                </div>
            </div>
        </form> 

    </div>
</div>




@endsection
