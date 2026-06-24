@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Pigeonhole: {{ $pigeonhole->name }}</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dts.pigeonhole-docs.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="mdi:mailbox-open-outline" class="icon text-lg"></iconify-icon>
                Pigeonholes
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">{{ $pigeonhole->name }}</li>
    </ul>
</div>

<div class="card basic-data-table">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-8 d-flex align-items-center">
                <h5 class="card-title mb-0">{{ $pigeonhole->name }} — {{ $pigeonhole->section->name ?? 'N/A' }}</h5>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('dts.pigeonhole-docs.index') }}" class="btn btn-secondary btn-sm">Back to Pigeonholes</a>
            </div>
        </div>
        @if($pigeonhole->description)
        <p class="text-muted mb-0 mt-1">{{ $pigeonhole->description }}</p>
        @endif
    </div>
    <div class="card-body">
        <table id="phDocsTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th style="text-align: left;">Tracking Code</th>
                    <th>Description</th>
                    <th>From</th>
                    <th>Date Forwarded</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td class="align-middle" style="text-align: left; min-width: 6rem;">
                        <a href="{{ route('dts.document-view', $doc->document->id) }}">{{ $doc->document->tracking_code }}</a>
                    </td>
                    <td class="align-middle">{{ $doc->docType->description ?? '' }} - {{ $doc->document->description }}</td>
                    <td class="align-middle">
                        {{ $doc->fromSection->name ?? 'N/A' }}<br>
                        <small>{{ $doc->fromUser->name ?? 'N/A' }}</small>
                    </td>
                    <td class="align-middle">@dateDateTime($doc->date_forwarded)</td>
                    <td class="align-middle">
                        @if($doc->status_id == 4)
                            <span class="badge bg-success">Released</span>
                        @elseif($doc->status_id == 1)
                            <span class="badge bg-warning">For Release</span>
                        @else
                            <span class="badge bg-info">Re-entered</span>
                        @endif
                    </td>
                    <td class="align-middle">
                        @if($doc->status_id == 1)
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#releaseModal{{ $doc->id }}">Release</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $doc->id }}">Cancel</button>
                        @elseif($doc->status_id == 4)
                            <span class="text-muted small">{{ $doc->date_acted ? \Carbon\Carbon::parse($doc->date_acted)->format('M d, Y h:i A') : '' }}</span>
                        @else
                            <span class="text-muted small">Re-entered</span>
                        @endif
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

@foreach($documents as $doc)
    @if($doc->status_id == 1)
    <div class="modal fade" id="releaseModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h6 class="modal-title">Release Document: {{ $doc->document->tracking_code }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form action="{{ route('dts.pigeonhole-docs.release') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doc_route_id" value="{{ $doc->id }}">
                        <input type="hidden" name="pigeonhole_id" value="{{ $pigeonhole->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tracking Code</label>
                            <input type="text" class="form-control" value="{{ $doc->document->tracking_code }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" class="form-control" value="{{ $doc->docType->description ?? '' }} - {{ $doc->document->description }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Releasing to Pigeonhole</label>
                            <input type="text" class="form-control" value="{{ $pigeonhole->name }} — {{ $pigeonhole->section->name ?? 'N/A' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks (Optional)</label>
                            <input type="text" class="form-control" name="release_remarks" placeholder="e.g. Released to pigeonhole">
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" class="btn btn-secondary px-40 py-11" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success px-48 py-12">Confirm Release</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($doc->status_id == 1)
    <div class="modal fade" id="cancelModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h6 class="modal-title">Cancel Pigeonhole: {{ $doc->document->tracking_code }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form action="{{ route('dts.pigeonhole-docs.cancel') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doc_route_id" value="{{ $doc->id }}">
                        <input type="hidden" name="pigeonhole_id" value="{{ $pigeonhole->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tracking Code</label>
                            <input type="text" class="form-control" value="{{ $doc->document->tracking_code }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" class="form-control" value="{{ $doc->docType->description ?? '' }} - {{ $doc->document->description }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason for cancellation</label>
                            <input type="text" class="form-control" name="cancel_reason" required placeholder="e.g. Wrong pigeonhole assignment">
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" class="btn btn-secondary px-40 py-11" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger px-48 py-12">Confirm Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#phDocsTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
