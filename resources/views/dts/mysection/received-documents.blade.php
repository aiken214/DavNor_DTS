@extends('layouts.dts-admin')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">DTS- My Section Received Document</h6>
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

<!--ednValidation display -->


    <div class="card-header">
      <div class="row">
        <div class="col-sm-6">
            <h5 class="card-title mb-0">{{ $tableTitle }}</h5>
        </div>
        <div class="col-sm-6 text-end">
            <a href="{{ route('dts.my-station') }}" class="btn btn-primary float-end">Back to My Section Page</a>
        </div>
      </div>
        
    </div>
    <div class="card-body">
        <table id="receivedDocsTable" class="table table-striped table-responsive w-100">
            <thead>
                <tr>
                    <th>Tracking</th>
                    <th class="description-column">Description</th>
                    <th>Date</th>
                    <th>From</th>
                    <th>Accepted /Route Purpose</th>

                    
                    
                </tr>
            </thead>
            <tbody>
                @foreach($receivedDocs as $document)
                <tr>
                    <td class="align-middle" style="min-width: 6rem;"> <a href="{{ route('dts.document-view', $document->document->id) }}">{{ $document->document->tracking_code }}</a></td>
                    <td class="align-middle">{{ $document->docType->description }} - {{ $document->document->description }}</td>
                   <td>{{ $document->date_accepted }}</td>
                    <td class="align-middle"> {{ $document->fromSection->name ?? 'N/A' }} <br>
                     <small> {{ $document->fromUser->name ?? 'N/A' }}  </small>
                    </td>
                    <td class="align-middle">@dateDateTime($document->date_accepted) <br>
                     <small>   {{ $document->route_purpose }} </small>

                    </td>
                  
                   
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="my-3">
        <div class="d-flex justify-content-center">
            {{ $receivedDocs->links() }}
        </div>
    </div>
</div>

{{-- End modals --}}

@endsection

@section('styles')
<style>
    .description-column {
        width: 40%;
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
      $('#receivedDocsTable').DataTable({
          responsive: true,
          autoWidth: false, // Prevent auto-calculation of width by DataTables
          columnDefs: [
              { width: '35%', targets: 1 }, // Set the width of the description column
              { visible: false, targets: 2 } // Hide the third column (index 2)
          ],
          order: [[2, 'desc']],  // Sort by the hidden third column (index 2) in descending order
          "pageLength": 25,
      });
  
  });
</script>
@endsection
