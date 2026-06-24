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
                            <span class="text-muted small">{{ $doc->date_acted ? \Carbon\Carbon::parse($doc->date_acted)->format('M d, Y h:i A') : '' }}</span>
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
        $('#myPigeonholeTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
