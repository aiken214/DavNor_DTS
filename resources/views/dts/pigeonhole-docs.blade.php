@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Pigeonholes</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                My DTS Section
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">Pigeonholes</li>
    </ul>
</div>

<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Available Pigeonholes</h5>
    </div>
    <div class="card-body">
        <table id="pigeonholeTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Description</th>
                    <th>Pending Docs</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pigeonholes as $pigeonhole)
                <tr>
                    <td class="align-middle">{{ $pigeonhole->name }}</td>
                    <td class="align-middle">{{ $pigeonhole->section->name ?? 'N/A' }}</td>
                    <td class="align-middle">{{ $pigeonhole->description ?? '' }}</td>
                    <td class="align-middle">
                        @if($pigeonhole->pending_count > 0)
                            <span class="badge bg-warning text-dark">{{ $pigeonhole->pending_count }}</span>
                        @else
                            <span class="text-muted">0</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dts.pigeonhole-docs.show', $pigeonhole->id) }}" class="btn btn-info btn-sm">View</a>
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
        $('#pigeonholeTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'asc']]
        });
    });
</script>
@endsection
