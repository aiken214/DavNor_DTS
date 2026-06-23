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
                    <th>Tracking Code</th>
                    <th>Description</th>
                    <th>From</th>
                    <th>Date Forwarded</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td class="align-middle" style="min-width: 6rem;">
                        <a href="{{ route('dts.document-view', $doc->document->id) }}">{{ $doc->document->tracking_code }}</a>
                    </td>
                    <td class="align-middle">{{ $doc->docType->description ?? '' }} - {{ $doc->document->description }}</td>
                    <td class="align-middle">
                        {{ $doc->fromSection->name ?? 'N/A' }}<br>
                        <small>{{ $doc->fromUser->name ?? 'N/A' }}</small>
                    </td>
                    <td class="align-middle">@dateDateTime($doc->date_forwarded)</td>
                    <td class="align-middle">
                        @if($doc->status_id == 1)
                            <span class="badge bg-info">Incoming</span>
                        @elseif($doc->status_id == 2)
                            <span class="badge bg-success">Received</span>
                        @elseif($doc->status_id == 3)
                            <span class="badge bg-secondary">Filed</span>
                        @elseif($doc->status_id == 6)
                            <span class="badge bg-primary">Forwarded</span>
                        @else
                            <span class="badge bg-dark">{{ $doc->status_id }}</span>
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
