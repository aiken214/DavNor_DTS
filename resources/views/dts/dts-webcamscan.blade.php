@extends('layouts.dts-admin')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold text-success mb-0 d-none d-md-block">
        {{ $systemSetting->custom_system_name }}
    </h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
         My Station Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">
          
        <div class="btn-group dropstart">
            <button class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                 @if(isset($mySection) && $mySection != NULL)
                {{ $mySection }}
                @elseif(isset($myAllSections) && count($myAllSections) > 0)                        
                    Select Section
                @endif
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
        
        <div class="card-body">

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

         
               
    
  
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <div class="fw-semibold text-primary mb-0">QR Scan <span id="qrcapture" class="text-success"></span></div>
        </div>
        <div class="card-body">          
          
          <form action="{{ route('dts.qrcode-search') }}" method="post" id="webcam-form" class="row g-2 justify-content-end">
            @csrf
           <div id="scanner">
             <div id="reader" width="600px"></div>
             <input type="hidden" id="webcamqr_scan" name="doc_track">
           </div>
          
         
           </form>    
          
          
        </div>
      </div>
     
    </div>
    <div class="col-sm-6">
      <div class="card">    
        <div class="card-header">
          <div class="fw-semibold text-primary mb-0">Capture Result and Daily Counts</div>
          </div>    
        <div class="card-body">
          <h6 id="capture_qrcode" class="text-success"></h6>

           <div> {{ $mySection }} Today's Received Counts</div>
          <h1 class="fw-semibold text-danger mb-0 text-center"> {{ $sectionReceivedCount->today_count ?? '' }} </h1>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
          <div class="d-flex align-items-center gap-2">
              <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
              {{ session('success') }}
          </div>
          <button class="remove-button text-success-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
      </div>
    @endif



      </div>

    </div>
  </div>




  
        </div>
      </div>





@endsection
@section('scripts')

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script type="text/javascript">
  function onScanSuccess(decodedText, decodedResult) {
    document.getElementById('webcamqr_scan').value = decodedText;
    document.getElementById('capture_qrcode').innerText = `Captured QR Code: ${decodedText}`;
    document.getElementById('qrcapture').innerText = `Result: ${decodedText}`;
    // setTimeout(() => {
      document.getElementById('webcam-form').submit();
    // }, 20);
  }

  function onScanFailure(error) {
    console.warn(`QR error = ${error}`);
  }

  let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
      fps: 10,
      qrbox: { width: 250, height: 250 },
      rememberLastUsedCamera: true,
      facingMode: "environment"
    });
  html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
  
  @endsection