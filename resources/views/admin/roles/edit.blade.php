@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Edit Role</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">
            <a href="{{ route('admin.roles.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                Roles
            </a>
        </li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h5 class="card-title mb-0">Edit Role: {{ $role->title }}</h5>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-warning btn-sm">Back to Roles</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Role Name</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $role->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Assign Permissions</label>
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Deselect All</button>
                </div>

                @foreach($groupedPermissions as $group => $permissions)
                <div class="card mb-3 border">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $group }}</h6>
                        <div class="form-check">
                            <input class="form-check-input group-toggle" type="checkbox" data-group="{{ Str::slug($group) }}" id="group-{{ Str::slug($group) }}">
                            <label class="form-check-label small" for="group-{{ Str::slug($group) }}">Toggle All</label>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox group-{{ Str::slug($group) }}"
                                           type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission->id }}"
                                           id="perm-{{ $permission->id }}"
                                           {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                                        {{ $permission->title }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Update Role</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#selectAll').click(function() {
        $('.permission-checkbox').prop('checked', true);
        $('.group-toggle').prop('checked', true);
    });

    $('#deselectAll').click(function() {
        $('.permission-checkbox').prop('checked', false);
        $('.group-toggle').prop('checked', false);
    });

    $('.group-toggle').each(function() {
        var group = $(this).data('group');
        var total = $('.group-' + group).length;
        var checked = $('.group-' + group + ':checked').length;
        $(this).prop('checked', total === checked && total > 0);
    });

    $('.group-toggle').change(function() {
        var group = $(this).data('group');
        $('.group-' + group).prop('checked', $(this).is(':checked'));
    });

    $('.permission-checkbox').change(function() {
        var classes = $(this).attr('class').split(' ');
        classes.forEach(function(cls) {
            if (cls.startsWith('group-')) {
                var group = cls.replace('group-', '');
                var total = $('.' + cls).length;
                var checked = $('.' + cls + ':checked').length;
                $('[data-group="' + group + '"]').prop('checked', total === checked);
            }
        });
    });
});
</script>
@endsection
