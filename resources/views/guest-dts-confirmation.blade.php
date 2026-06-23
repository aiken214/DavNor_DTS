<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'DepEd DTS') }} - Submission Confirmation</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
  <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/modern-theme.css') }}">
  <style>
    @media print {
      .no-print { display: none !important; }
      body { background: #fff !important; }
      .print-area { box-shadow: none !important; border: none !important; }
    }
  </style>
</head>
<body>
@if (Auth::check())
    <script>window.location.href = "{{ url('/dashboard') }}";</script>
@endif

<section class="auth bg-base d-flex flex-wrap">
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{ asset('assets/images/dts-system-welcome.png') }}" alt="Auth Image">
        </div>
    </div>
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-800-px mx-auto w-100">

            <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-24 fw-semibold text-lg radius-8 d-flex align-items-center gap-2 no-print">
                <iconify-icon icon="mdi:check-circle-outline" class="icon text-xl"></iconify-icon>
                Your document has been submitted successfully!
            </div>

            <div class="card print-area" id="printArea">
                <div class="card-header text-center">
                    <h5 class="card-title mb-0">Guest Document Submission Receipt</h5>
                    <small class="text-secondary-light">Reference No: <strong class="text-danger-600">{{ $refNo }}</strong></small>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div style="display:inline-block;">{!! $qrCode !!}</div>
                        <div class="mt-1"><strong class="text-danger-600" style="font-size:1.3rem">{{ $refNo }}</strong></div>
                    </div>
                    <table class="table table-borderless mb-0">
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
                            <td>{{ $guestDocument->created_at->format('M d, Y h:i A') }}</td>
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
                    <hr>
                    <p class="text-center text-secondary-light text-sm mb-0">
                        Please keep this receipt for your records. Present the Reference No. <strong>{{ $refNo }}</strong> when following up.
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 mt-24 justify-content-center no-print">
                <button onclick="window.print()" class="btn btn-primary-600 d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:printer" class="icon text-lg"></iconify-icon>
                    Print Receipt
                </button>
                <a href="{{ route('guest-dts') }}" class="btn btn-outline-primary-600">Submit Another Document</a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to Sign-In</a>
            </div>

            <div class="mt-32 text-center text-sm no-print">
                <p>Back to <a href="{{ route('login') }}" class="text-primary-600 fw-semibold">Sign-In Page</a></p>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
</body>
</html>
