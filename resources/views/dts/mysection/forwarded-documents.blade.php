@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">My Section Forwarded Documents</h6>
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
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="card-title mb-0">{{ $tableTitle }}</h5>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('dts.my-station') }}" class="btn btn-primary float-end">Back to My Section Page</a>
                </div>
              </div>
    </div>
    <div class="card-body">
        <table id="receivedDocsTable" class="table table-striped">
            <thead>
                <tr>
                  
                    <th style="text-align: left;">Tracking</th>
                    <th>Particulars</th>
                    <th>For</th>                  
                    <th>Date Forwarded</th>                   
                </tr>
            </thead>
            <tbody>
                @foreach($forwardedDocs as $documentRoute)
                    <tr>
                       
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
                      
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="my-3">
        <div class="d-flex justify-content-center">
            {{ $forwardedDocs->links() }}
        </div>
    </div>
</div>



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
                { width: '13ch', targets: 0 },
                { width: '38%', targets: 1 }
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
                            userDropdown.append('<option value="' + user.id + '">' + user.name + '</option>');
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
