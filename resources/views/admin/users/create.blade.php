@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Add New User</h6>
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
                <h5 class="card-title mb-0">Create User</h5>
            </div>
            <div class="col-sm-6">
                <div class="btn-toolbar justify-content-end">
                    
                    <a href="{{ route('admin.users.index') }}" class="btn btn-warning btn-sm">All Users</a>
                   
                </div>
            </div>  
        </div>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
           

            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"  required>
                    </div>
        
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"  required>
                    </div>
        
                    <div class="mb-3">
                        <label for="section_id" class="form-label">Section</label>
                        <select class="form-control" id="section_id" name="section_id" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="roles" class="form-label">Roles</label>
                        <select class="form-control select2" id="roles" name="roles[]" multiple required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" >{{ $role->title }}</option>
                            @endforeach
                        </select>
                    </div>
                   
        
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" name="password" value="pass6789" required>
                       
                    </div>
        
                  
              
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header"> <label for="assignSections" class="form-label">Assigned Sections (User may transfer from one of the assigned sections to another)</label></div>
                        <div class="card-body">
                            <div class="m-4">
                                
                                <div class="border border-primary p-1" style="border-color: rgb(16, 129, 92); border-width: 2px; border-style: solid;">
                                 <select class="select2 select2-purple" data-placeholder="Select Section " name="sections[]" id="assignSection" multiple="multiple" data-dropdown-css-class="select2-purple" style="width: 100% !important;">
                                    @foreach($sections as $section)
                                    <option value="{{ $section->id }}" > 
                                        {{ $section->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                                
                            </div>
                          
                        </div>
                    </div>
                   
                </div>
            </div>

       <div class="row">
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="btn btn-primary" type="button">Add User</button>           
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
<script>
    document.getElementById('section_id').addEventListener('change', function() {
        var selectedSection = this.value;
        var assignSection = document.getElementById('assignSection');
        for (var i = 0; i < assignSection.options.length; i++) {
            if (assignSection.options[i].value == selectedSection) {
                assignSection.options[i].selected = true;
                break;
            }
        }
        $('#assignSection').trigger('change'); // Update the select2 UI
    });
</script>

@endsection