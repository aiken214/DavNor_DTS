<button type="button" class="sidebar-close-btn">
  <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
</button>
<div>
  <a href="index.html" class="sidebar-logo">
    <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
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
  <li class="li-forbadge">
    <a href="{{ route('dts.guest-doc') }}">
      <iconify-icon icon="fluent:document-person-20-regular" class="menu-icon"></iconify-icon>
      <span > Guest Docs </span>
      @if(isset($guestdocCount) && $guestdocCount!=0)
      <span class="badge-container">
          <span class="badge-right purple">{{ $guestdocCount }}</span>
      </span>
  @endif
    </a>
  </li>
  <li  class="li-forbadge">
    <a href="{{ route('dts.incoming-docs') }}">
      <iconify-icon icon="fluent:document-search-20-regular" class="menu-icon"></iconify-icon>
      <span>Incoming-route</span> 
      @if(isset($incomingCount) && $incomingCount!=0)
            <span class="badge-container">
                <span class="badge-right green">{{ $incomingCount }}</span>
            </span>
        @endif
    </a>
  </li>
  <li  class="li-forbadge">
    <a href="{{ route('dts.received-docs') }}">
      <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
      <span > Received </span>
      @if(isset($receivedCount) && $receivedCount!=0)
            <span class="badge-container">
                <span class="badge-right orange">{{ $receivedCount }}</span>
            </span>
        @endif
    </a>
  </li>
  <li  class="li-forbadge">
    <a href="{{ route('dts.forwarded-docs') }}">
      <iconify-icon icon="fluent:document-arrow-right-20-filled" class="menu-icon"></iconify-icon>     
      <span>Forwarded</span> 
      @if(isset($forwardedCount) && $forwardedCount!=0)
            <span class="badge-container">
                <span class="badge-right yellow">{{ $forwardedCount }}</span>
            </span>
        @endif
    </a>
  </li>
  <li  class="li-forbadge">
    <a href="#Wdef">
      <iconify-icon icon="material-symbols:map-outline" class="menu-icon"></iconify-icon>
      <span>Deferred</span> 
      
      @if(isset($deferredCount) && $deferredCount!=0)
      <span class="badge-container">
          <span class="badge-right red">{{ $deferredCount }}</span>
      </span>
  @endif
    </a>
  </li>
  <li  class="li-forbadge">
    <a href="{{ route('dts.documents.create-forward') }}">
      <iconify-icon icon="fluent:document-one-page-24-regular" class="menu-icon"></iconify-icon>
      <span>Submit New</span> 
    </a>
  </li>
  <li class="dropdown">
    <a href="javascript:void(0)">
      <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
      <span>Dts Reports</span> 
    </a>
    <ul class="sidebar-submenu">
      <li>
        <a href="#m"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Route Stats</a>
      </li>
      <li>
        <a href="#SP"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Stats Per Sections</a>
      </li>
      <li>
        <a href="#pd"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Printables</a>
      </li>
      
    </ul>
  </li>

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
     
      <li>
        <a href="hgf"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add User</a>
      </li>
      
    </ul>
  </li>
 
 
  <li class="dropdown">
    <a href="javascript:void(0)">
      <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
      <span>Settings</span> 
    </a>
    <ul class="sidebar-submenu">
      <li>
        <a href="{{ route('admin.sections.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Sections</a>
      </li>
      <li>
        <a href="{{ route('dts.doc-types.index') }}"><i class="ri-circle-fill circle-icon text-success-600 w-auto"></i>Doc Types</a>
      </li>
      <li>
        <a href="{{ route('dts.system-settings.index') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> DTS Settings</a>
      </li>
     
    </ul>
  </li>
</ul>
</div>