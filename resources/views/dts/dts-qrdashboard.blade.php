@extends('layouts.dts-admin')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold text-success mb-0">
      {{ $systemSetting->organization->name }}
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
            
      <!--Card New -->
                <div class="card h-100 radius-12">
                  <div class="card-body p-24">
                    <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-gradient-purple text-lilac-600 mb-16 radius-12">
                      <iconify-icon icon="mdi:qrcode-scan" class="h5 mb-0"></iconify-icon>
                    </div>
                    <h6 class="fw-semibold mb-12">Quick Document Receiving</h6>
                    <p>
                      Streamline document acceptance with QuickScan. Just scan a QR code to accept documents quickly and securely, reducing paperwork and processing time.
                    </p>
                    <label class="form-label">QR Code Scan</label>
                    <form action="{{ route('dts.quick-receipt') }}" method="post" id="qrcode-form">
                      @csrf
                      <div class="input-group">
                        <span class="input-group-text bg-base">
                          <iconify-icon icon="ic:twotone-qrcode"></iconify-icon>
                        </span>
                        <input type="text" class="form-control flex-grow-1" name="doc_track" autofocus>
                      </div>
                    </form>
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
          <h6 class="fw-semibold text-danger mb-6">Scan QR thru webcam.</h6>
        <p>
        You may also scan a QR code using your webcam. Just click the link button below to start scanning.

        </p> 
        <p>
          <a href="{{ route('dts.webcam-qr-scan') }}" class="btn btn-success">Webcam Scanning Page</a>
          
        </p>
          
          
        </div>
      </div>
     
    </div>
    <div class="col-sm-6">
      <div class="card">  
        <div class="card-header">{{ $mySection }} Today's Received Counts</div>      
        <div class="card-body">
            <h1 class="fw-semibold text-danger mb-0 text-center"> {{ $sectionReceivedCount->today_count ?? "" }} </h1>

         
        
        </div>
        

      </div>

    </div>
  </div>




  
        </div>
      </div>





@endsection
@section('scripts')

<script>
  document.getElementById('doc_track').addEventListener('change', function() {
      document.getElementById('qrcode-form').submit();
  });
</script>
  
  @endsection