@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">DTS-Documents</h6>
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
        <h5 class="card-title mb-0">My Documents</h5>
    </div>
    <div class="card-body">
        {{-- <table id="dataTable" class="table table-striped"> --}}
            <table class="table table-striped">
            <thead>
                <tr>
                    {{-- <th>ID</th> --}}
                    <th style="text-align: left;">Tracking</th>
                    <th>Doc Type</th>
                    <th>Description</th>                  
                    <th>Last Section Routed</th>
                    <th></th>
                    
                   
                </tr>
            </thead>
            <tbody>
                @if($mydocuments->count() > 0)
                    @foreach($mydocuments as $document)
                        <tr>
                            {{-- <td>{{ $document->id }}</td> --}}
                            <td style="text-align: left;">
                                <a href="{{ route('dts.documents.show', $document->id) }}">
                                {{ $document->tracking_code }}
                                </a>
                            </td>
                            <td>{{ $document->doctype_description }}</td>
                            <td>
                                <a href="{{ route('dts.documents.show', $document->id) }}">
                                {{ $document->description }}
                                </a>
                            </td>
                            <td>
                                {{ $document->route_to_section_name }}
                            </td>
                            <td>
                                <a href="{{ route('dts.my-documents-view', $document->id) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                            
                            
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No documents found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <hr class="my-3">
        <div class="d-flex justify-content-center">
            {{ $mydocuments->links() }}
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
        $('#mydataTable').DataTable({
            responsive: true,
            autoWidth: false, // Prevent auto-calculation of width by DataTables
            columnDefs: [
                { width: '13ch', targets: 0 },
                { width: '35%', targets: 1 } // Set the width of the description column
            ],
            "pageLength": 25,
            order: [[0, 'desc']] 
            
        });
    });

   
</script>
<script>
    function submitSectionForm(section_id) {
        $('#station-id').val(section_id);
        $('#section-form').submit();
    }
</script> 





@endsection
