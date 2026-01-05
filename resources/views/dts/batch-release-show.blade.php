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
        <div class="row">
            <div class="col-sm-8 d-flex align-items-center">
                <h3 class="card-title">FOR BATCH RELEASING </h3>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('dts.batch-releases.index') }}" class="btn btn-primary bg-lilac-600 hover-bg-primary-700">Back to Batch List</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">Batch Name: <span>{{ $batchRelease->name }} | Batch Description: {{ $batchRelease->description }} | Release to: {{ $batchRelease->receiver_name }}</div>
            <div class="col-sm-4 text-end"> 
                @if($batchRelease->releaseby_id == NULL)
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#releaseModal">Release To</button>
               @elseif($batchRelease->releaseby_id != NULL)
               <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#releaseModal">Change Receiver</button>
                @endif

                @if($batchRelease->release_date !=NULL)

                <a href="{{ route('dts.batch-releases-for-print-view', $batchRelease->id) }}" class="btn btn-primary btn-sm">Print View</a>
                @endif
        </div>
        </div>
      <hr class="my-3">
        <div class="row">
            <div class="col-sm-6">
                <h6 class="fw-semibold">Received Routed Documents</h6>
                <table id="routeTable" class="table">
                    <thead>
                    <tr>
                        <th> TrackNo</th>
                       <th> Description</th>
                       <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($receivedDocuments) && $receivedDocuments != NULL)
                    @foreach($receivedDocuments as $doc)
                    <tr>
                        <td>{{ $doc->document->tracking_code }}</td>
                        <td>{{ $doc->document->description }}</td>
                        <td>
                            @if($batchRelease->release_date == NULL)
                            <form action="{{ route('dts.batch-releases-add-item') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="doc_route_id" value="{{ $doc->id }}">
                                <input type="hidden" name="batch_release_id" value="{{ $batchRelease->id }}">
                                <button type="submit" class="btn btn-primary-400 btn-sm">Add</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                </table>
 
            </div>
            <div class="col-sm-6">
                <h6 class="fw-semibold">For Batch Release Documents</h6>
                <table id="forReleaseTable" class="table">
                    <thead>
                    <tr>
                        <th>TrackNo</th>
                        <th> Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($forBatchRelease) && $forBatchRelease != NULL)
                    @foreach($forBatchRelease as $relDoc)
                    <tr>
                        <td>{{ $relDoc->tracking_code }}</td>
                        <td>{{ $relDoc->description }} </td>
                        <td>
                            @if($batchRelease->release_date == NULL)
                            <form action="{{ route('dts.batch-releases-remove-item') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $relDoc->id }}">
                            <input type="hidden" name="doc_route_id" value="{{ $relDoc->route_id }}">
                            <button type="submit" class="btn btn-danger-300 btn-sm">Remove</button>
                        </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
<!-- Modal for Release -->
<div class="modal fade" id="releaseModal" tabindex="-1" aria-labelledby="releaseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="releaseModalLabel">Release Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add your form elements here -->
                <form action="{{ route('dts.batch-releases-release-docs') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_release_id" value="{{ $batchRelease->id }}">
                    
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Release To</label>
                        <input type="text" class="form-control" id="release_to" name="receiver_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Release</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection



@section('scripts')
<script>
    $(document).ready(function() {
        $('#routeTable').DataTable({
            responsive: true,
            order: [[0, 'desc']]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#forReleaseTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            "pageLength": 25,
        });
    });
</script>
<script>
    function submitSectionForm(sectionId) {
        $('#station-id').val(sectionId);
        $('#section-form').submit();
    }
</script>

@endsection
