@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Document Search</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                My DTS Section
            </a>
        </li>
       
        <li class="fw-medium">
          @if(isset($myAllSections) && count($myAllSections) > 0)
            <div class="btn-group dropstart">
                <button class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                     @if(isset($mySection) && $mySection != NULL)
                    {{ $mySection }}
                    @elseif(isset($myAllSections) && count($myAllSections) > 0)                        
                        Select Section
                    @endif
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
  
  
    <div class="card-header">
        <h5 class="card-title mb-0">Search Results</h5>
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
           
            <div> &nbsp;</div>
         @endif


        {{-- <table id="dataTable" class="table table-striped"> --}}
            <table  id="searchdataTable" class="table table-striped">
            <thead>
                <tr>
                    {{-- <th style="text-align: left;">ID</th> --}}
                    <th style="text-align: left;">Tracking</th>
                    <th>Particulars</th>
                    <th>From</th>                  
                  
                    <th></th>
                   
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                    <tr>
                        {{-- <td>{{ $doc->id }}</td> --}}
                        <td>{{ $doc->tracking_code }}</td>
                        <td>{{ $doc->description }}</td>
                        <td> 
                            @if ($doc->fromUser)
                            {{ $doc->fromUser->name;}}                        
                            @else                           
                           {{ $doc->guest_origin_name }}
                           <br><small>(Guest Acct)</small> 
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('dts.document-view', $doc->id) }}" class="btn rounded-pill btn-outline-lilac-600 radius-8 px-20 py-11" style="font-size:0.8rem;">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="my-3">
        <div class="d-flex justify-content-center">
            {{ $documents->links() }}
        </div>
    </div>
</div>



@endsection


@section('styles')
<style>
    .description-column {
        width: 20%;
    }

    /* Ensure the table fits 100% width of its container */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }
</style>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('#searchdataTable').DataTable({
            responsive: true,
            autoWidth: false, // Prevent auto-calculation of width by DataTables
            columnDefs: [
             //   { targets: 0, visible: false }, // Hide the first column
                { width: '13ch', targets: 0 },
                { width: '45%', targets: 1 } // Set the width of the description column
            ],
            "pageLength": 25,
            order: [[0, 'desc']] 
        });
    });

   
</script>

@endsection
