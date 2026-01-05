@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Section Management</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
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
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
            <h5 class="card-title mb-0">Sections</h5>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('admin.sections-create') }}" class="btn btn-primary float-end">Add Section</a>
            </div>
        </div>
       
    </div>
    <div class="card-body">
        <table id="sections-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>    
                    {{-- <th>Main Office</th>                --}}
                    <th>RecMgnt</th>                   
                   <th>Guest DropDown</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $section)
                    <tr>
                        <td>{{ $section->name }} <small> ( ID:{{ $section->id }} )</small></td>
                        {{-- <td>{{ $section->category->name }}</td> --}}
                        <td>{{ $section->is_record_management ? 'Yes' : 'No' }}</td>                        
                        <td>{{ $section->is_public_dropdown ? 'Yes' : 'No' }}</td>
                       
                        <td>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" 
                            data-bs-target="#editSectionModal" 
                            data-section-id="{{ $section->id }}" 
                            data-section-name="{{ $section->name }}"
                            data-is-public-dropdown="{{ $section->is_public_dropdown }}"
                            data-is-record-management="{{ $section->is_record_management }}" 
                            data-has-createforward-docform="{{ $section->has_createforward_docform }}"
                            data-has-create-docform="{{ $section->has_create_docform }}">
                            Edit</button>

                            <form method="POST" action="{{ route('admin.sections.destroy') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="section_id" value="{{ $section->id }}" hidden>
                                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete {{ $section->name }} section?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="editSectionModalLabel">Edit Section</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.sections.update') }}" id="editSectionForm">
                    @csrf
                    {{-- @method('PATCH') --}}

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" autocomplete="section-name" required>
                        <input type="hidden" name="section_id" id="section_id">
                    </div>

                    {{-- <div class="mb-3">
                        <label for="category_id" class="form-label">Section Category</label>
                        <select class="form-control" id="category_id" name="category_id" autocomplete="section-category">
                            @foreach($sectionCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        
                    </div> --}}

                    <div class="mb-3">
                        <label for="is_record_management" class="form-label">Is Record Management</label>
                        <select class="form-control" id="is_record_management" name="is_record_management" autocomplete="section-record-management">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                     <div class="mb-3">
                        <label for="isPublicDropDown" class="form-label">Guest DropDown</label>
                        <select class="form-control" id="isPublicDropDown" name="is_public_dropdown" autocomplete="is-public-dropdown">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                  

                    <button type="submit" class="btn btn-primary">Update Section</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('#sections-table').DataTable({
            responsive: true,
            autoWidth: false, // Prevent auto-calculation of width by DataTables
            "pageLength": 25,
            
        });
    });
</script>
<script>
    $('#editSectionModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var sectionId = button.data('section-id') 
  var sectionName = button.data('section-name')
  var isRecordManagement = button.data('is-record-management')
  var isPublicDropdown = button.data('is-public-dropdown')
  
  var modal = $(this)
  modal.find('.modal-title').text('Edit Section: ' + sectionName)
  modal.find('#name').val(sectionName)
  modal.find('#section_id').val(sectionId)
  modal.find('#is_record_management').val(isRecordManagement)
  modal.find('#isPublicDropDown').val(isPublicDropdown)

})
</script>


@endsection
