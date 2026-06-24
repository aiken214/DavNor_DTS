@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Permission Management</h6>
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
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h5 class="card-title mb-0">Permissions</h5>
            </div>
            <div class="col-sm-6 text-end">
                @can('permission_create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPermissionModal">Add Permission</button>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="permissionsTable" class="table table-striped">
            <thead>
                <tr>
                    <th style="display: none;">ID</th>
                    <th>Permission Title</th>
                    <th>Remarks</th>
                    <th>Assigned To Roles</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td><span class="badge bg-success">{{ $permission->title }}</span></td>
                        <td>{{ $permission->remarks ?? '-' }}</td>
                        <td>
                            @foreach($permission->roles as $role)
                                <span class="badge bg-primary">{{ $role->title }}</span>
                            @endforeach
                        </td>
                        <td>
                            @can('permission_edit')
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editPermissionModal"
                                data-permission-id="{{ $permission->id }}"
                                data-permission-title="{{ $permission->title }}"
                                data-permission-remarks="{{ $permission->remarks }}">
                                Edit
                            </button>
                            @endcan
                            @can('permission_delete')
                            <form method="POST" action="{{ route('admin.permissions.destroy') }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                @csrf
                                <input type="hidden" name="permission_id" value="{{ $permission->id }}">
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

@can('permission_create')
<div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="createPermissionModalLabel">Create New Permission</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.permissions.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="create_title" class="form-label">Permission Title</label>
                        <input type="text" class="form-control" id="create_title" name="title" required placeholder="e.g. dts_feature_access">
                        <small class="text-muted">Use snake_case format (e.g. dts_feature_access)</small>
                    </div>
                    <div class="mb-3">
                        <label for="create_remarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" id="create_remarks" name="remarks" placeholder="Optional description">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Permission</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

@can('permission_edit')
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="editPermissionModalLabel">Edit Permission</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.permissions.update') }}">
                    @csrf
                    <input type="hidden" name="permission_id" id="edit_permission_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Permission Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                        <small class="text-muted">Use snake_case format (e.g. dts_feature_access)</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_remarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" id="edit_remarks" name="remarks">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Permission</button>
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
    $('#permissionsTable').DataTable({
        responsive: true,
        "pageLength": 25,
        order: [[1, 'asc']]
    });
});

$('#editPermissionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    modal.find('#edit_permission_id').val(button.data('permission-id'));
    modal.find('#edit_title').val(button.data('permission-title'));
    modal.find('#edit_remarks').val(button.data('permission-remarks'));
    modal.find('.modal-title').text('Edit Permission: ' + button.data('permission-title'));
});
</script>
@endsection
