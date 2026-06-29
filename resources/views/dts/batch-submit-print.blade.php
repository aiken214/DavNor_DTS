@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Batch Submit - Print View</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dts.batch-submits.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                Batch Submit
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">{{ $batchSubmit->batch_code }}</li>
    </ul>
</div>

<div class="card basic-data-table">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-8 d-flex align-items-center">
                <h3 class="card-title">BATCH TRANSMITTAL</h3>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('dts.batch-submits.show', $batchSubmit->id) }}" class="btn btn-primary bg-lilac-600 hover-bg-primary-700">Back</a>
                <button class="btn btn-primary" onclick="printForPrint()">Print</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="forPrint" class="card">
            <div class="card-header">
                <div class="d-flex flex-column align-items-center mb-3">
                    <div class="d-flex" style="padding-bottom: 8px;"><img src="{{ asset('assets/images/DepEd_Seal.png') }}" alt="" width="75px" height="75px"></div>
                    <div class="d-flex oldenglish" style="font-size: 16px"> Republic of the Philippines </div>
                    <div class="d-flex oldenglish" style="font-size: 18px"> Department of Education </div>
                    <div class="d-flex trajan" style="padding-top: 8px"> Region XI </div>
                    <div class="d-flex trajan" style="font-weight: 600"> Schools Division of Davao del Norte </div>
                </div>
                <hr>
                <h6 class="card-title">Batch Transmittal: <span>{{ $batchSubmit->batch_code }} — {{ $batchSubmit->name }}</span></h6>
                @if($batchSubmit->description)
                <div>Description: {{ $batchSubmit->description }}</div>
                @endif
                <div>Destination: {{ $batchSubmit->forSection->name ?? 'N/A' }}</div>
                <div style="margin-bottom: 10px;">Submit Date: {{ $batchSubmit->submit_date ?? 'Draft' }}</div>

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:80px;">QR</th>
                            <th>Tracking Code</th>
                            <th>Doc Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batchDocuments as $document)
                        <tr>
                            <td style="text-align:center;">{!! QrCode::size(50)->generate($document->tracking_code) !!}</td>
                            <td>{{ $document->tracking_code }}</td>
                            <td>{{ $document->type_description }}</td>
                            <td>{{ $document->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    Submitted by: {{ $batchSubmit->submittedBy->name ?? $batchSubmit->createdBy->name }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
    @media print {
        body * {
            visibility: hidden;
        }
        #forPrint, #forPrint * {
            visibility: visible;
        }
        #forPrint {
            position: absolute;
            left: 0;
            top: 50px;
            width: 100%;
        }
    }
@endsection

@section('scripts')
<script>
    function printForPrint() {
        var printContents = document.getElementById('forPrint').innerHTML;
        var originalContents = document.body.innerHTML;
        var styledPrintContents = '<div style="margin-top: 50px;">' + printContents + '</div>';
        document.body.innerHTML = styledPrintContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
</script>
@endsection
