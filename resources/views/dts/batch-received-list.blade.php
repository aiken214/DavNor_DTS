@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Batch Received</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">Batch Received</li>
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
                <h5 class="card-title">Submitted Batches</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="batchReceivedTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>From</th>
                    <th>Destination</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batches as $batch)
                <tr>
                    <td>{{ $batch->batch_code }}</td>
                    <td>{{ $batch->name }}<br><small class="text-muted">{{ $batch->description }}</small></td>
                    <td>{{ $batch->section->name ?? 'N/A' }}<br><small>{{ $batch->createdBy->name ?? '' }}</small></td>
                    <td>{{ $batch->forSection->name ?? 'N/A' }}</td>
                    <td>
                        {{ $batch->submittedBy->name ?? '' }}<br>
                        <small>@dateDateTime($batch->submit_date)</small>
                    </td>
                    <td>
                        <a href="{{ route('dts.batch-received.show', $batch->id) }}" class="btn btn-info btn-sm">View</a>
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
        $('#batchReceivedTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
