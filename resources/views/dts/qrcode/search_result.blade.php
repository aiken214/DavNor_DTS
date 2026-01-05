@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">DTS-QRCode Search</h6>
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
      
        <hr class="my-3">
        <div class="d-flex justify-content-center">
         
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
                { width: '13ch', targets: 0 },
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
