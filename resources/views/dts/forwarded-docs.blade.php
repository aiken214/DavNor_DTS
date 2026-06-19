@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">{{ $tableTitle }}</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">
            <div class="btn-group dropstart">
                <button class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                     @if(isset($mySection) && $mySection != NULL)
                    {{ $mySection }}
                    @elseif(isset($myAllSections) && count($myAllSections) > 0)                        
                        Select Section
                    @endif
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

<div class="card basic-data-table">
        <div class="card-header">
        <h5 class="card-title mb-0">Forwarded Documents</h5>
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
    </div>
    <div class="card-body">
        <table id="receivedDocsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="text-align: left;">Tracking</th>
                    <th>Particulars</th>
                    <th>For</th>                  
                    <th>Date Forwarded</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $documentRoute)
                    <tr>
                        <td><a href="{{ route('dts.documents.show', $documentRoute->document->id) }}">
                            {{ $documentRoute->id  }}
                        </a>
                        </td>
                        <td class="align-middle" style="text-align: left !important; min-width: 6rem;">
                            <a href="{{ route('dts.documents.show', $documentRoute->document->id) }}">
                                {{ $documentRoute->document->tracking_code }}
                            </a>
                        </td>
                        <td class="align-middle">{{ $documentRoute->docType->description }} - {{ $documentRoute->document->description }}</td>
                        <td>{{ $documentRoute->forSection->name ?? 'N/A' }} <br>
                            <small>{{ $documentRoute->forUser->name ?? 'N/A' }} </small>
                        </td>                        
                        <td>@dateDateTime($documentRoute->date_forwarded)<br>
                            <small>{{ $documentRoute->route_purpose }} </small>
                        </td>                        
                        <td class="align-middle">

                       @if($documentRoute->status_id==1)
                            <button class="btn btn-info btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editRouteModal"
                                    data-route-id="{{ $documentRoute->id }}"
                                    data-document-id="{{ $documentRoute->document->id }}"
                                    data-for-section-id="{{ $documentRoute->for_section_id }}"
                                    data-for-user-id="{{ $documentRoute->for_user_id }}"
                                    data-route_purpose="{{ $documentRoute->route_purpose }}"
                                    data-doc-description="{{ $documentRoute->document->description }}"
                                    onclick="editRoute(this)">
                                <iconify-icon data-icon="bi:pencil-square" class="icon"></iconify-icon>
                                Edit Route
                            </button>
                           @endif

                            @if($documentRoute->previous_route_id != NULL)
                            <!-- Add button for cancel -->
                            <button class="btn btn-danger-400 btn-sm"
                            data-bs-toggle="modal"
                                    data-bs-target="#cancelRouteModal"
                                     data-route-id="{{ $documentRoute->id }}"
                                     data-prev_route_id="{{ $documentRoute->previous_route_id }}"
                                     data-doc-description="{{ $documentRoute->document->description }}"
                                        onclick="cancelRoute(this)">
                                     Cancel</button>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="my-3">
        <div class="d-flex justify-content-center">
            {{ $documents->links() }}
        </div>
    </div>
</div>

<!-- Bootstrap modal for Edit Route -->
<div class="modal fade" id="editRouteModal" tabindex="-1" aria-labelledby="editRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRouteModalLabel">Edit Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to edit the route -->
                <form id="editRouteForm" method="POST" action="{{ route('dts.forwarded-docs.update-forwarded-doc') }}">
                    @csrf
                    <textarea class="form-control" id="editDocDescription" rows="2" cols="80" readonly></textarea>
                    <input type="hidden" name="document_id" id="document-id">
                    <div class="mb-3">
                        <label for="for_section_id" class="form-label">For Section</label>   
                        <select class="form-select" name="for_section_id" id="for_section_id">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        
                        <input type="hidden" class="form-control" name="route_id" id="editDocRouteId">
                    </div>
                    <div class="mb-3">
                        <label for="for_user_id" class="form-label">For User</label>    
                        <select class="form-select" name="for_user_id" id="for_user_id">
                            <option value="">Select User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edroutePurpose" class="form-label">Route Purpose</label>
                        <textarea class="form-control" name="route_purpose" id="edroutePurpose" rows="2"></textarea> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End of Bootstrap modal for Edit Route -->
<!-- Modal for CancelRouteModal -->
<div class="modal fade" id="cancelRouteModal" tabindex="-1" aria-labelledby="cancelRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelRouteModalLabel">Cancel / Delete Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to edit the route -->
                <form id="cancelRouteForm" method="POST" action="{{ route('dts.forwarded-docs.cancel-forwarded-doc') }}">
                    @csrf
                    <textarea class="form-control" id="cancelDocDescription" rows="2" cols="80" readonly></textarea>
                    <input type="hidden" name="route_id" id="cancelDocRouteId" class="form-control">
                    <input type="hidden" name="prev_route_id" id="prevRouteId" class="form-control">
                    <div class="mb-3">
                        <label for="cancelRoutePurpose" class="form-label">Cancel/Delete Reason</label>
                        <textarea class="form-control" name="del_reason" id="cancelReason" rows="2" required></textarea> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Cancel Route</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal for CancelRouteModal -->



@endsection

@section('styles')
<style>
    .description-column {
        width: 20%;
    }

    /* Ensure the table fits 100% width of its container */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // DataTable initialization
        $('#receivedDocsTable').DataTable({
            responsive: true,
            autoWidth: false,
            columnDefs: [
                { width: '13ch', targets: 1 },
                { width: '35%', targets: 2 }
            ],
            order: [[0, 'desc']]
        });

        // Handle modal show event
        $('#editRouteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            var documentId = button.data('document-id');
            var forSectionId = button.data('for-section-id');
            var forUserId = button.data('for-user-id');
            var routePurpose = button.data('route_purpose');
            var docDescription = button.data('doc-description');
            var routeId = button.data('route-id');

            // Populate the form fields
            modal.find('#document-id').val(documentId);
            modal.find('#for_section_id').val(forSectionId);
            modal.find('#edroutePurpose').val(routePurpose);
            modal.find('#editDocDescription').val(docDescription);
            modal.find('#editDocRouteId').val(routeId);

            // Fetch and populate users for the selected section
            fetchUsersForSection(modal, forSectionId, forUserId);
        });

        // Handle section change event
        $('#for_section_id').change(function() {
            var sectionId = $(this).val();
            fetchUsersForSection($('#editRouteModal'), sectionId);
        });

        // Function to fetch and populate users
        function fetchUsersForSection(modal, sectionId, selectedUserId = null) {
            var userDropdown = modal.find('#for_user_id');
            userDropdown.empty().append('<option value="">Select User</option>').prop('disabled', true);

            if (sectionId) {
                $.ajax({
                    url: "{{ url('/dts/get-users-by-section') }}/" + sectionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(users) {
                        $.each(users, function(index, user) {
                            userDropdown.append($('<option>').val(user.id).text(user.name));
                        });
                        if (selectedUserId) {
                            userDropdown.val(selectedUserId);
                        }
                        userDropdown.prop('disabled', false);
                    },
                    error: function() {
                        userDropdown.prop('disabled', false);
                    }
                });
            }
        }
    });
</script>
<!-- Script for Cancel Route form -->
<script>
    $(document).ready(function() {
        // Handle modal show event
        $('#cancelRouteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            var routeId = button.data('route-id');
            var docDescription = button.data('doc-description');
            var prevRouteId = button.data('prev_route_id');

            // Populate the form fields
            modal.find('#cancelDocRouteId').val(routeId);
            modal.find('#cancelDocDescription').val(docDescription);
            modal.find('#prevRouteId').val(prevRouteId);
        });
    });
</script>
@endsection
