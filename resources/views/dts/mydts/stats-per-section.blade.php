@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Stats Per Section</h6>
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
        <h5 class="card-title mb-0">Document Statistics Per Section</h5>
    </div>
    <div class="card-body">
        <table id="statsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Section</th>
                    <th class="text-center">Incoming</th>
                    <th class="text-center">Received</th>
                    <th class="text-center">Forwarded</th>
                    <th class="text-center">Deferred</th>
                    <th class="text-center">Guest Docs</th>
                    <th class="text-center">Parked Incoming</th>
                    <th class="text-center">Parked Pending</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sectionStats as $stat)
                    <tr>
                        <td><strong>{{ $stat->section_name }}</strong></td>
                        <td class="text-center">
                            @if($stat->count_incomming > 0)
                                <span class="badge bg-warning">{{ $stat->count_incomming }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stat->count_received > 0)
                                <span class="badge bg-success">{{ $stat->count_received }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stat->count_forwarded > 0)
                                <span class="badge bg-primary">{{ $stat->count_forwarded }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stat->count_deferred > 0)
                                <span class="badge bg-danger">{{ $stat->count_deferred }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stat->guestdoc_count > 0)
                                <span class="badge bg-info">{{ $stat->guestdoc_count }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stat->parked_incoming_count > 0)
                                <span class="badge bg-secondary">{{ $stat->parked_incoming_count }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stat->parked_pending_count > 0)
                                <span class="badge bg-secondary">{{ $stat->parked_pending_count }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#statsTable').DataTable({
            responsive: true,
            autoWidth: false,
            "pageLength": 50,
            order: [[0, 'asc']]
        });
    });
</script>
@endsection
