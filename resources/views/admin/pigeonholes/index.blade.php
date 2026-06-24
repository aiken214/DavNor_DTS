@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Pigeonhole Management</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li class="fw-medium">
            @if(isset($mySection) && $mySection != NULL)
            <span class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 not-active px-18 py-11">
                {{ $mySection }}
            </span>
            @endif
        </li>
    </ul>
</div>

<div class="card basic-data-table">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h5 class="card-title mb-0">Pigeonholes</h5>
            </div>
            <div class="col-sm-6 text-end">
                @can('dts_settings_edit')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPigeonholeModal">Add Pigeonhole</button>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-3 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
              <div class="d-flex align-items-center gap-2">
                  <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                  {{ session('success') }}
              </div>
              <button class="remove-button text-success-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
          </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-3 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
              <div class="d-flex align-items-center gap-2">
                  <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
                  {{ session('error') }}
              </div>
              <button class="remove-button text-danger-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
          </div>
        @endif

        <table id="pigeonholesTable" class="table table-striped">
            <thead>
                <tr>
                    <th style="display: none;">ID</th>
                    <th>Name</th>
                    <th>Assigned Section</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pigeonholes as $pigeonhole)
                    <tr>
                        <td style="display: none;">{{ $pigeonhole->id }}</td>
                        <td><strong>{{ $pigeonhole->name }}</strong></td>
                        <td>{{ $pigeonhole->section->name ?? 'N/A' }}</td>
                        <td>{{ $pigeonhole->description ?? '-' }}</td>
                        <td>
                            @if($pigeonhole->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @can('dts_settings_edit')
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editPigeonholeModal"
                                data-pigeonhole-id="{{ $pigeonhole->id }}"
                                data-pigeonhole-name="{{ $pigeonhole->name }}"
                                data-pigeonhole-section="{{ $pigeonhole->section_id }}"
                                data-pigeonhole-description="{{ $pigeonhole->description }}"
                                data-pigeonhole-active="{{ $pigeonhole->is_active }}">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.pigeonholes.destroy') }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pigeonhole?')">
                                @csrf
                                <input type="hidden" name="pigeonhole_id" value="{{ $pigeonhole->id }}">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@can('dts_settings_edit')
<div class="modal fade" id="createPigeonholeModal" tabindex="-1" aria-labelledby="createPigeonholeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="createPigeonholeModalLabel">Create New Pigeonhole</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.pigeonholes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Pigeonhole Name</label>
                        <input type="text" class="form-control" id="create_name" name="name" required placeholder="e.g. Slot 1, Box A">
                    </div>
                    <div class="mb-3">
                        <label for="create_section_id" class="form-label">Assigned Section</label>
                        <select class="form-control" id="create_section_id" name="section_id" required>
                            <option value="">-- Select Section --</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Documents sent to this pigeonhole will be forwarded to this section.</small>
                    </div>
                    <div class="mb-3">
                        <label for="create_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="create_description" name="description" placeholder="Optional description">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Pigeonhole</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPigeonholeModal" tabindex="-1" aria-labelledby="editPigeonholeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="editPigeonholeModalLabel">Edit Pigeonhole</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.pigeonholes.update') }}">
                    @csrf
                    <input type="hidden" name="pigeonhole_id" id="edit_pigeonhole_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Pigeonhole Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_id" class="form-label">Assigned Section</label>
                        <select class="form-control" id="edit_section_id" name="section_id" required>
                            <option value="">-- Select Section --</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description">
                    </div>
                    <div class="mb-3">
                        <label for="edit_is_active" class="form-label">Status</label>
                        <select class="form-control" id="edit_is_active" name="is_active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Pigeonhole</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#pigeonholesTable').DataTable({
        responsive: true,
        "pageLength": 25,
        order: [[1, 'asc']]
    });
});

$('#editPigeonholeModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    modal.find('#edit_pigeonhole_id').val(button.data('pigeonhole-id'));
    modal.find('#edit_name').val(button.data('pigeonhole-name'));
    modal.find('#edit_section_id').val(button.data('pigeonhole-section'));
    modal.find('#edit_description').val(button.data('pigeonhole-description'));
    modal.find('#edit_is_active').val(button.data('pigeonhole-active'));
    modal.find('.modal-title').text('Edit Pigeonhole: ' + button.data('pigeonhole-name'));
});
</script>
@endsection
