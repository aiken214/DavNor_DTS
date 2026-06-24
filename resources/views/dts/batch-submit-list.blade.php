@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Batch Submit</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">Batch Submit</li>
        <li class="fw-medium">
            @if(isset($mySection) && $mySection != NULL)
            <span class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 not-active px-18 py-11">
                {{ $mySection }}
            </span>
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
    <button class="remove-button text-danger-600 text-xxl line-height-1"><iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
</div>
@endif

<div class="card basic-data-table">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-8 d-flex align-items-center">
                <h5 class="card-title">My Batch Submittals</h5>
            </div>
            <div class="col-sm-4 text-end">
                <button type="button" class="btn rounded-pill btn-lilac-600 radius-8 px-20 py-6" data-bs-toggle="modal" data-bs-target="#addBatchSubmitModal">New Batch</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="batchSubmitTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th class="description-column">Description</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batchSubmits as $batch)
                <tr>
                    <td>{{ $batch->batch_code }}</td>
                    <td>{{ $batch->name }}</td>
                    <td>{{ $batch->description }}</td>
                    <td>
                        {{ $batch->createdBy->name }}<br>
                        <small>@dateDateTime($batch->created_at)</small>
                    </td>
                    <td>
                        @if($batch->submittedby_id != NULL)
                            <span class="badge bg-success">Submitted</span><br>
                            <small>@dateDateTime($batch->submit_date)</small>
                        @else
                            <span class="badge text-sm fw-semibold border border-warning-600 text-warning-600 bg-transparent px-20 py-9 radius-4">Draft</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dts.batch-submits.show', $batch->id) }}" class="btn btn-info btn-sm">View</a>
                        @if($batch->submittedby_id == NULL)
                        <a href="javascript:void(0)" class="btn btn-success btn-sm"
                            data-bs-toggle="modal" data-bs-target="#editBatchSubmitModal"
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

<div class="modal fade" id="addBatchSubmitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Batch Submit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dts.batch-submits.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="batchName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="batchName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="batchDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="batchDescription" name="description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editBatchSubmitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Batch Submit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBatchSubmitForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editBatchName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editBatchName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBatchDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editBatchDescription" name="description"></textarea>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#batchSubmitTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'desc']]
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editBatchSubmitModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var batchId = button.getAttribute('data-batch-id');
            var batchName = button.getAttribute('data-batch-name');
            var batchDescription = button.getAttribute('data-batch-description');
            var form = editModal.querySelector('#editBatchSubmitForm');
            form.action = "{{ url('dts/batch-submits-update') }}/" + batchId;
            editModal.querySelector('#editBatchName').value = batchName;
            editModal.querySelector('#editBatchDescription').value = batchDescription;
        });
    });
</script>
@endsection
