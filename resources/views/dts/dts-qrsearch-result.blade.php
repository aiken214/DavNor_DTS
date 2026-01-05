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
          <div class="col-sm-12">  
            @if(isset($document) && $document != NULL)      
         <div class="card">
          <div class="card-heading"><h5> Search Result</h5></div>
          <div class="card-body">
       
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Tracking Code</th>
                  <th>Description</th>
                  <th> Routed to </th>
                  <th style="width: 10%;">Action</th>
                </tr>
              </thead>
              <tbody>
              <tr>
                <td>{{ $document->tracking_code }}</td>
                <td>
                  DocType: {{ $document->doc_type_description}} <br>
                  {{ $document->description }}
                </td>
                <td>{{ $document->for_section_name ?? '' }}</td>
                <td>
                  @if($document->routeForSecId==Auth::user()->section_id && $document->routeDateAccepted!=NULL)
                    Received on:  @dateDateTime($document->routeDateAccepted)
                  @else
                  <form action="{{ route('dts.quick-receipt') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden"  name="doc_track" value="{{ $document->tracking_code }}" >
                    <button type="submit" class="btn btn-primary btn-sm">Accept</button>
                </form>

                @endif
                </td>
              </tr>
            </tbody>
            </table>
            

          </div>          
        </div>   <!-- //card --> 
        @endif
          </div>
        
         </div> <!--//row-->
  
 <div>
 
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