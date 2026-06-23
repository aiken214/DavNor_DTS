@extends('layouts.dts-admin')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Guest Document - QR Scan Result</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                My DTS Section
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">QR Scan Result</li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Guest Document Found</h5>
    </div>
    <div class="card-body">
        @if(isset($guestDocument))
        <div class="row">
            <div class="col-sm-8">
                <table class="table table-borderless">
                    <tr>
                        <th style="width:200px;">Reference No.</th>
                        <td><strong class="text-danger-600" style="font-size:1.3rem">{{ $refNo }}</strong></td>
                    </tr>
                    <tr>
                        <th>Submitted By</th>
                        <td>{{ $guestDocument->submittedby }}</td>
                    </tr>
                    <tr>
                        <th>Organization</th>
                        <td>{{ $guestDocument->organization ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Document Type</th>
                        <td>{{ optional($guestDocument->docType)->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $guestDocument->doc_description }}</td>
                    </tr>
                    <tr>
                        <th>Actions Needed</th>
                        <td>{{ $guestDocument->actions_needed ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Routed To</th>
                        <td>{{ optional($guestDocument->receiverSection)->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Intended Receiver</th>
                        <td>{{ optional($guestDocument->intendedReceiver)->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date Submitted</th>
                        <td>@dateDateTime($guestDocument->created_at)</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($guestDocument->is_accepted)
                                <span class="badge bg-success">Accepted</span>
                            @else
                                <span class="badge bg-warning">Pending Acceptance</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 text-center">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted mb-2">Guest Document</div>
                        <div class="fw-bold text-danger-600" style="font-size:1.5rem">{{ $refNo }}</div>
                    </div>
                </div>
                <a href="{{ route('dts.webcam-qr-scan') }}" class="btn btn-primary mt-3" style="width:100%">Scan Another</a>
                <a href="{{ route('dts.guest-doc') }}" class="btn btn-outline-primary mt-2" style="width:100%">Go to Guest Docs</a>
            </div>
        </div>
        @else
        <div class="text-center text-muted py-5">
            <iconify-icon icon="mdi:file-question-outline" style="font-size:3rem"></iconify-icon>
            <p class="mt-2">Guest document not found.</p>
        </div>
        @endif
    </div>
</div>

@endsection
