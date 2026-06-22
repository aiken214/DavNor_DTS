@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Routed for Me</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
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
        <h5 class="card-title mb-0">Documents Routed for Me</h5>
    </div>
    <div class="card-body">
        <table id="routedForMeTable" class="table table-striped">
            <thead>
                <tr>
                    <th style="text-align: left;">Tracking</th>
                    <th>Particulars</th>
                    <th>From</th>
                    <th>To Section</th>
                    <th>Date Forwarded</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $route)
                    <tr>
                        <td style="text-align: left; min-width: 6rem;">
                            <a href="{{ route('dts.document-view', $route->dts_document_id) }}">
                                {{ optional($route->document)->tracking_code ?? 'N/A' }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('dts.documents.show', $route->dts_document_id) }}">
                                {{ optional($route->docType)->description ?? '' }} - {{ optional($route->document)->description ?? '' }}
                            </a>
                        </td>
                        <td>
                            {{ optional($route->fromSection)->name ?? 'N/A' }}<br>
                            <small>{{ optional($route->fromUser)->name ?? 'N/A' }}</small>
                        </td>
                        <td>{{ optional($route->forSection)->name ?? 'N/A' }}</td>
                        <td>@dateDateTime($route->date_forwarded)</td>
                        <td>
                            @if($route->date_accepted)
                                <span class="badge bg-success">Received</span>
                            @elseif($route->status_id == 1)
                                <span class="badge bg-warning">Pending</span>
                            @elseif($route->status_id == 6)
                                <span class="badge bg-secondary">Forwarded</span>
                            @else
                                <span class="badge bg-info">Status {{ $route->status_id }}</span>
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
        $('#routedForMeTable').DataTable({
            responsive: true,
            autoWidth: false,
            "pageLength": 25,
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
