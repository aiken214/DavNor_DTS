@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">DTS-Received Document</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                My DTS Section
            </a>
        </li>
        <li>-</li>
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

<div class="card basic-data-table">
    
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


    <div class="card-header">
        <h5 class="card-title mb-0">Deferred Documents</h5>
    </div>
    <div class="card-body">
        <table id="receivedDocsTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="description-column">Description</th>
                    <th>From</th>
                    <th>Accepted /Route Purpose</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($docRoutes as $docRoute)
                <tr>
                    <td class="align-middle" style="min-width: 6rem;"> <a href="{{ route('dts.document-view', $docRoute->document->id) }}">{{ $docRoute->document->tracking_code }}</a></td>
                    <td class="align-middle">{{ $docRoute->docType->description }} - {{ $docRoute->document->description }}</td>
                    <td class="align-middle"> {{ $docRoute->fromSection->name ?? 'N/A' }} <br>
                     <small> {{ $docRoute->fromUser->name ?? 'N/A' }}  </small>
                    </td>
                    <td class="align-middle">@dateDateTime($docRoute->date_accepted) <br>
                     <small>   {{ $docRoute->route_purpose }} </small>

                    </td>
                    <td class="align-middle">                       
                        <div class="btn-group radius-13 font-xs" role="group" aria-label="Default button group">
                        <button type="button" class="btn btn-primary-600 px-8 py-8 text-xs radius-13"
                        data-bs-toggle="modal" data-bs-target="#forwardDocModal"
                            data-dts_document_id="{{ $docRoute->document->id }}"
                            data-dts_previousroute_id="{{ $docRoute->id }}"
                            data-doc_description="{{ $docRoute->docType->description }} - {{ $docRoute->document->description }}"
                        >Fwd</button>
                        <button type="button" class="btn btn-success-600 px-8 py-8 text-xs"
                        data-bs-toggle="modal" data-bs-target="#fileKeptDocModal"
                        data-routeId="{{ $docRoute->id }}"
                        data-docId="{{ $docRoute->document->id }}"
                        data-doc_description="{{ $docRoute->docType->description }} - {{ $docRoute->document->description }}"
                        >File</button>
                        <button type="button" class="btn btn-danger-500 px-8 py-8 text-xs radius-13"
                        data-bs-toggle="modal" data-bs-target="#releaseDocModal"
                        data-relRouteId="{{ $docRoute->id }}"
                        data-relDocId="{{ $docRoute->document->id }}"
                        data-relDoc_description="{{ $docRoute->docType->description }} - {{ $docRoute->document->description }}"
                        >Rel</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!--modal -->
<!-- ReleaseDocModal -->
<div class="modal fade" id="releaseDocModal" tabindex="-1" aria-labelledby="releaseDocModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('dts.received-docs.file-released') }}" method="post">
        @csrf
        <div class="modal-header">
          <h6 class="modal-title" id="releaseDocModalLabel">Release Document</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 row">
            <label for="releaseDocDesc" class="col-sm-3 col-form-label">Document Description</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="releaseDocDesc"  cols="30" rows="2" readonly></textarea>
            </div>
          </div>
          <input type="hidden" name="route_id" id="releaseRouteId">
          <input type="hidden" name="dts_document_id" id="releaseDocumentId">
          <div class="mb-3 row">
            <label for="releaseRemarks" class="col-sm-3 col-form-label">Remarks</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="releaseRemarks" name="remarks" rows="2"></textarea>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="releaseTo" class="col-sm-3 col-form-label">Release To</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="releaseTo" name="release_to">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Release</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- FileKeptDocModal -->
<div class="modal fade" id="fileKeptDocModal" tabindex="-1" aria-labelledby="fileKeptDocModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('dts.received-docs.file-kept') }}" method="post">
        @csrf
        <div class="modal-header">
          <h6 class="modal-title" id="fileKeptDocModalLabel">File/Kept Document</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 row">
            <label for="fileKeptDocDesc" class="col-sm-3 col-form-label">Document Description</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="fileKeptDocDesc" cols="30" rows="3" readonly></textarea>
              </div>
          </div>
          <input type="hidden" name="route_id" id="fileKeptRouteId">
          <input type="hidden" name="dts_document_id" id="fileKeptDocumentId">
          <div class="mb-3 row">
            <label for="fileKeptRemarks" class="col-sm-3 col-form-label">Remarks (optional)</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="fileKeptRemarks" name="remarks" rows="2"></textarea>
            </div>
          </div>
          <div class="row m-3">
            Note: No further actions needed for this document. I will just keep the file.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ForwadDocModal -->
<div class="modal fade" id="forwardDocModal" tabindex="-1" aria-labelledby="forwardDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('dts.deferred-docs.forwarding-the-document') }}" method="post">
          @csrf
        <div class="modal-header">
          <h6 class="modal-title" id="forwardDocModalLabel">Forward Document</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 row">
              <label for="fwdDocDesc" class="col-sm-3 col-form-label">Document Description</label>
              <div class="col-sm-9">
                <textarea name="docDescription" class="form-control" id="fwdDocDesc" readonly cols="60" rows="3"></textarea>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="fwdFrom" class="col-sm-3 col-form-label">From</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="fwdFrom" readonly value="{{ $mySection }} | {{ Auth::user()->name }}">
                <input type="hidden" name="from_section_id" value="{{ Auth::user()->section_id }}">
                <input type="hidden" id="fwddoc_id" name="dts_document_id">
                <input type="hidden" id="fwdprevious_route_id" name="previous_route_id"> <!-- Corrected Name -->
              </div>
            </div>
            <div class="mb-3 row">
              <label for="fwdRemarks" class="col-sm-3 col-form-label">Route Purpose</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="fwdRemarks" name="route_purpose" required>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="fwdCat" class="col-sm-3 col-form-label">Fwd Doc Category</label>
              <div class="col-sm-9">
                   <select class="form-control" id="fwd_io_type" name="fwd_io_type">
                      <option value="1">Incoming</option>
                      <option value="2" selected>Outgoing</option>
                  </select>
            </div>
            
            </div>
            <div class="mb-3 row">
              <label for="fwdToSectionId" class="col-sm-3 col-form-label">Forward To Section</label>
              <div class="col-sm-9">
                <select name="for_section_id" id="fwdToSectionId" class="form-control" required>
                  <option value="" style="visibility: hidden; display:none;">Select Section</option>
                  @foreach ($sections as $section)
                      <option value="{{ $section->id }}">{{ $section->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
  
            <div class="mb-3 row">
              <label for="to_user_id" class="col-sm-3 col-form-label">Personnel</label>
              <div class="col-sm-9">
                <select name="for_user_id" id="to_user_id" class="form-control" required> <!-- Corrected Name -->
                  <option value="" style="visibility: hidden; display:none;">Select Personnel</option>
                </select>
              </div>
            </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Forward Document</button>
        </div>
  
      </form>
      </div>
    </div>
  </div>
  <!--End ForwardDocModal -->

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
            ]
        });
    });
</script>

<script>
    const forwardDocModal = document.getElementById('forwardDocModal');
  if (forwardDocModal) {
      forwardDocModal.addEventListener('show.bs.modal', event => {
          const button = event.relatedTarget;
          const documentId = button.getAttribute('data-dts_document_id');
          const previousRouteId = button.getAttribute('data-dts_previousroute_id');
          const docDescription = button.getAttribute('data-doc_description');
  
          const fwdDocDesc = forwardDocModal.querySelector('#fwdDocDesc');
          const fwddoc_id = forwardDocModal.querySelector('#fwddoc_id');
          const fwdprevious_route_id = forwardDocModal.querySelector('#fwdprevious_route_id');
          const fwdFrom = forwardDocModal.querySelector('#fwdFrom');
  
          fwdDocDesc.value = docDescription;
          fwddoc_id.value = documentId;
          fwdprevious_route_id.value = previousRouteId;  // Set the previous route ID
      
      });
  }
  
  </script>
  <script>
    $(document).ready(function() {
        var baseUrl = "{{ url('/') }}"; // Get the base URL
  
        $('#fwdToSectionId').change(function() {
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

    <!-- Script to populate modal fields -->                          
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var releaseDocModal = document.getElementById('releaseDocModal');
    releaseDocModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var routeId = button.getAttribute('data-relRouteId');
      var documentId = button.getAttribute('data-relDocId');
      var docDescription = button.getAttribute('data-relDoc_description');

      var modal = this;
      modal.querySelector('#releaseRouteId').value = routeId;
      modal.querySelector('#releaseDocumentId').value = documentId;
      modal.querySelector('#releaseDocDesc').value = docDescription;
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var fileKeptDocModal = document.getElementById('fileKeptDocModal');
    fileKeptDocModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var routeId = button.getAttribute('data-routeId');
      var documentId = button.getAttribute('data-docId');
      var docDescription = button.getAttribute('data-doc_description');

      var modal = this;
      modal.querySelector('#fileKeptRouteId').value = routeId;
      modal.querySelector('#fileKeptDocumentId').value = documentId;
      modal.querySelector('#fileKeptDocDesc').value = docDescription;
    });
  });
</script>

@endsection
