@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">DTS-Incoming</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                My DTS Section
            </a>
        </li>
       
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
        <h5 class="card-title mb-0">Incoming Document for Receipt</h5>
    </div>
    <div class="card-body">
        {{-- <table id="dataTable" class="table table-striped"> --}}
            <table  id="mydataTable" class="table table-striped">
            <thead>
                <tr>
                    <th style="display: none;"Tracking Code</th>
                    <th>Particulars</th>
                    <th>From</th>                  
                    <th>Date Forwarded</th>
                    <th>Action</th>
                   
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $documentRoute)
                    <tr>
                        <td class="align-middle" style="text-align: left !important; min-width: 6rem;"><a href="{{ route('dts.document-view', $documentRoute->document->id) }}"> {{ $documentRoute->document->tracking_code }}</a></td>
                        <td class="align-middle"><a href="{{ route('dts.documents.show', $documentRoute->document->id) }}">{{ $documentRoute->docType->description }} - {{ $documentRoute->document->description }}</a></td>
                        <td>{{ $documentRoute->fromSection->name ?? 'N/A' }} <br>
                            <small>{{ $documentRoute->fromUser->name ?? 'N/A' }} 
                              {{-- @can('dts_system_housekeeping')
                              | RouteID:{{ $documentRoute->id }} 
                              @endcan --}}
                             </small>
                        </td>                        
                        <td>@dateDateTime($documentRoute->date_forwarded)
                            <br>  <small>  {{ $documentRoute->route_purpose ?? "" }} 
                              {{-- @can('dts_system_housekeeping')<br> {{ $documentRoute->id }} @endcan --}}
                            </small></td>                        
                        <td >
                            <div class="btn-group dropstart">
                                <button class="btn btn-success-600 not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Action </button>
                                <ul class="dropdown-menu">
                                  <li><a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900" href="javascript:void(0)"
                                    data-bs-toggle="modal" data-bs-target="#acceptDocModal"
                                    data-adrouteid="{{ $documentRoute->id }}"
                                    data-details="{{ $documentRoute->docType->description }} - {{ $documentRoute->document->description }}"
                                    data-acptFrom="{{  $documentRoute->fromSection->name }} | {{ $documentRoute->fromUser->name }}"
                                    data-doc_id ="{{ $documentRoute->document->id }}"
                                    >
                                     Accept Document</a></li>
                                  <li>
                                    <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900" href="javascript:void(0)"
                                    data-bs-toggle="modal" data-bs-target="#acceptDocFileModal"
                                    data-adfrouteid="{{ $documentRoute->id }}"
                                    data-adfdetails="{{ $documentRoute->docType->description }} - {{ $documentRoute->document->description }}"
                                     data-adfFrom="{{  $documentRoute->fromSection->name }} | {{ $documentRoute->fromUser->name }}"
                                     data-adfdoc_id ="{{ $documentRoute->document->id }}"
                                     >
                                    Accept and File </a></li>
                                  <li><hr class="dropdown-divider"></li>
                                  <li>
                                    <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900" href="javascript:void(0)"
                                    data-bs-toggle="modal" data-bs-target="#forwardDocModal"
                                    data-fwdrouteid="{{ $documentRoute->id }}"
                                    data-fwddetails="{{ $documentRoute->docType->description }} - {{ $documentRoute->document->description }}"
                                    data-fwdfrom="{{ $documentRoute->fromSection->name }} | {{ $documentRoute->fromUser->name }}"
                                    data-fwddoc_id="{{ $documentRoute->document->id }}"
                                    data-fwdpurpose="{{ $documentRoute->route_purpose }}"
                                    data-fwdintended="{{ $documentRoute->intended_section_id }}"
                                    >
                                    Forward</a>
                                  </li>
                                  @if($pigeonholes->count() > 0)
                                  <li>
                                    <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900" href="javascript:void(0)"
                                    data-bs-toggle="modal" data-bs-target="#sendToPigeonholeModal"
                                    data-phrouteid="{{ $documentRoute->id }}"
                                    data-phdetails="{{ $documentRoute->docType->description }} - {{ $documentRoute->document->description }}"
                                    data-phfrom="{{ $documentRoute->fromSection->name }} | {{ $documentRoute->fromUser->name }}"
                                    data-phdoc_id="{{ $documentRoute->document->id }}"
                                    >
                                    Send to Pigeonhole</a>
                                  </li>
                                  @endif
                                </ul>
                            </div> 
                            
                        
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


<!--Accept Modal -->
<div class="modal fade" id="acceptDocModal" tabindex="-1" aria-labelledby="acceptDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('dts.incomingdoc-accept') }}" method="post">
            @csrf
        <div class="modal-header">
          <h6 class="modal-title fs-5" id="acceptDocModalLabel">Accept Document</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 row">
                <div class="col-sm-3">Description</div>
                <div class="col-sm-9 align-self-center" id="acptDocDesc">       </div>
              </div>
              <div class="mb-3 row">
                {{-- <label for="acptDocFrom" class="col-sm-3 col-form-label">From</label> --}}
                <div class="col-sm-3">From</div>
                
                <input type="hidden" name="doc_route_id" id="acptDocRouteId" class="form-control" >
                <input type="hidden" name="document_id" id="acptDocId" class="form-control">
           
                <div class="col-sm-9" id="acptDocFrom">                 
                </div>
              </div>
              <div class="mb-3 row">
                <label for="inc_io_type" class="col-sm-3 col-form-label">In Doc Category</label>
                <div class="col-sm-9">
                     <select class="form-control" id="inc_io_type" name="io_type">
                        <option value="1" selected>Incoming</option>
                        <option value="2">Outgoing</option>
                    </select>
              </div>              
              </div>

              <div class="mb-3 row">
                <label for="acptBy" class="col-sm-3 col-form-label">Accepted By</label>
                
                <div class="col-sm-9">
                 <input class="form-control" id="acptBy" value="{{ Auth::user()->name }}" readonly>
                </div>
              </div>
              <div class="mb-3 row">
                <label for="acptBy" class="col-sm-3 col-form-label">Remarks (optional)</label>
                <div class="col-sm-9">
                 <input class="form-control" name="accept_remarks">
                </div>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Accept</button>
        </div>
    </form>
      </div>
    </div>
  </div>
<!--//Accept Modal -->


<!--AcceptFile Modal -->
<div class="modal fade" id="acceptDocFileModal" tabindex="-1" aria-labelledby="acptDocFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('dts.incomingdoc-accept-andfile') }}" method="post">
            @csrf
        <div class="modal-header">
          <h6 class="modal-title fs-5" id="exampleModalLabel">Accept and File the Document</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 row">
                <div class="col-sm-3">Description</div>
                <div class="col-sm-9 align-self-center" id="acptDocFileDesc">
                   
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col-sm-3">From</div>
                <div class="col-sm-9" id="acptDocFileFrom"> </div>
              </div>
              <div class="mb-3 row">
                <label for="acptFileBy" class="col-sm-3 col-form-label">Accepted By</label>
                <div class="col-sm-9">{{ Auth::user()->name }}
                </div>
              </div>
              <div class="mb-3 row">
                <label for="acpFile_io_type" class="col-sm-3 col-form-label">In Doc Category</label>
                <div class="col-sm-9">
                     <select class="form-control" id="acpFile_io_type" name="io_type">
                        <option value="1" selected>Incoming</option>
                        <option value="2">Outgoing</option>
                    </select>
              </div>              
              </div>
              <div class="mb-3 row">
                <label for="acptFileBy" class="col-sm-3 col-form-label">Remarks (optional)</label>
                <div class="col-sm-9">
                 <input class="form-control" type="text" name="accept_remarks">
                 <input type="hidden" class="form-control" name="doc_route_id" id="acptDocFileRouteId">
                 <input type="hidden" name="document_id" id="acptDocFileDocumentId" class="form-control">
                </div>
              </div>
              <div class="mb-3 row"><div class="col-sm-12">
                 Note: No further actions needed for this document. I will just keep the file.
                </div>
                 </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning">Accept and File</button>
        </div>
    </form>
      </div>
    </div>
  </div>
<!--//AcceptFile Modal -->

<!--Forward Document Modal -->
<div class="modal fade" id="forwardDocModal" tabindex="-1" aria-labelledby="forwardDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('dts.incoming-docs.forward-doc') }}" method="post">
            @csrf
        <div class="modal-header">
          <h6 class="modal-title fs-5" id="forwardDocModalLabel">Forward Document</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 row">
                <div class="col-sm-3">Description</div>
                <div class="col-sm-9 align-self-center" id="fwdDocDesc"></div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">From</div>
                <input type="hidden" name="doc_route_id" id="fwdDocRouteId">
                <input type="hidden" name="document_id" id="fwdDocId">
                <div class="col-sm-9" id="fwdDocFrom"></div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Original Purpose</div>
                <div class="col-sm-9" id="fwdDocPurpose"></div>
            </div>
            <div class="mb-3 row">
                <label for="forward_section_id" class="col-sm-3 col-form-label">Forward to Section</label>
                <div class="col-sm-9">
                    <select class="form-control" id="forward_section_id" name="forward_section_id" required>
                        <option value="">-- Select Section --</option>
                        @foreach($forwardSections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="forward_remarks" class="col-sm-3 col-form-label">Remarks (optional)</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="forward_remarks" id="forward_remarks">
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Forwarded By</div>
                <div class="col-sm-9">{{ Auth::user()->name }}</div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning">Forward</button>
        </div>
    </form>
      </div>
    </div>
</div>
<!--//Forward Document Modal -->

@if($pigeonholes->count() > 0)
<!--Send to Pigeonhole Modal -->
<div class="modal fade" id="sendToPigeonholeModal" tabindex="-1" aria-labelledby="sendToPigeonholeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('dts.incoming-docs.send-to-pigeonhole') }}" method="post">
            @csrf
        <div class="modal-header">
          <h6 class="modal-title fs-5" id="sendToPigeonholeModalLabel">Send Document to Pigeonhole</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 row">
                <div class="col-sm-3">Description</div>
                <div class="col-sm-9 align-self-center" id="phDocDesc"></div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">From</div>
                <input type="hidden" name="doc_route_id" id="phDocRouteId">
                <input type="hidden" name="document_id" id="phDocId">
                <div class="col-sm-9" id="phDocFrom"></div>
            </div>
            <div class="mb-3 row">
                <label for="pigeonhole_id" class="col-sm-3 col-form-label">Pigeonhole</label>
                <div class="col-sm-9">
                    <select class="form-control" id="pigeonhole_id" name="pigeonhole_id" required>
                        <option value="">-- Select Pigeonhole --</option>
                        @foreach($pigeonholes as $pigeonhole)
                            <option value="{{ $pigeonhole->id }}" data-section="{{ $pigeonhole->section->name ?? '' }}">
                                {{ $pigeonhole->name }} — {{ $pigeonhole->section->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="phRemarks" class="col-sm-3 col-form-label">Remarks (optional)</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="remarks" id="phRemarks">
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Processed By</div>
                <div class="col-sm-9">{{ Auth::user()->name }}</div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Send to Pigeonhole</button>
        </div>
    </form>
      </div>
    </div>
</div>
<!--//Send to Pigeonhole Modal -->
@endif

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
        $('#mydataTable').DataTable({
            responsive: true,
            autoWidth: false, // Prevent auto-calculation of width by DataTables
            columnDefs: [
                { width: '12%', targets: 0 },
                { width: '35%', targets: 1 } // Set the width of the description column
            ],
            "pageLength": 25,
            order: [[0, 'desc']] 
            
        });
    });

   
</script>

<script>
// Function to submit the selected section_id
$('#acceptDocModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var routeId = button.data('adrouteid') // Extract info from data-* attributes
  var docDescription =button.data('details')
  var acptFrom =button.data('acptfrom')
  var docId = button.data('doc_id')
  var modal = $(this)
  modal.find('#acptDocDesc').text(docDescription)
  modal.find('#acptDocRouteId').val(routeId)
  modal.find('#acptDocFrom').text(acptFrom)
  modal.find('#acptDocId').val(docId)
});

$('#sendToPigeonholeModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    modal.find('#phDocRouteId').val(button.data('phrouteid'));
    modal.find('#phDocDesc').text(button.data('phdetails'));
    modal.find('#phDocFrom').text(button.data('phfrom'));
    modal.find('#phDocId').val(button.data('phdoc_id'));
});

$('#forwardDocModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    modal.find('#fwdDocRouteId').val(button.data('fwdrouteid'));
    modal.find('#fwdDocDesc').text(button.data('fwddetails'));
    modal.find('#fwdDocFrom').text(button.data('fwdfrom'));
    modal.find('#fwdDocId').val(button.data('fwddoc_id'));
    modal.find('#fwdDocPurpose').text(button.data('fwdpurpose'));
    var intended = button.data('fwdintended');
    if (intended) {
        modal.find('#forward_section_id').val(intended);
    } else {
        modal.find('#forward_section_id').val('');
    }
});

$('#acceptDocFileModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var adfRouteId = button.data('adfrouteid'); // Extract route ID from data-* attributes
    var adfDocDescription = button.data('adfdetails'); // Extract document description
    var adfFrom = button.data('adffrom'); // Extract "from" details
    var docId = button.data('adfdoc_id')
    // Select the modal using $(this)
    var modal = $(this);
    
    // Populate the modal fields with the extracted data
    modal.find('#acptDocFileDesc').text(adfDocDescription); // Set document description
    modal.find('#acptDocFileRouteId').val(adfRouteId); // Set route ID
    modal.find('#acptDocFileFrom').text(adfFrom); // Set "from" details
    modal.find('#acptDocFileDocumentId').val(docId)
});

  
  </script>
@endsection
