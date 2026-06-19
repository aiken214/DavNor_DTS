@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Document Tracking System</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                DTS-Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">
            @if(isset($myAllSections) && count($myAllSections) > 0)
            <div class="btn-group dropstart">
                <button class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                     @if(isset($mySection) && $mySection != NULL)
                    {{ $mySection }}
                    @elseif(isset($myAllSections) && count($myAllSections) > 0)                        
                        Select Section
                    @endif
                </button>
              
                <ul class="dropdown-menu">                   
                    @foreach($myAllSections as $section)
                        <li>
                            <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900"
                               href="javascript:void(0)"
                               onclick="submitSectionForm('{{ $section->id }}')">
                               {{ $section->name }}
                            </a>
                        </li>
                    @endforeach                  
                    
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
 <!-- Validation display -->
 @if ($errors->any())
 <div class="mt-3 alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
 <div class="d-flex align-items-center gap-2">
 <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
 <ul>
     @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
     @endforeach
 </ul>
 </div>
 <button class="remove-button text-danger-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
 </div>
@endif
<!--ednValidation display -->

<div class="row">
    <div class="col-sm-7">
        <div class="card basic-data-table">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    @if(isset($tableTitle))
                        {{ $tableTitle }}
                    @else
                        Submit New Document
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($mySection != NULL)
                <form action="{{ route('dts.documents.store') }}" method="POST">
                    @csrf
                    <div class="row mb-24 gy-3 align-items-center">
                        <label for="from" class="form-label mb-0 col-sm-3">From</label>
                        <div class="col-sm-9">
                            <input type="text" id="from" class="form-control form-control-sm" value="{{ Auth::user()->name }} | {{ $mySection }}" readonly>
                            <input type="hidden" name="fromuser_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="from_section_id" value="{{ Auth::user()->section_id }}">
                        </div>
                    </div>
                    <div class="row mb-24 gy-3 align-items-center">
                        <label for="dts_doc_type_id" class="form-label mb-0 col-sm-3">Document Type</label>
                        <div class="col-sm-9">
                            <select name="dts_doc_type_id" id="dts_doc_type_id" class="form-control form-control-sm" required>
                                <option value="">Select Document Type</option>
                                @foreach ($docTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->description }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="tracking_issuedby_id" value="{{ Auth::user()->id }}">
                        </div>
                    </div>
                  
                    <div class="row mb-24 gy-3 align-items-center">
                        <label for="description" class="form-label mb-0 col-sm-3">Description</label>
                        <div class="col-sm-9">
                              <textarea id="description" class="form-control text-box-small" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="row mb-24 gy-3 align-items-center">
                        <label for="actions_needed" class="form-label mb-0 col-sm-3">Actions Needed (Route Purpose)</label>
                        <div class="col-sm-9">
                            <input type="text" id="actions_needed" class="form-control form-control-sm" name="actions_needed">
                        </div>
                    </div>
                    <div class="row mb-24 gy-3 align-items-center">
                        <label for="to_section_id" class="form-label mb-0 col-sm-3">Route to Section</label>
                        <div class="col-sm-9">
                            <select name="to_section_id" id="to_section_id" class="form-control form-control-sm" required>
                                <option value="" style="visibility: hidden; display:none;">Select Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-24 gy-3 align-items-center">
                        <label for="to_user_id" class="form-label mb-0 col-sm-3">Employee</label>
                        <div class="col-sm-9">
                            <select name="to_user_id" id="to_user_id" class="form-control form-control-sm" required>
                                <option value="" style="visibility: hidden; display:none;">Select Staff</option>
                                <!-- Options will be dynamically populated here -->
                            </select>
                             <div class="alert alert-danger mt-2 d-none" id="user-fetch-error">
                                An error occurred while fetching users. Please try again.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary-600">Submit</button>
                        </div>
                    </div>
                </form>
                @else
                <div> Please Ask the system admin to assign you to a DTS Section or Station</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="card">
            <div class="card-header">
               <h6>Form Instructions</h6> 
            </div>
            <div class="card-body">
                <p style="font-size:1.1rem; font-weight: bold;">Description Field</p>
                <p>
                    Please provide a detailed description of the document content. For example, if the document type is Authority to Travel, include the following in the description: <span style="font-weight: bold;">your destination, the purpose of travel, and the travel dates. </span>
                </p>  <p>
                    If it is a communication letter, please give a short description of its purpose and to where it is addressed.
                </p>
                <p>
                    If it is for procurement, liquidation, or reimbursement, please include the amount, attachments, and the document dates.
                </p>
                <p style="font-size:1.1rem; font-weight: bold;">Actions Needed Field</p>
                <p>
                    Please specify the purpose of routing this document. For example, indicate if the document is being sent for approval, review, signature, or for information. </p>
                </p>

                

            </div>
        </div>
       
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var baseUrl = "{{ url('/') }}"; // Get the base URL

        $('#to_section_id').change(function() {
            var sectionId = $(this).val();
            var userDropdown = $('#to_user_id');
            var errorAlert = $('#user-fetch-error');
            
            userDropdown.empty().append('<option value="">Select Staff</option>'); // Clear current options

            if (sectionId) {
                $.ajax({
                    url: baseUrl + '/dts/get-users-by-section/' + sectionId,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        userDropdown.prop('disabled', true);
                        errorAlert.addClass('d-none');
                    },
                    success: function(data) {
                        $.each(data, function(index, user) {
                            userDropdown.append($('<option>').val(user.id).text(user.name));
                        });
                        userDropdown.prop('disabled', false);
                    },
                    error: function(error) {
                        console.error('Error fetching users:', error);
                        errorAlert.removeClass('d-none');
                        userDropdown.prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
@endsection
