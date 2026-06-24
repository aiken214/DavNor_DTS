@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">DTS-Guest Document</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                My DTS Section
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

  
@if ($errors->any())
<div class="m-3 alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
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

<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0"> Guest Documents for receipt</h5>
    </div>
    <div class="card-body">
        <table id="receivedDocsTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>Code</th>
                    <th class="description-column">Description</th>
                    <th>From</th>
                    <th>Created /Route Purpose</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr>
                    <td class="align-middle" style="min-width: 6rem;"> <a href="{{ route('dts.documents.show', $document->id) }}">GD-{{ str_pad($document->id, 5, '0', STR_PAD_LEFT) }}</a></td>
                    <td class="align-middle">{{ $document->docType->description }} - {{ $document->doc_description }}</td>
                    <td class="align-middle"> {{ $document->fromSection->name ?? 'N/A' }} <br>
                     <small> {{ $document->submittedby ?? 'N/A' }}  </small>
                    </td>
                    <td class="align-middle">@dateDateTime($document->created_at) <br>
                       <small> {{ $document->actions_needed ?? 'N/A' }} </small>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-info btn-sm"
                        data-bs-toggle="modal" data-bs-target="#viewGuestDocModal"
                        data-guestdoc_id="{{ $document->id }}"
                        data-submittedby="{{ $document->submittedby }}"
                        data-organization="{{ $document->organization }}"
                        data-doctype_desc="{{ $document->docType->description }}"
                        data-description="{{ $document->doc_description }}"
                        data-actions_needed="{{ $document->actions_needed }}"
                        data-receiver_section="{{ $document->receiverSection->name ?? 'N/A' }}"
                        data-intended_receiver="{{ $document->intendedReceiver->name ?? 'N/A' }}"
                        data-created_at="{{ $document->created_at ? $document->created_at->format('M d, Y h:i A') : 'N/A' }}"
                        >View</button>
                        <button class="btn btn-success btn-sm"
                        data-bs-toggle="modal" data-bs-target="#acceptModal"
                        data-guestdoc_id="{{ $document->id }}"
                        data-from_section_name="{{ $document->fromSection->name ?? 'N/A' }}"
                        data-from_section_id="{{ $document->from_section_id }}"
                        data-organization ="{{ $document->organization }} "
                        data-submittedby="{{ $document->submittedby }}"
                        data-submitter_id ="{{ $document->submitter_id }}"
                        data-actions_needed="{{ $document->actions_needed }}"
                        data-doctype_id="{{ $document->docType->id }}"
                        data-description="{{ $document->doc_description }}"
                        >Accept</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGuestDocModal"
                        data-guestdoc_description="{{ $document->docType->description }} - {{ $document->doc_description }}"
                        data-doc_id="{{ $document->id }}">
                        Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- View Guest Document Modal -->
<div class="modal fade" id="viewGuestDocModal" tabindex="-1" aria-labelledby="viewGuestDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewGuestDocModalLabel">Guest Document Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width:35%">Reference No.</th>
                        <td><strong class="text-danger-600" id="viewRefNo"></strong></td>
                    </tr>
                    <tr>
                        <th>Submitted By</th>
                        <td id="viewSubmittedBy"></td>
                    </tr>
                    <tr>
                        <th>Organization</th>
                        <td id="viewOrganization"></td>
                    </tr>
                    <tr>
                        <th>Document Type</th>
                        <td id="viewDocType"></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td id="viewDescription"></td>
                    </tr>
                    <tr>
                        <th>Actions Needed</th>
                        <td id="viewActionsNeeded"></td>
                    </tr>
                    <tr>
                        <th>Routed To Section</th>
                        <td id="viewReceiverSection"></td>
                    </tr>
                    <tr>
                        <th>Intended Receiver</th>
                        <td id="viewIntendedReceiver"></td>
                    </tr>
                    <tr>
                        <th>Date Submitted</th>
                        <td id="viewCreatedAt"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('dts.guest-doc.accept') }}" method="post">
                @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="acceptModalLabel">Accept Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="from" class="form-label mb-0 col-sm-3">From</label>
                    <div class="col-sm-9">
                        <input type="text" id="from" class="form-control form-control-sm"  readonly>
                        <input type="hidden" id="fromUserId" name="fromuser_id" class="form-control">
                        <input type="hidden" id="fromSectionId" name="fromsection_id" class="form-control">
                        <input type="hidden" id="submittedBy" name="guest_origin_name" class="form-control">
                        <input type="hidden" id="guestOrganization" name="guest_origin_organization">
                        <input type="hidden" id="guestDocId" name="guest_doc_id" class="form-control">
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
                    </div>
                </div>              
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="description" class="form-label mb-0 col-sm-3">Description</label>
                    <div class="col-sm-9">
                          <textarea id="description" class="form-control text-box-small" name="description" rows="3" required></textarea>
                          <input type="hidden" name="tracking_issuedby_id" value="{{ Auth::user()->id }}">
                    </div>
                </div>
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="actions_needed" class="form-label mb-0 col-sm-3">Route Purpose </label>
                    <div class="col-sm-9">
                        <input type="text" id="actions_needed" class="form-control form-control-sm" name="actions_needed">                       
                    </div>
                </div>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Accept</button>
            </div>
        </form>
        </div>
    </div>
</div>
 <!-- Delete Modal -->
 <div class="modal fade" id="deleteGuestDocModal" tabindex="-1" aria-labelledby="deleteGuestDocModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dts.guest-doc-destroy') }}" method="post">
                @csrf
             <div class="modal-header">
                <h5 class="modal-title" id="deleteGuestDocModalLabel">Delete Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this document?</p>
                <p id="forDelDescription" class="alert alert-info"></p>
                <input type="hidden" id="delDocId" name="guest_document_id" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .description-column {
        width: 40%;
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
        $('#receivedDocsTable').DataTable({
            responsive: true,
            autoWidth: false, // Prevent auto-calculation of width by DataTables
            columnDefs: [
                { width: '35%', targets: 1 } // Set the width of the description column
            ],
            order: [[0, 'desc']] 
        });
    });
</script>
<script>
    $('#viewGuestDocModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('guestdoc_id');
        var refNo = 'GD-' + String(id).padStart(6, '0');
        var modal = $(this);
        modal.find('#viewRefNo').text(refNo);
        modal.find('#viewSubmittedBy').text(button.data('submittedby'));
        modal.find('#viewOrganization').text(button.data('organization') || 'N/A');
        modal.find('#viewDocType').text(button.data('doctype_desc'));
        modal.find('#viewDescription').text(button.data('description'));
        modal.find('#viewActionsNeeded').text(button.data('actions_needed') || 'N/A');
        modal.find('#viewReceiverSection').text(button.data('receiver_section'));
        modal.find('#viewIntendedReceiver').text(button.data('intended_receiver'));
        modal.find('#viewCreatedAt').text(button.data('created_at'));
    });
</script>
<script>
    $('#acceptModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var fromSection = button.data('from_section_name') 
  var organization = button.data('organization')
  var doctypeId = button.data('doctype_id')
  var description = button.data('description')
  var actionsNeeded = button.data('actions_needed') 
  var modal = $(this)
    modal.find('#from').val(organization+' - '+fromSection)
    modal.find('#description').val(description)
    modal.find('#dts_doc_type_id').val(doctypeId)
    modal.find('#actions_needed').val(actionsNeeded)
    modal.find('#fromUserId').val(button.data('submitter_id'))
    modal.find('#fromSectionId').val(button.data('from_section_id'))
    modal.find('#submittedBy').val(button.data('submittedby'))
    modal.find('#guestOrganization').val(button.data('organization'))
    modal.find('#guestDocId').val(button.data('guestdoc_id'))
})
</script>

<script>
    $('#deleteGuestDocModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var description = button.data('guestdoc_description') 
  var docId= button.data('doc_id')
  var modal = $(this)
    modal.find('#forDelDescription').text(description)
    modal.find('#delDocId').val(docId)
})
</script>
@endsection
