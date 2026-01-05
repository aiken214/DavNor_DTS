@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Edit User</h6>
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
        <h5 class="card-title mb-0">Edit User</h5>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PATCH')

            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="section_id" class="form-label">Section</label>
                        <select class="form-control" id="section_id" name="section_id">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ $user->section_id == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Leave blank to keep the current password.</small>
                    </div>
        
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header"> <label for="sections" class="form-label">Assign Sections</label></div>
                        <div class="card-body">
                            <div class="m-4">
                                
                                <select class="form-control select2" multiple="multiple" style="width: 100%">
                                    @foreach($sections as $section)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                                
                               
                                
                            </div>
                            <div class="mb-3">
                               
                                @foreach($sections as $section)
                                        <div class="form-check mb-2 d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" name="sections[]" value="{{ $section->id }}" id="section_{{ $section->id }}" {{ in_array($section->id, $userSections) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="section_{{ $section->id }}">
                                                {{ $section->name }}
                                            </label>
                                        </div>
                                
                                @endforeach
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>

       <div class="row">
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="btn btn-primary" type="button">Update User</button>           
          </div>    
    </div>    
          
           
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection