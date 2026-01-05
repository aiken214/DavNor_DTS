@extends('layouts.dts-admin')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">User Management</h6>
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

<div class="card basic-data-table">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-6">
                <h5 class="card-title mb-0">Users</h5>
            </div>
            <div class="col-sm-6">
                <div class="btn-toolbar justify-content-end">
                    @can('user_create')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add User</a>
                    @endcan
                </div>
            </div>
        </div>
        
    </div>
    <div class="card-body">
          <table id="dataTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>                    
                    <th>Email</th>
                    <th>Role/s</th>
                    <th>Default Section</th>
                    <th>All Assigned Sections</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td> @foreach($user->roles as $key => $role)
                            <span class="badge bg-primary">{{ $role->title }}</span>
                        @endforeach</td>
                        <td>{{ $user->section->name ?? 'N/A' }}</td>
                        <td>
                            @foreach($user->sections as $key => $section)
                                <span class="badge bg-secondary">{{ $section->name }}</span>
                            @endforeach
                        </td>

                        <td>
                            {{-- @can('user_show')
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary btn-sm">View</a>
                            @endcan --}}
                            @can('user_edit')
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            @endcan
                            @can('user_delete')
                            @if($user->id != 1)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @endif
                            @endcan
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
        $('#users-table').DataTable();
    });
</script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            "pageLength": 25,
            order: [[0, 'asc']] 
            
        });
    });

   
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
