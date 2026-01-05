@extends('layouts.dts-admin')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 id="pageheading" class="fw-semibold text-success mb-0 d-none d-md-block">    
      DTS - {{ $mySection }} 
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

  
      
      <div class="card ">
        <div class="card-header">
          <h5>My Station Statistics</h5>
    </div>
        
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

         
               
            @if(Auth::user()->section_id == NULL)
            <div class="alert alert-danger" role="alert">
             <h4 class="alert-heading">No Section Assigned!</h4>
             <p>You have not been assigned to any section. Please contact the system administrator.</p>
            </div>
            @else

<!--Start of the first row -->
<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header">
            <h6>Data Query Form</h6>
      </div>
         <div class="card-body">
  
          <form action="{{ route('dts.my-station.query-dates') }}" method="post">
            @csrf
              <div class="mb-3">
                <label for="begin_date" class="form-label">Begin Date</label>
                <input type="date" class="form-control" id="begin_date" name="begin_date" value="{{ session('beginDate') }}">
              </div>
              <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ session('endDate') }}">
              </div>

              <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('dts.my-station.clear-dates') }}" class="btn btn-secondary float-end">Clear</a>
              
          </form>
          
         </div>
  
    </div>
  
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header">
        <div class="fw-semibold text-success mb-0">
        @if(session('beginDate') && session('endDate'))
        <div>For DB Query Dates: {{ \Carbon\Carbon::parse(session('beginDate'))->format('F j, Y') }} to {{ \Carbon\Carbon::parse(session('endDate'))->format('F j, Y') }}</div>
        @endif
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          @if(session('beginDate') && session('endDate'))
          <tr>
            <td>Received Documents <small>(Accepted from dates specified.)</small></td>
            <td>{{isset($receivedCount) ? $receivedCount : 0 }}</td>
            <td>
              <a href="{{ route('dts.my-section-received') }}" class="btn btn-primary-300 btn-sm">View</a>
            </td>
          </tr>
          @endif
          @if(session('beginDate') && session('endDate'))
          <tr>
            <td>Forwarded Documents <small>(Forwarded from dates specified.)</small></td>
            <td>{{isset($forwardedCount ) ? $forwardedCount  : 0 }}</td>
            <td>
              <a href="{{ route('dts.my-section-forwarded') }}" class="btn btn-primary-300 btn-sm">View</a>
            </td>
          </tr>
          @endif
        @if(session('beginDate') && session('endDate'))
        <tr>
          <td>Documents Kept</td>
          <td>{{isset($documentsKeptCount) ? $documentsKeptCount : 0 }}</td>
          <td>
            <a href="{{ route('dts.my-section-kept') }}" class="btn btn-success-300 btn-sm">View</a>
          </td>
        </tr>
        @endif
        
        @if(session('beginDate') && session('endDate'))
        <tr>
          <td>Remaining Documents Pending/Deferred (No action has been initiated yet)
            <br>
            <small>Documents received are from the dates specified.</small>
          </td>
          <td>{{isset($pendingCount) ? $pendingCount : 0 }}</td>
          <td>
            <a href="{{ route('dts.mysection-pending-documents') }}" class="btn btn-danger-300 btn-sm">View</a>
          </td>
        </tr>
        @endif
          

        </table>

         



      </div>
    </div>
  
  </div>
  
  </div>
  
  
  <!--End of the first row -->
<!--Start of the second row -->
@if(session('beginDate') && session('endDate'))
<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header">
        <div class="text-lg text-lime-600">Documents Received by Doc Types</div>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          @if(isset($receivedDocsByType) && count($receivedDocsByType) > 0)
          <tr>
            <th>Description</th>
            <th> <div class="text-center">Total</div></th>
          </tr>
          @foreach($receivedDocsByType as $doc)
          <tr>
            <td>{{ $doc->description }}</td>
            <td><div class="text-center">{{ $doc->total }}</div></td>
          </tr>
          @endforeach
          @endif
        </table>

      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-header">
        <div class="text-lg text-lime-600">Documents Forwarded by Doc Types</div>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          @if(isset($forwardedDocsByType) && count($forwardedDocsByType) > 0)
          <tr>
            <th>Description</th>
            <th> <div class="text-center">Total</div></th>
          </tr>
          @foreach($forwardedDocsByType as $doc)
          <tr>
            <td>{{ $doc->description }}</td>
            <td><div class="text-center">{{ $doc->total }}</div></td>
          </tr>
          @endforeach
          @endif
        </table>

      </div>
    </div>


  </div>
 

</div>
@endif
<!--End of the second row -->




  <div class="row mt-50">

    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <div class="fw-semibold text-success mb-0">As of Today's Count Documents Received</div>
        </div>
        <div class="card-body">
          
          <table class="table table-bordered table-striped">
          <tr>
            <th>Last Year Count ({{ date('Y', strtotime('-1 year')) }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->last_year_count : 0 }}</td>
          </tr>
          <tr>
            <th>This Year Count ({{ date('Y') }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->ytd_count : 0 }}</td>
          </tr>
          <tr>
            <th>Today's Count ({{ date('F j, Y') }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->today_count : 0 }}</td>
          </tr>
          <tr>
            <th>Yesterday Count ({{ date('F j, Y', strtotime('-1 day')) }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->yesterday_count : 0 }}</td>
          </tr>
          <tr>
            <th>This Week Count ({{ date('F j, Y', strtotime('monday this week')) }} - {{ date('F j, Y', strtotime('sunday this week')) }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->week_count : 0 }}</td>
          </tr>
          <tr>
            <th>Last Week Count ({{ date('F j, Y', strtotime('monday last week')) }} - {{ date('F j, Y', strtotime('sunday last week')) }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->last_week_count : 0 }}</td>
          </tr>
          <tr>
            <th>This Month Count ({{ date('F') }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->month_count : 0 }}</td>
          </tr>
          <tr>
            <th>Last Month Count ({{ date('F', strtotime('first day of last month')) }})</th>
            <td class="text-center">{{ isset($myStationStats) ? $myStationStats->last_month_count : 0 }}</td>
          </tr>             
            
          </table>
          
          
        </div>
      </div>
     
    </div>
    <div class="col-sm-6">
      <div class="card">    
        <div class="card-header">
          <div class="fw-semibold text-primary mb-0"> As of Today's Count Documents Forwarded</div>
          </div>    
        <div class="card-body">
          
            <table class="table table-bordered table-striped">
            <tr>
              <th>Last Year Count ({{ date('Y', strtotime('-1 year')) }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->last_year_count : 0 }}</td>
            </tr>
            <tr>
              <th>This Year Count ({{ date('Y') }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->ytd_count : 0 }}</td>
            </tr>
            <tr>
              <th>Today's Count ({{ date('F j, Y') }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->today_count : 0 }}</td>
            </tr>
            <tr>
              <th>Yesterday Count ({{ date('F j, Y', strtotime('-1 day')) }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->yesterday_count : 0 }}</td>
            </tr>
            <tr>
              <th>This Week Count ({{ date('F j, Y', strtotime('monday this week')) }} - {{ date('F j, Y', strtotime('sunday this week')) }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->week_count : 0 }}</td>
            </tr>
            <tr>
              <th>Last Week Count ({{ date('F j, Y', strtotime('monday last week')) }} - {{ date('F j, Y', strtotime('sunday last week')) }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->last_week_count : 0 }}</td>
            </tr>
            <tr>
              <th>This Month Count ({{ date('F') }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->month_count : 0 }}</td>
            </tr>
            <tr>
              <th>Last Month Count ({{ date('F', strtotime('first day of last month')) }})</th>
              <td class="text-center">{{ isset($myStationForwardedStats) ? $myStationForwardedStats->last_month_count : 0 }}</td>
            </tr>
          </table>            
        </div>
        </div>
    </div>
  </div><!--end row -->


   </div><!--end row -->
<!--Start of the second row -->



<!--End of the second row -->
  @endif


      </div>


@endsection
@section('scripts')


@endsection