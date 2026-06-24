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
                <h5 class="card-title">Batch List</h5>
            </div>
            <div class="col-sm-4 text-end">
                <button type="button" class="btn rounded-pill btn-lilac-600 radius-8 px-20 py-6" data-bs-toggle="modal" data-bs-target="#addBatchReleaseModal">Add</button>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <table id="batchListTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th class="description-column">Description</th>
                    
                    <th> Created</th>
                    <th> Released</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batchReleases as $batch)
                <tr>
                   <td>{{ $batch->batch_code }}</td>
                   <td>{{ $batch->name }}</td>
                   <td>{{ $batch->description }}</td>
                     
                     <td>
                        {{ $batch->createdBy->name }}<br>
                  <small> @dateDateTime($batch->created_at)</small>     
                     </td>
                     <td>
                        @if($batch->releaseby_id != NULL)
                      To:  {{ $batch->receiver_name }}<br>
                    <small> @dateDateTime($batch->release_date)</small> <br>
                   <small>by: {{ $batch->releasedBy->name }}</small> 
                    @else                        
                        {!! $batch->releasedBy->name ?? '
                         <span class="badge text-sm fw-semibold border border-warning-600 text-warning-600 bg-transparent px-20 py-9 radius-4 text-white">
                           Not Yet
                            </span>
                        
                        ' !!}<br>
                        @endif
                     </td>
                     <td>
                        <a href="{{ route('dts.batch-releases.show', $batch->id) }}" class="btn btn-info btn-sm">View</a>
                          @if($batch->releaseby_id == NULL)
                          <a href="javascript:void(0)" class="btn btn-success btn-sm"
                            data-bs-toggle="modal" data-bs-target="#editBatchReleaseModal"
                            data-batch-id="{{ $batch->id }}"
                            data-batch-name="{{ $batch->name }}"
                            data-batch-description="{{ $batch->description }}">Edit</a>
                          @endif
                     </td>
                </tr>
              @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Add Batch Release Modal -->
<div class="modal fade" id="addBatchReleaseModal" tabindex="-1" aria-labelledby="addBatchReleaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBatchReleaseModalLabel">Add Batch Release</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Batch Release Form -->
                <form action="{{ route('dts.batch-releases.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="batchName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="batchName" name="name" required>
                        <input type="hidden" name="createdby_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="section_id" value="{{ Auth::user()->section_id }}">
                    </div>
                    <div class="mb-3">
                        <label for="batchDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="batchDescription" name="description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Edit Batch Release Modal -->
<div class="modal fade" id="editBatchReleaseModal" tabindex="-1" aria-labelledby="editBatchReleaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBatchReleaseModalLabel">Edit Batch Release</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBatchForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editBatchName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editBatchName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBatchDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editBatchDescription" name="description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
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
        $('#batchListTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'desc']]
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editBatchReleaseModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var batchId = button.getAttribute('data-batch-id');
            var batchName = button.getAttribute('data-batch-name');
            var batchDescription = button.getAttribute('data-batch-description');

            var form = editModal.querySelector('#editBatchForm');
            form.action = "{{ url('dts/batch-releases-update') }}/" + batchId;
            editModal.querySelector('#editBatchName').value = batchName;
            editModal.querySelector('#editBatchDescription').value = batchDescription;
        });
    });
</script>
@endsection
