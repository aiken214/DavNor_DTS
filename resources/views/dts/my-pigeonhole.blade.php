@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">My Pigeonhole: {{ $pigeonhole->name }}</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">My Pigeonhole</li>
        <li class="fw-medium">
            @if(isset($mySection) && $mySection != NULL)
            <span class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 not-active px-18 py-11">
                {{ $mySection }}
            </span>
            @endif
        </li>
    </ul>
</div>

<div class="card basic-data-table">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-8 d-flex align-items-center">
                <h5 class="card-title mb-0">{{ $pigeonhole->name }}</h5>
            </div>
        </div>
        @if($pigeonhole->description)
        <p class="text-muted mb-0 mt-1">{{ $pigeonhole->description }}</p>
        @endif
    </div>
    <div class="card-body">
        <table id="myPigeonholeTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>Tracking Code</th>
                    <th>Description</th>
                    <th>From</th>
                    <th>Date Released</th>
                    <th>Remarks</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td class="align-middle" style="min-width: 6rem;">
                        <a href="{{ route('dts.document-view', $doc->document->id) }}">{{ $doc->document->tracking_code }}</a>
                    </td>
                    <td class="align-middle">{{ optional($doc->document->docType)->description ?? '' }} - {{ $doc->document->description }}</td>
                    <td class="align-middle">
                        {{ $doc->fromSection->name ?? 'N/A' }}<br>
                        <small>{{ $doc->fromUser->name ?? 'N/A' }}</small>
                    </td>
                    <td class="align-middle">@dateDateTime($doc->date_forwarded)</td>
                    <td class="align-middle">
                        @if($doc->end_remarks && str_contains($doc->end_remarks, '| '))
                            <small>{{ Str::afterLast($doc->end_remarks, '| ') }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
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
                        @if($doc->status_id == 4)
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reEntryModal{{ $doc->id }}">Re-entry</button>
                        @elseif($doc->status_id != 4 && $doc->status_id != 1)
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
    @if($doc->status_id == 4)
    <div class="modal fade" id="reEntryModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h6 class="modal-title">Re-entry Document: {{ $doc->document->tracking_code }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form action="{{ route('dts.my-pigeonhole.re-entry') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doc_route_id" value="{{ $doc->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tracking Code</label>
                            <input type="text" class="form-control" value="{{ $doc->document->tracking_code }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" class="form-control" value="{{ optional($doc->document->docType)->description ?? '' }} - {{ $doc->document->description }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Will be sent back to</label>
                            <input type="text" class="form-control" value="{{ $doc->fromSection->name ?? 'N/A' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Re-entry Remarks</label>
                            <input type="text" class="form-control" name="reentry_remarks" placeholder="e.g. Corrections needed, document returned">
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" class="btn btn-secondary px-40 py-11" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning px-48 py-12">Confirm Re-entry</button>
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
        $('#myPigeonholeTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
