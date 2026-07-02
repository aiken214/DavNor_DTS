@extends('layouts.dts-admin')

@section('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
    #qrcode, #qrcode * { width: 180px; height: 180px; }
    .queue-number { font-size: 3.5rem; font-weight: 900; font-family: 'Courier New', monospace; letter-spacing: -2px; }
    .queue-panel { border-radius: 1rem; transition: all 0.3s ease; }
    .queue-panel:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    @media print {
        @page { size: auto; margin: 0mm; }
        .no-print, .sidebar, .navbar-header, .dashboard-main .navbar-header { display: none !important; }
        #print-placard {
            width: 100% !important; max-width: 100% !important; height: 100vh !important;
            position: fixed !important; left: 0 !important; top: 0 !important; z-index: 9999;
            display: flex !important; flex-direction: column !important; align-items: center !important;
            justify-content: center !important; padding: 4rem !important;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        .qr-print-wrapper { width: 320px !important; height: 320px !important; }
        #qrcode, #qrcode * { width: 260px !important; height: 260px !important; }
    }
</style>
@endsection

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Queue Management</h6>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Section 1: Station Config --}}
    <div class="card mb-3 no-print">
        <div class="card-body">
            <h6 class="text-muted text-uppercase fw-bold small mb-3">1. Station Config & Control</h6>
            <div class="d-flex flex-wrap align-items-end gap-3">
                <form method="POST" action="{{ route('dts.queue.generate') }}" class="d-flex flex-grow-1 gap-2" id="generateForm">
                    @csrf
                    <input type="text" name="q_name" value="{{ $queueName }}" class="form-control" required placeholder="Queue station name">
                    <button type="button" class="btn btn-primary fw-bold text-nowrap" onclick="confirmGenerate()">
                        Generate New Queue
                    </button>
                </form>
                <div class="d-flex gap-2">
                    <a href="{{ url('/queue/display') }}" target="_blank" class="btn btn-outline-primary fw-bold text-nowrap">
                        <iconify-icon icon="mdi:television" class="me-1"></iconify-icon> TV Display
                    </a>
                    <form method="POST" action="{{ route('dts.queue.toggle-break') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn {{ $onBreak ? 'btn-warning' : 'btn-dark' }} fw-bold text-nowrap">
                            @if($onBreak)
                                <iconify-icon icon="mdi:coffee" class="me-1"></iconify-icon> Resume Lines
                            @else
                                <iconify-icon icon="mdi:pause-circle" class="me-1"></iconify-icon> Go On Break
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: QR Code Placard --}}
    <div id="print-placard" class="card mb-4 border-0 text-white" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                <div>
                    <span class="badge bg-white bg-opacity-25 text-white text-uppercase fw-bold small mb-2">Scan to Enter Queue</span>
                    <h4 class="fw-bold mb-1" id="placard-queue-name">{{ $queueName }}</h4>
                    <p class="text-white-50 small mb-3">Scan the QR code to take a virtual priority voucher instantly.</p>
                    <button onclick="window.print()" class="btn btn-light btn-sm fw-bold no-print">
                        <iconify-icon icon="mdi:printer" class="me-1"></iconify-icon> Print Placard
                    </button>
                </div>
                <div class="qr-print-wrapper bg-white rounded-3 p-3 d-flex align-items-center justify-content-center">
                    <div id="qrcode"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Two-Panel Dashboard --}}
    <div class="row g-4 no-print" id="dashboard-panels">
        {{-- Receiving Panel --}}
        <div class="col-md-6">
            <div class="card queue-panel h-100 border-primary border-top border-3">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase small px-3 py-2">Station 1: Receiving</span>
                        <span class="text-muted small fw-bold">Waiting: <strong class="text-primary fs-6" id="rec-waiting">{{ $totalWaitingRec }}</strong></span>
                    </div>
                    <div class="text-center bg-light rounded-3 py-4 mb-3">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Now Calling</small>
                        <div class="queue-number text-primary" id="rec-current">{{ $currentRecDisplay }}</div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <button class="btn btn-light w-100 fw-bold small text-uppercase" onclick="queueAction('recall', 'receiving')">
                                <iconify-icon icon="mdi:refresh"></iconify-icon> Recall
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-light w-100 fw-bold small text-uppercase" onclick="queueAction('complete', 'receiving')">
                                <iconify-icon icon="mdi:check"></iconify-icon> Close
                            </button>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <div class="mb-2 small text-muted fw-bold">Up Next: <span class="font-monospace text-dark" id="rec-next">{{ $recTrailHtml }}</span></div>
                        <button class="btn btn-primary w-100 fw-bold py-3 fs-5" onclick="queueAction('call-next', 'receiving')">
                            Call Next Visitor
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Releasing Panel --}}
        <div class="col-md-6">
            <div class="card queue-panel h-100 border-success border-top border-3">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success-subtle text-success fw-bold text-uppercase small px-3 py-2">Station 2: Releasing</span>
                        <span class="text-muted small fw-bold">Waiting: <strong class="text-success fs-6" id="rel-waiting">{{ $totalWaitingRel }}</strong></span>
                    </div>
                    <div class="text-center bg-light rounded-3 py-4 mb-3">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Now Calling</small>
                        <div class="queue-number text-success" id="rel-current">{{ $currentRelDisplay }}</div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <button class="btn btn-light w-100 fw-bold small text-uppercase" onclick="queueAction('recall', 'releasing')">
                                <iconify-icon icon="mdi:refresh"></iconify-icon> Recall
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-light w-100 fw-bold small text-uppercase" onclick="queueAction('complete', 'releasing')">
                                <iconify-icon icon="mdi:check"></iconify-icon> Close
                            </button>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <div class="mb-2 small text-muted fw-bold">Up Next: <span class="font-monospace text-dark" id="rel-next">{{ $relTrailHtml }}</span></div>
                        <button class="btn btn-success w-100 fw-bold py-3 fs-5" onclick="queueAction('call-next', 'releasing')">
                            Call Next Visitor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize QR Code
    const qrContainer = document.getElementById("qrcode");
    new QRCode(qrContainer, {
        text: "{{ $clientUrl }}",
        width: 260,
        height: 260,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    function confirmGenerate() {
        if (confirm('Generate New Queue? This updates station name and resets all active tickets.')) {
            document.getElementById('generateForm').submit();
        }
    }

    function pad(n) {
        return String(n).padStart(3, '0');
    }

    function formatNext(arr, prefix) {
        if (!arr || arr.length === 0) return 'None pending';
        return arr.map(n => prefix + '-' + pad(n)).join(', ');
    }

    // AJAX queue actions
    function queueAction(action, type) {
        $.ajax({
            url: '{{ url("/dts/queue") }}/' + action,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                type: type
            },
            success: function() {
                refreshDashboard();
            },
            error: function(xhr) {
                console.error('Queue action error:', xhr);
            }
        });
    }

    function refreshDashboard() {
        $.getJSON('{{ route("queue.api.status") }}', function(data) {
            $('#rec-current').text(data.recActive ? 'REC-' + pad(data.recActive) : '--');
            $('#rel-current').text(data.relActive ? 'REL-' + pad(data.relActive) : '--');
            $('#rec-next').text(formatNext(data.recNext, 'REC'));
            $('#rel-next').text(formatNext(data.relNext, 'REL'));
            $('#rec-waiting').text(data.recWaiting);
            $('#rel-waiting').text(data.relWaiting);
        });
    }

    // Poll every 3 seconds
    setInterval(refreshDashboard, 3000);
</script>
@endsection
