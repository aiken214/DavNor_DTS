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
        <li class="fw-medium">
            <a href="{{ route('dts.batch-submits.index') }}" class="hover-text-primary">Batch Submit</a>
        </li>
        <li>-</li>
        <li class="fw-medium">{{ $batchSubmit->batch_code }}</li>
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
                <h3 class="card-title">BATCH SUBMIT</h3>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('dts.batch-submits.index') }}" class="btn btn-primary bg-lilac-600 hover-bg-primary-700">Back to Batch List</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">
                <strong>{{ $batchSubmit->batch_code }}</strong> | Name: {{ $batchSubmit->name }} | Description: {{ $batchSubmit->description }}<br>
                <small>Destination: <strong>{{ $batchSubmit->forSection->name ?? 'N/A' }}</strong></small>
            </div>
            <div class="col-sm-4 text-end">
                @if($batchSubmit->submittedby_id == NULL)
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#finalizeModal">Submit Batch</button>
                @endif
                <a href="{{ route('dts.batch-submits-for-print-view', $batchSubmit->id) }}" class="btn btn-primary btn-sm">Print View</a>
            </div>
        </div>
        <hr class="my-3">
        <div class="row">
            <div class="col-sm-6">
                @if($batchSubmit->submittedby_id == NULL)
                <h6 class="fw-semibold">Add Document</h6>
                <form action="{{ route('dts.batch-submits-add-doc') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_submit_id" value="{{ $batchSubmit->id }}">
                    <div class="mb-2">
                        <label class="form-label">Doc Type</label>
                        <select class="form-control" name="dts_doc_type_id" required>
                            <option value="">-- Select Doc Type --</option>
                            @foreach($docTypes as $docType)
                                <option value="{{ $docType->id }}">{{ $docType->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2" required placeholder="Document description"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Actions Needed / Purpose</label>
                        <input type="text" class="form-control" name="actions_needed" required placeholder="e.g. For approval, For signature">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-2">Add Document</button>
                </form>
                @else
                <h6 class="fw-semibold">Batch Submitted</h6>
                <p class="text-muted">Submitted by {{ $batchSubmit->submittedBy->name }} on @dateDateTime($batchSubmit->submit_date)</p>
                @endif
            </div>
            <div class="col-sm-6">
                <h6 class="fw-semibold">Documents in This Batch</h6>
                <table id="batchDocsTable" class="table">
                    <thead>
                        <tr>
                            <th>Tracking Code</th>
                            <th>Doc Type</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batchDocuments as $doc)
                        <tr>
                            <td>{{ $doc->tracking_code }}</td>
                            <td>{{ $doc->type_description }}</td>
                            <td>{{ $doc->description }}</td>
                            <td>
                                @if($batchSubmit->submittedby_id == NULL)
                                <form action="{{ route('dts.batch-submits-remove-doc') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $doc->id }}">
                                    <input type="hidden" name="doc_route_id" value="{{ $doc->doc_route_id }}">
                                    <input type="hidden" name="dts_document_id" value="{{ $doc->doc_id }}">
                                    <button type="submit" class="btn btn-danger-300 btn-sm">Remove</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($batchSubmit->submittedby_id == NULL)
<div class="modal fade" id="finalizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h6 class="modal-title">Submit Batch: {{ $batchSubmit->batch_code }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form action="{{ route('dts.batch-submits-finalize') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_submit_id" value="{{ $batchSubmit->id }}">
                    <p>This will submit <strong>{{ $batchDocuments->count() }}</strong> document(s) to the Records Section. Are you sure?</p>
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button type="button" class="btn btn-secondary px-40 py-11" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-48 py-12">Confirm Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#batchDocsTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
        });
    });
</script>
@endsection
