@extends('layouts.dts-admin')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Document Types</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">Document Types</li>
    </ul>
</div>

<div class="card basic-data-table">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-6"> <h5 class="card-title mb-0">Document Types</h5></div>
            <div class="col-sm-6">
                <div class="btn-toolbar justify-content-end">
                    <a href="{{ route('dts.doc-types.create') }}" class="btn btn-primary">Add Document Type</a>
                </div>
            </div>
        </div>
       
    </div>
    <div class="card-body">
        <table id="docTypesTable" class="table table-striped">
            <thead>
                <tr>
                   
                    <th>Description</th>
                    <th>Is Guest Form Dropdown</th>
                    
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($docTypes as $docType)
                <tr>
                    
                    <td>{{ $docType->description }} ({{ $docType->id }})</td>
                    <td>{{ $docType->for_guest ? 'Yes' : 'No' }}</td>
                  
                    <td>
                        <a href="{{ route('dts.doc-types.edit', $docType->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('dts.doc-types.destroy', $docType->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this document type?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
       
        $('#docTypesTable').DataTable({
            "ordering": false,
            "pageLength": 50
        });
    });
</script>
@endsection
