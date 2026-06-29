@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Batch Received</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dts.batch-received.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="mdi:package-variant-closed-check" class="icon text-lg"></iconify-icon>
                Batch Received
            </a>
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
                <h3 class="card-title">BATCH RECEIVED</h3>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('dts.batch-received.index') }}" class="btn btn-primary bg-lilac-600 hover-bg-primary-700">Back to List</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-6">
                <strong>{{ $batchSubmit->batch_code }}</strong> | {{ $batchSubmit->name }}<br>
                @if($batchSubmit->description)
                <small class="text-muted">{{ $batchSubmit->description }}</small><br>
                @endif
                <small>From: <strong>{{ $batchSubmit->section->name ?? 'N/A' }}</strong> ({{ $batchSubmit->createdBy->name ?? '' }})</small><br>
                <small>Destination: <strong>{{ $batchSubmit->forSection->name ?? 'N/A' }}</strong></small>
            </div>
            <div class="col-sm-6 text-end">
                <small>Submitted: {{ $batchSubmit->submittedBy->name ?? '' }} — @dateDateTime($batchSubmit->submit_date)</small>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">Documents ({{ $batchDocuments->count() }})</h6>
            @if($batchDocuments->where('status_id', 1)->count() > 0 && $batchSubmit->for_section_id)
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#forwardBatchModal">
                <iconify-icon icon="fluent:document-arrow-right-20-filled" class="me-1"></iconify-icon>
                Forward Batch to {{ $batchSubmit->forSection->name ?? 'Destination' }}
            </button>
            @endif
        </div>
        <table id="batchRecDocsTable" class="table table-striped w-100">
            <thead>
                <tr>
                    <th>Tracking Code</th>
                    <th>Doc Type</th>
                    <th>Description</th>
                    <th>Actions Needed</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batchDocuments as $doc)
                <tr>
                    <td><a href="{{ route('dts.document-view', $doc->doc_id) }}">{{ $doc->tracking_code }}</a></td>
                    <td>{{ $doc->type_description }}</td>
                    <td>{{ $doc->description }}</td>
                    <td>{{ $doc->actions_needed }}</td>
                    <td>
                        @if($doc->status_id == 1)
                            <span class="badge bg-warning">Incoming</span>
                        @elseif($doc->status_id == 2)
                            <span class="badge bg-info">Received</span>
                        @elseif($doc->status_id == 6)
                            <span class="badge bg-secondary">Re-routed</span>
                        @else
                            <span class="badge bg-primary">{{ $doc->status_id }}</span>
                        @endif
                    </td>
                    <td>
                        @if($doc->status_id == 1)
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $doc->id }}">Delete</button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#rerouteModal{{ $doc->id }}">Re-route</button>
                        @else
                            <span class="text-muted small">Processed</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach($batchDocuments as $doc)
    @if($doc->status_id == 1)
    <div class="modal fade" id="deleteModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h6 class="modal-title">Delete Document: {{ $doc->tracking_code }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form action="{{ route('dts.batch-received.delete-doc') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $doc->id }}">
                        <input type="hidden" name="doc_route_id" value="{{ $doc->doc_route_id }}">
                        <input type="hidden" name="dts_document_id" value="{{ $doc->doc_id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tracking Code</label>
                            <input type="text" class="form-control" value="{{ $doc->tracking_code }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" class="form-control" value="{{ $doc->type_description }} - {{ $doc->description }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason for deletion</label>
                            <input type="text" class="form-control" name="delete_reason" required placeholder="e.g. Physical document not present">
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" class="btn btn-secondary px-40 py-11" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger px-48 py-12">Confirm Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rerouteModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h6 class="modal-title">Re-route Document: {{ $doc->tracking_code }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form action="{{ route('dts.batch-received.reroute-doc') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doc_route_id" value="{{ $doc->doc_route_id }}">
                        <input type="hidden" name="dts_document_id" value="{{ $doc->doc_id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tracking Code</label>
                            <input type="text" class="form-control" value="{{ $doc->tracking_code }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" class="form-control" value="{{ $doc->type_description }} - {{ $doc->description }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Re-route to Section</label>
                            <select class="form-control" name="reroute_section_id" required>
                                <option value="">-- Select Section --</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason</label>
                            <input type="text" class="form-control" name="reroute_reason" required placeholder="e.g. Mistakenly included, should go to...">
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" class="btn btn-secondary px-40 py-11" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning px-48 py-12">Confirm Re-route</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@if($batchDocuments->where('status_id', 1)->count() > 0 && $batchSubmit->for_section_id)
<div class="modal fade" id="forwardBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h6 class="modal-title">Forward Batch: {{ $batchSubmit->batch_code }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form action="{{ route('dts.batch-received.forward-batch') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_submit_id" value="{{ $batchSubmit->id }}">
                    <p>This will forward <strong>{{ $batchDocuments->where('status_id', 1)->count() }}</strong> pending document(s) to <strong>{{ $batchSubmit->forSection->name ?? 'N/A' }}</strong>.</p>
                    <p class="text-muted small">Documents that have been deleted or re-routed will be skipped.</p>
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button type="button" class="btn btn-secondary px-40 py-11" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-48 py-12">Confirm Forward</button>
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
        $('#batchRecDocsTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'asc']],
            pageLength: 25,
        });
    });
</script>
@endsection
