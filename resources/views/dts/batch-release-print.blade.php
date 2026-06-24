@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">DTS-Received Document</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                My DTS Section
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">
            @if(isset($mySection) && $mySection != NULL)
            <div class="btn-group dropstart">
                <button class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $mySection }}
                </button>
                <ul class="dropdown-menu">
                    @if(isset($myAllSections))
                    @foreach($myAllSections as $section)
                        <li>
                            <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900"
                               href="javascript:void(0)"
                               onclick="submitSectionForm('{{ $section->id }}')">
                               {{ $section->name }}
                            </a>
                        </li>
                    @endforeach
                    @endif
                </ul>
            </div>
            
            <!-- Form to submit the selected section_id -->
            <form id="section-form" method="POST" action="{{ route('user.updateStation') }}" style="display: none;">
                @csrf
                <input type="hidden" name="station_id" id="station-id">
            </form>
            @endif
        </li>
    </ul>
</div>

<div class="card basic-data-table">
    
  @if ($errors->any())
  <div class="m-3 alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
  <div class="d-flex align-items-center gap-2">
  <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
  <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
  </ul>
  </div>
  <button class="remove-button text-danger-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
  </div>
  @endif


    <div class="card-header">
        <div class="row">
            <div class="col-sm-8 d-flex align-items-center">
                <h3 class="card-title">FOR BATCH RELEASING </h3>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('dts.batch-releases.index') }}" class="btn btn-primary bg-lilac-600 hover-bg-primary-700">Back to Batch List</a>
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
        <h6 class="card-title">Batch: <span>{{ $batchRelease->batch_code }} — {{ $batchRelease->name }} </span> </h6>
       <div>Description: {{ $batchRelease->description }} </div> 
       <div style="margin-bottom: 10px;">Release Date:  {{  $batchRelease->release_date ?? '' }}</div> 

       
    <table class="table">
        <thead>
            <tr>
                <th style="width:80px;">QR</th>
                <th>Tracking Code</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop through $forBatchReleasesDocuments --}}
            @if(isset($forBatchReleasesDocuments) && count($forBatchReleasesDocuments) > 0)
            @foreach($forBatchReleasesDocuments as $document)
            <tr>
            <td style="text-align:center;">{!! QrCode::size(50)->generate($document->tracking_code) !!}</td>
            <td>{{ $document->tracking_code }}</td>
            <td>{{ $document->description }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

   <div style="margin-top: 20px;">
   Received by:  {{ $batchRelease->receiver_name }}
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
            top: 50px; /* Adjust the top margin as needed */
            width: 100%;
        }
     }

@endsection


@section('scripts')


<script>
    function printForPrint() {
         var printContents = document.getElementById('forPrint').innerHTML;
         var originalContents = document.body.innerHTML;
 
         // Add margin to the top of the content to be printed
         var styledPrintContents = '<div style="margin-top: 50px;">' + printContents + '</div>';
 
         document.body.innerHTML = styledPrintContents;
 
         window.print();
 
         document.body.innerHTML = originalContents;
         window.location.reload(); // Reload the page to restore the original content
     }
 </script>
@endsection
