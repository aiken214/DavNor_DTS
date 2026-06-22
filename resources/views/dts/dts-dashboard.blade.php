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
      @if(isset($mySection) && $mySection != NULL)
      <li>-</li>
      <li class="fw-medium">
        @if(isset($myAllSections) && count($myAllSections) > 1)
        <div class="btn-group dropstart">
            <button class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $mySection }}
            </button>
            <ul class="dropdown-menu">
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
        <form id="section-form" method="POST" action="{{ route('user.updateStation') }}" style="display: none;">
            @csrf
            <input type="hidden" name="station_id" id="station-id">
        </form>
        @else
        <span class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 not-active px-18 py-11">
            {{ $mySection }}
        </span>
        @endif
      </li>
      @endif
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
     @if($mySection != NULL)
         <div class="row">
          <div class="col-sm-6">         
            
      <!--Card New -->
                <div class="card h-100 radius-12">
                  <div class="card-body p-24">
                    <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-gradient-purple text-lilac-600 mb-16 radius-12">
                      <iconify-icon icon="mdi:qrcode-scan" class="h5 mb-0"></iconify-icon>
                    </div>
                    <h6 class="fw-semibold mb-12">Quick Document Receiving</h6>
                    <p>
                      Scan a QR code using a hardware scanner or your phone's camera to accept documents quickly.
                    </p>
                    <label class="form-label">QR Code / Tracking Number</label>
                    <form action="{{ route('dts.quick-receipt') }}" method="post" id="qrcode-form">
                      @csrf
                      <div class="input-group mb-12">
                        <span class="input-group-text bg-base">
                          <iconify-icon icon="ic:twotone-qrcode"></iconify-icon>
                        </span>
                        <input type="text" class="form-control flex-grow-1" name="doc_track" id="doc_track" autofocus>
                      </div>
                    </form>

                    <button type="button" class="btn btn-success btn-sm w-100" id="toggleCameraBtn" style="display:inline-flex !important; align-items:center; justify-content:center; gap:0.5rem;">
                      <iconify-icon icon="mdi:camera" style="font-size:1.1rem; line-height:1;"></iconify-icon>
                      <span id="cameraBtnText">Scan with Camera</span>
                    </button>
                    <div id="dashboard-reader" class="mt-12" style="display:none;"></div>
                  </div>
                </div>


      <!--//End Card New -->    
          </div>
          <div class="col-sm-6">
            <div class="card h-100 radius-12 text-end">
              <div class="card-body p-24">
                  <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-gradient-success text-success-600 mb-16 radius-12">
                      <iconify-icon icon="hugeicons:binary-code" class="h5 mb-0"></iconify-icon> 
                  </div>
                  <h6 class="mb-8">Alternate Doc Receiving</h6>
                  <p class="card-text mb-8 text-secondary-light pb-8">
                    While you do not have a QR code scanner, you may go to the <a href="{{ route('dts.incoming-docs.index') }}">  <button class="px-3" style="background-color: rgb(234, 247, 190); border-radius: 10px;"> Incoming-route Page </button> </a> search for the document then accept or encode the tracking number below, click submit and accept the document.
                  </p>
                  
                    <div class="text-end pt-8">
                    <form action="{{ route('dts.qrcode-search') }}" method="post"  class="row g-2 justify-content-end">
                     @csrf
                    <div class="col-auto">
                      <label for="inputPassword2" class="visually-hidden">Tracking Number</label>
                      <input type="text" class="form-control" id="inputPassword2" name="doc_track" placeholder="Tracking Number">
                    </div>
                    <div class="col-auto">
                      <button type="submit" class="btn btn-primary mb-3">Submit</button>
                    </div>
                    </form>                  
                  </div>
                <div>
                 
                </div>
                
              </div>
          </div>

          </div>
         </div> <!--//row-->
  <div style="padding-top: 25px;"> </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          <h6 class="fw-semibold text-primary mb-6">Dedicated QR Scanning Page</h6>
        <p>
          For continuous scanning sessions, use the dedicated scanning page with a larger camera view.
        </p>
        <p>
          <a href="{{ route('dts.webcam-qr-scan') }}" class="btn btn-success btn-sm">
            <iconify-icon icon="mdi:qrcode-scan" class="icon me-1"></iconify-icon> Open Scanning Page
          </a>
        </p>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">  
        <div class="card-header">{{ $mySection }} Today's Received Counts</div>      
        <div class="card-body">
            <h1 class="fw-semibold text-danger mb-0 text-center"> {{ $sectionReceivedCount->today_count ?? "" }} </h1>
      
            @if(session('success'))          
            
            <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
              <div class="d-flex align-items-center gap-2">
                  <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                  {{ session('success') }}
              </div>
              <button class="remove-button text-success-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
          </div>
        
        @endif
      
        </div><!--card body -->
        

      </div>

    </div>
  </div>




  
        </div>



      @else
              {{-- Wala pa pong napiling section --}}

              <div class="alert alert-warning bg-warning-100 text-warning-600 border-warning-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
                   Please ask the system administrator to assign you to a section.
                </div>
                <button class="remove-button text-warning-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
              </div>
      
      @endif


      </div>





@endsection
@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
  document.getElementById('doc_track').addEventListener('change', function() {
      document.getElementById('qrcode-form').submit();
  });

  var html5QrCode = null;
  var cameraRunning = false;

  document.getElementById('toggleCameraBtn').addEventListener('click', function() {
      var readerDiv = document.getElementById('dashboard-reader');
      var btnText = document.getElementById('cameraBtnText');

      if (cameraRunning) {
          html5QrCode.stop().then(function() {
              readerDiv.style.display = 'none';
              readerDiv.innerHTML = '';
              btnText.textContent = 'Scan with Camera';
              cameraRunning = false;
              html5QrCode = null;
          });
      } else {
          readerDiv.style.display = 'block';
          btnText.textContent = 'Stop Camera';
          cameraRunning = true;

          html5QrCode = new Html5Qrcode("dashboard-reader");
          html5QrCode.start(
              { facingMode: "environment" },
              { fps: 10, qrbox: { width: 220, height: 220 } },
              function(decodedText) {
                  document.getElementById('doc_track').value = decodedText;
                  html5QrCode.stop().then(function() {
                      readerDiv.style.display = 'none';
                      readerDiv.innerHTML = '';
                      btnText.textContent = 'Scan with Camera';
                      cameraRunning = false;
                      html5QrCode = null;
                      document.getElementById('qrcode-form').submit();
                  });
              },
              function(errorMessage) {}
          ).catch(function(err) {
              readerDiv.innerHTML = '<div class="alert alert-warning mt-2 p-2 small">Camera access denied or unavailable. Make sure you are using HTTPS and allow camera permission.</div>';
              btnText.textContent = 'Scan with Camera';
              cameraRunning = false;
              html5QrCode = null;
          });
      }
  });
</script>

@endsection