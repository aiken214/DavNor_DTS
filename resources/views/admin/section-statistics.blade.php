@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Section Statistics</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">
            @if(isset($mySection) && $mySection != NULL)
            <span class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 not-active px-18 py-11">
                {{ $mySection }}
            </span>
            @endif
        </li>
    </ul>
</div>

<div class="card mb-24">
    <div class="card-header">
        <h5 class="card-title mb-0">Select Section</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.section-statistics.index') }}">
            <div class="row align-items-end">
                <div class="col-sm-8">
                    <label for="section_id" class="form-label">Section</label>
                    <select class="form-control" name="section_id" id="section_id" onchange="this.form.submit()">
                        <option value="">-- Select a Section --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ $selectedSectionId == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary">View Statistics</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selectedSection)

<h5 class="fw-semibold text-success mb-3">
    Statistics for: {{ $selectedSection->name }}
</h5>

<div class="card mb-24">
    <div class="card-header">
        <h5 class="card-title mb-0">Document Status Summary</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Incoming</th>
                        <td class="text-center">
                            <span class="badge bg-warning" style="min-width:50px">{{ $sectionDocCounts->count_incomming ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Received / Pending</th>
                        <td class="text-center">
                            <span class="badge bg-success" style="min-width:50px">{{ $sectionDocCounts->count_received ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Forwarded (Routes)</th>
                        <td class="text-center">
                            <span class="badge bg-primary" style="min-width:50px">{{ $sectionDocCounts->count_forwarded ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Forwarded (Status)</th>
                        <td class="text-center">
                            <span class="badge bg-info" style="min-width:50px">{{ $sectionDocCounts->forwardedroute_status_count ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Deferred</th>
                        <td class="text-center">
                            <span class="badge bg-danger" style="min-width:50px">{{ $sectionDocCounts->count_deferred ?? 0 }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Guest Documents</th>
                        <td class="text-center">
                            <span class="badge bg-info" style="min-width:50px">{{ $sectionDocCounts->guestdoc_count ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Re-Entered</th>
                        <td class="text-center">
                            <span class="badge bg-secondary" style="min-width:50px">{{ $sectionDocCounts->reentered_count ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Parked Incoming</th>
                        <td class="text-center">
                            <span class="badge bg-secondary" style="min-width:50px">{{ $sectionDocCounts->parked_incoming_count ?? 0 }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Parked Pending</th>
                        <td class="text-center">
                            <span class="badge bg-secondary" style="min-width:50px">{{ $sectionDocCounts->parked_pending_count ?? 0 }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <div class="fw-semibold text-success mb-0">Documents Received</div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Last Year Count ({{ date('Y', strtotime('-1 year')) }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->last_year_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>This Year Count ({{ date('Y') }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->ytd_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Today's Count ({{ date('F j, Y') }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->today_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Yesterday Count ({{ date('F j, Y', strtotime('-1 day')) }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->yesterday_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>This Week Count ({{ date('F j, Y', strtotime('monday this week')) }} - {{ date('F j, Y', strtotime('sunday this week')) }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->week_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Last Week Count ({{ date('F j, Y', strtotime('monday last week')) }} - {{ date('F j, Y', strtotime('sunday last week')) }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->last_week_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>This Month Count ({{ date('F') }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->month_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Last Month Count ({{ date('F', strtotime('first day of last month')) }})</th>
                        <td class="text-center">{{ $sectionReceivedStats->last_month_count ?? 0 }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <div class="fw-semibold text-primary mb-0">Documents Forwarded</div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Last Year Count ({{ date('Y', strtotime('-1 year')) }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->last_year_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>This Year Count ({{ date('Y') }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->ytd_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Today's Count ({{ date('F j, Y') }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->today_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Yesterday Count ({{ date('F j, Y', strtotime('-1 day')) }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->yesterday_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>This Week Count ({{ date('F j, Y', strtotime('monday this week')) }} - {{ date('F j, Y', strtotime('sunday this week')) }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->week_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Last Week Count ({{ date('F j, Y', strtotime('monday last week')) }} - {{ date('F j, Y', strtotime('sunday last week')) }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->last_week_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>This Month Count ({{ date('F') }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->month_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Last Month Count ({{ date('F', strtotime('first day of last month')) }})</th>
                        <td class="text-center">{{ $sectionForwardedStats->last_month_count ?? 0 }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@else

<div class="card">
    <div class="card-body text-center py-5">
        <iconify-icon icon="mdi:chart-bar" class="text-4xl text-muted mb-3" style="font-size:3rem;"></iconify-icon>
        <p class="text-muted">Select a section from the dropdown above to view its statistics.</p>
    </div>
</div>

@endif

@endsection
