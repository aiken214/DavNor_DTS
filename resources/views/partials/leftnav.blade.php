@php
    $isRecordSection = Auth::check() ? \App\Models\DtsSection::where('id', Auth::user()->section_id)->value('is_record_management') : false;
@endphp
<button type="button" class="sidebar-close-btn">
  <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
</button>
<div>
  <a href="{{ route('dashboard') }}" class="sidebar-logo">
    <img src="{{ asset('assets/images/logo-dts.png') }}" alt="site logo" class="light-logo">
    <img src="{{ asset('assets/images/logo-dts-light.png') }}" alt="site logo" class="dark-logo">
    <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
  </a>
</div>
<div class="sidebar-menu-area">
<ul class="sidebar-menu" id="sidebar-menu">
  <li>
    <a href="{{ route('dashboard') }}">
      <iconify-icon icon="fluent:home-20-regular" class="menu-icon"></iconify-icon>
      <span>Dashboard</span>
    </a>
  </li>
  
  <li class="sidebar-menu-group-title">Doc Tracking</li>
@can('dts_access')
@if($isRecordSection)
<li class="li-forbadge">
    <a href="{{ route('dts.guest-doc') }}">
        <iconify-icon icon="fluent:document-person-20-regular" class="menu-icon"></iconify-icon>
        <span> Guest Docs </span>
        <span class="badge-container">
            <span id="guest-doc-badge" class="badge-right purple"></span>
        </span>
    </a>
</li>
@endif

<li class="li-forbadge">
    <a href="{{ route('dts.incoming-docs.index') }}">
        <iconify-icon icon="fluent:document-search-20-regular" class="menu-icon"></iconify-icon>
        <span>Incoming-route</span> 
        <span class="badge-container">
            <span id="incoming-doc-badge" class="badge-right green"></span>
        </span>
    </a>
</li>

<li class="li-forbadge">
    <a href="{{ route('dts.received-docs.index') }}">
        <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
        <span> Pending </span>
        <span class="badge-container">
            <span id="received-doc-badge" class="badge-right orange"></span>
        </span>
    </a>
</li>

<li class="li-forbadge">
    <a href="{{ route('dts.forwarded-docs.index') }}">
        <iconify-icon icon="fluent:document-arrow-right-20-filled" class="menu-icon"></iconify-icon>
        <span>Forwarded</span> 
        <span class="badge-container">
            <span id="forwarded-doc-badge" class="badge-right yellow"></span>
        </span>
    </a>
</li>
  
<li class="li-forbadge">
  <a href="{{ route('dts.deferred-docs.index') }}">
      <iconify-icon icon="material-symbols:map-outline" class="menu-icon"></iconify-icon>
      <span>Deferred</span> 
      <span class="badge-container">
          <span id="deferred-doc-badge" class="badge-right red" style="display: none;"></span>
      </span>
  </a>
</li>

  <li  class="li-forbadge">
    <a href="{{ route('dts.documents.create-forward') }}">
      <iconify-icon icon="fluent:document-one-page-24-regular" class="menu-icon"></iconify-icon>
      <span>Submit New</span> 
    </a>
  </li>
  @endcan
  @can('dts_batch_release_access')
  <li  class="li-forbadge">
    <a href="{{ route('dts.batch-releases.index') }}">
      <iconify-icon icon="et:documents" class="menu-icon"></iconify-icon>
      <span>Batch Releasing</span> 
    </a>
  </li>
@endcan
  @if($isRecordSection)
  <li class="li-forbadge">
    <a href="{{ route('dts.pigeonhole-docs.index') }}">
      <iconify-icon icon="mdi:mailbox-open-outline" class="menu-icon"></iconify-icon>
      <span>Pigeonholes</span>
    </a>
  </li>
  @endif

  <li class="dropdown {{ request()->routeIs('dts.my-documents', 'dts.routed-for-me', 'dts.accepted-by-me', 'dts.stats-per-section') ? 'open' : '' }}">
    <a href="javascript:void(0)">
      <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
      <span>My DTS</span>
    </a>
    <ul class="sidebar-submenu" {!! request()->routeIs('dts.my-documents', 'dts.routed-for-me', 'dts.accepted-by-me', 'dts.stats-per-section') ? 'style="display:block;"' : '' !!}>
      <li>
        <a href="{{ route('dts.my-documents') }}" >
          <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> My Documents
        </a>
      </li>
      @can('dts_access')
      <li>
        <a href="{{ route('dts.routed-for-me') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Routed for Me</a>
      </li>
      <li>
        <a href="{{ route('dts.accepted-by-me') }}"><i class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Accepted by Me</a>
      </li>
      @endcan
      @can('dts_reports_mngt')
      <li>
        <a href="{{ route('dts.stats-per-section') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Stats Per Sections</a>
      </li>
      @endcan
      
    </ul>
  </li>
 

  @can('dts_access')
  <li class="dropdown">
    <a href="javascript:void(0)">
      <iconify-icon icon="stash:section-divider" class="menu-icon"></iconify-icon>
      <span>My Section</span> 
    </a>
    <ul class="sidebar-submenu">
      
      <li>
        <a href="{{ route('dts.my-station') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> DTS Statisiics</a>
      </li>
      <li>
        <a href="{{ route('dts.kept-documents') }}"><i class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Documents Kept</a>
      </li>
      <li>
        <a href="{{ route('dts.incoming-parked') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Parked Incoming Documents</a>
      </li>
      <li>
        <a href="{{ route('dts.pending-parked') }}"><i class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Parked Pending Documents</a>
      </li>
    <li>
      <a href="{{ route('dts.deffered-parked') }}"><i class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Parked Deferred Documents</a>
    </li>
      
    </ul>
  </li>
  @endcan
  @can('user_access')
  <li class="sidebar-menu-group-title">Application</li>
  <li class="dropdown">
    <a href="javascript:void(0)">
      <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
      <span>Users</span> 
    </a>
    <ul class="sidebar-submenu">
     
      <li>
        <a href="{{ route('admin.users.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List</a>
      </li> 
    

      @can('role_access')
      <li>
        <a href="{{ route('admin.roles.index') }}"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> User Roles</a>
      </li>
      @endcan
      @can('permission_access')
      <li>
        <a href="{{ route('admin.permissions.index') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Permissions</a>
      </li>
      @endcan

    </ul>
  </li>
  @endcan
 @can('dts_settings_access')
  <li class="dropdown">
    <a href="javascript:void(0)">
      <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
      <span>System Settings</span> 
    </a>
    <ul class="sidebar-submenu">
      <li>
        <a href="{{ route('admin.sections.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Sections</a>
      </li>
      <li>
        <a href="{{ route('dts.doc-types.index') }}"><i class="ri-circle-fill circle-icon text-success-600 w-auto"></i>Doc Types</a>
      </li>

      <li>
        <a href="{{ route('admin.pigeonholes.index') }}"><i class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Pigeonholes</a>
      </li>
      <li>
        <a href="{{ route('dts.system-settings.index') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> DTS Settings</a>
      </li>
      <li>
        <a href="{{ route('admin.section-statistics.index') }}"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Statistics</a>
      </li>

    </ul>
  </li>
  @endcan

  <li class="sidebar-menu-group-title">Help</li>
  <li>
    <a href="{{ route('user-manual') }}">
      <iconify-icon icon="solar:book-linear" class="menu-icon"></iconify-icon>
      <span>User's Manual</span>
    </a>
  </li>

</ul>
</div>