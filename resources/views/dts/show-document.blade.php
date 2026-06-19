@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Document Tracking System</h6>
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

<div class="card">
    
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
        <h5 class="card-title mb-0">New Document</h5>
    </div>
    <div class="card-body">
       <div class="row">
        <!--left side-->
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="card-title">Document Details</h5>
                        </div>
                        <div class="col-sm-4 text-end">
                            @can('dts_records_mngt_edit')
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDocModal">Edit</button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th class="align-middle"> Tracking Number : </th>
                            <td>
                                <p class="form-control-plaintext" style="font-weight: bold; font-size: 2.0rem; color:rgb(145, 5, 5)">{{ $document->tracking_code }}</p>
                            </td>
        
                        </tr>
                        
                        <tr>
                            <th > Document Type </th>
                            <td>{{ $document->docType->description ?? '' }}</td>
                        </tr>
                        <tr>
                            <th> Description </th>
                            <td>{{ $document->description ?? '' }}</td>
                        </tr>
                        <tr>
                            <th> From  </th>
                            <td>  
                                @if($document->guest_origin_name != NULL)
                                Guest:  {{ $document->guest_origin_name }} <br>
                                <small> (Accepted by: {{ $document->fromUser->name ?? '' }})</small>
                                 @else
                                 {{ $document->fromUser->name ?? '' }}
                                 @endif
                                </td>
                        </tr>
                        <tr>
                            <th> DTS Section/Guest Organization </th>
                            <td>
                                @if($document->guest_origin_organization != NULL)
                                {{ $document->guest_origin_organization }}
                                <br> <small> Thru:
                                ({{ $document->fromSection->name ?? '' }})
                                </small>
                                @else
                                {{ $document->fromSection->name ?? '' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th> Date Created </th>
                            <td>@dateDateTime($document->created_at)</td>
                        </tr>
                        <tr>
                            <th> Actions Needed </th>
                            <td>{{ $document->actions_needed }}</td>
                        </tr>
                        
                    </table>        
                </div>
            </div>
            <hr>
            <!-- Tracking History -->
            @if(isset($docRoutes) && $docRoutes != NULL)
            <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tracking History</h5>
                    </div>
                <div class="card-body">
                    @foreach($docRoutes as $route)
        <div class="arrow-down"></div>
     
        <div class="m-5">
           <div> 
            @can('permission_show')
                <div class="small centering">Route ID : {{ $route->id ?? '' }}  -- STATUS ID {{ $route->status_id }}</div>
            @endcan

                <div class="centering">To : {{ $route->forSection->name ?? '' }}</div>  
                @if($route->receiverUser != NULL) 
                <div class="centering">Receiver: {{ $route->receiverUser->name ?? '' }}</div>
                @endif
                <div class="centering">Route Date: @dateDateTime($route->created_at)</div>
                <div class="centering">
                    Date Accepted: 
                    @if($route->date_accepted != NULL)                    
                    @dateDateTime($route->date_accepted)
                    @else
                    <span class="text-danger">NOT YET ACCEPTED</span>
                    @endif
                </div>
                <div class="centering">Actions Taken: {{ $route->actions_taken ?? '' }}</div>
                                                            
            </div>
        </div>
                   
        @endforeach

                </div>
            </div>

            @endif
            <!-- End Tracking History -->
        </div>
          <!--Endleft side-->
        <!--right side-->
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div style="with:122px;">
                                <div style="text-align: center;">DTS</div>
                                <div style="text-align: center;">{!! $qrCodes['styleRound'] !!}</div>
                             <div style="text-align: center;">{{ $document->tracking_code }}</div>
                             </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-8">
                    <!-- Buttons -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Print QR code</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                         
                        <a href="{{ route('dts.barcode-slip-print', $document->id) }}" target="new" class="btn rounded-pill btn-outline-danger-600 radius-8 px-20 py-11" style="width:100%">QRCode Attached Slip</a>
                        <a href="{{ route('dts.barcode-top-right-print', $document->id) }}"  target="new" class="btn rounded-pill btn-outline-lilac-600 radius-8 px-20 py-11" style="width:100%">@ Document Top Right</a>
                        <a href="{{ route('dts.barcode-bottom-right-print', $document->id) }}" target="new" class="btn rounded-pill btn-outline-info-600 radius-8 px-20 py-11" style="width:100%">@ Document Bottom Right</a>
                        <a href="{{ route('dts.barcode-bottom-left-print', $document->id) }}" target="new" class="btn rounded-pill btn-outline-success-600 radius-8 px-20 py-11" style="width:100%">@ Document Bottom Left</a>
                       
                        </div> 
                </div>
            </div>
            <!--End Buttons -->   
                </div>
            </div>

       @if(isset($latestRoute) && $latestRoute != NULL)     
           <!-- Button for Accept--> 
          <div class="row mt-20">
             <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Accept Document for {{ $mySection }}</h5>
                </div>
                <div class="card-body">
                    @if($latestRoute->for_section_id ==Auth::user()->section_id && $latestRoute->date_accepted!=NULL)
                      <div>  Received Date:  @dateDateTime($latestRoute->date_accepted)</div>    
                  @else

                    <div class="d-flex flex-wrap align-items-center gap-3">
                       
                        <form action="{{ route('dts.quick-receipt') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden"  name="doc_track" value="{{ $document->tracking_code }}" >
                            <button type="submit" class="btn btn-success radius-8 px-20 py-11" style="width:100%">Accept this Document for {{ $mySection}}</button>
                        </form>
                    </div>

                    @endif

                </div>
             </div>

          </div>
             <!-- Button for Accept--> 
        @endif

        </div>
        <!--End right side-->

       </div>
        
    </div>
</div>

<!--modals-->
<!-- Modal Start -->
<div class="modal fade" id="editDocModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Document</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form action="{{ route('dts.document-update') }}" method="post">
                    @csrf
                    <div class="row">   
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tracking Number</label>
                            <input type="text" class="form-control radius-8" value="{{ $document->tracking_code }}" readonly>
                            <input type="hidden" name="doc_id" value="{{ $document->id }}">
                        </div>
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Document Type</label>
                           <select name="dts_doc_type_id" class="form-control">
                                @foreach($docTypes as $docType)
                                    <option value="{{ $docType->id }}" @if($docType->id == $document->dts_doc_type_id) selected @endif>{{ $docType->description }}</option>
                                @endforeach
                                </select>
                        </div>
                        <div class="col-12 mb-20">
                            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">Description</label>
                            <textarea class="form-control" id="desc" rows="3" cols="50" name="description">{{ $document->description }}</textarea>
                            </div>
                        <div class="col-12 mb-20">
                            <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">Actions Needed</label>
                            <textarea class="form-control" id="desc" rows="3" cols="50" name="actions_needed">{{ $document->actions_needed }}</textarea>
                        </div>
                       
                        
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8"> 
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->


<!--End modals-->


@endsection

