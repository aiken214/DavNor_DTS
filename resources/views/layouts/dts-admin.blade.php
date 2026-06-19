<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'DepEd DTS') }}</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/newdts-favicon.png') }}" sizes="16x16">
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">
  <!-- Data Table css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/editor-katex.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.atom-one-dark.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.quill.snow.css') }}">
  <!-- Date picker css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}">
  <!-- Calendar css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/full-calendar.css') }}">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
  <!-- Popup css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/magnific-popup.css') }}">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/slick.css') }}">
  <!-- main css -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/mydatatable.css') }}">
  <!-- Modern UI Theme -->
  <link rel="stylesheet" href="{{ asset('assets/css/modern-theme.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 
  
 
  <style>
  
  .arrow-down {
    /* margin: auto; */
    margin-left: 7rem;
    width: 0; 
    height: 0; 
    border-left: 20px solid transparent;
    border-right: 20px solid transparent;
    
    border-top: 20px solid rgb(14, 109, 69);
  }

  .centering{
    margin: auto !important;
  }
    .btn-xs{
      font-size: 0.7rem !important;
      padding: 2px 3px !important;
      border-radius: 2px;

    }

  /* Custom CSS to make the select options smaller and vertically centered */
  .custom-select-small {
      font-size: 0.875rem; /* Adjust the font size */
      padding: 0.25rem 0.5rem; /* Adjust padding to control height */
      line-height: 1.5; /* Ensure the text remains vertically centered */
  }

  .custom-select-small option {
      font-size: 0.875rem; /* Match the font size of options */
      padding: 0.25rem 0.5rem; /* Optionally add padding for consistency */
      line-height: 1.5; /* Ensure options text is vertically centered */
  }

    
    .sidebar-menu li {
      position: relative;
  }
  .sidebar-menu li {
      position: relative;
  }

  .sidebar-menu li a {
      padding: 0.625rem 0.75rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      color: var(--text-secondary-light);
      transition: all 0.3s;
      border-radius: 8px;
      font-size: 0.875rem;
  }


  .sidebar-menu li a span {
      flex-grow: 1; /* Ensures that the text span takes up the remaining space */
      white-space: nowrap;
  }
  .sidebar-menu li .badge-container {
      display: flex;
      justify-content: flex-end; /* Aligns the badge to the right */
      flex-grow: 0; /* Prevents the badge container from growing */
      align-items: right;
      margin-left: auto; /* Pushes the container to the right */
  }

  .badge-container .badge-right {
      display: none;
      
  }

  .sidebar-menu li .badge-right {
      display: inline-block; /* Ensures badge only wraps its content */
      padding: 0.2em 0.4em;
      font-size: 0.75rem;
      font-weight: 600;
      line-height: 1;
      color: #fff;
      text-align: center;
      white-space: nowrap;
      border-radius: 0.25rem;
      background-color: #28a745; /* Default for green badge */
  }

  /* Color classes */
  .sidebar-menu li .badge-right.blue { background-color: #052242; }
  .sidebar-menu li .badge-right.green { background-color: #28a745; }
  .sidebar-menu li .badge-right.red { background-color: #dc3545; }
  .sidebar-menu li .badge-right.orange { background-color: #fd7e14; }
  .sidebar-menu li .badge-right.purple { background-color: #6f42c1; }
  .sidebar-menu li .badge-right.yellow { background-color: #ffc107; color: #04351e; }
  .sidebar-menu li .badge-right.aqua { background-color: #17a2b8; color: #d0eea8;}

  .small-font {
      font-size: 0.75rem !important; /* Sets the font size to 0.75rem */
    }
    #auth_user_button:focus, #auth_user_button:active {
      font-size: 0.75rem !important; 
      color: purple !important; /* Set the text color to purple */
      outline: none !important; /* Remove the default outline */
      background-color: transparent !important;
    }
  
    textarea .form-control-sm .textarea-sm{
    height:6rem !important;
    }



    .text-box-small{
      font-size: 0.85rem; 
      padding: 0.4rem 1.2rem !important;
      /* padding-left: 1.2rem !important; */
      border-radius: 0.25rem; 

    }
    textarea.text-box-small{
      font-size: 0.75rem; 
      padding: 0.54rem; 
      border-radius: 0.25rem; 

    }

    textarea.text-box-small::placeholder {
      font-size: 0.885rem !important;; /* Ensures the placeholder text also has a smaller font size */
    }

    #allsearch {
        position: relative;
        display: flex;
        align-items: center;
    }

    #searchInput {
        flex: 1;
        padding-right: 30px !important; /* Add some padding to the right to make space for the icon */
    }

    #searchIcon {
        position: absolute !important;
        right: 10px !important; /* Adjust the value as needed */
        cursor: pointer;
    }    

  </style>
  
  @yield('sytle')
</head>
  <body>
    
<aside class="sidebar">
 
   @include('partials.leftnav')

  </aside>

<main class="dashboard-main">
  <div class="navbar-header">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search" method="post" action="{{ route('dts.search') }}">
          @csrf
          <div id="allsearch">
          <input type="text" name="search" placeholder="Search" id="searchInput">
          <iconify-icon icon="ion:search-outline" id="searchIcon"></iconify-icon>
          </div>
        </form>
      </div>
    </div>
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
       

        <div class="dropdown">
          <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
          </button>
          <div class="dropdown-menu to-top dropdown-menu-lg p-0">
            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
              </div>
              <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
            </div>
       

            <div class="text-center py-12 px-16"> 
                <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Notification</a>
            </div>

          </div>
        </div><!-- Notification dropdown end -->
      
        <div class="dropdown">
          <button id="auth_user_button" class="d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
            <span class="small-font">{{ Auth::user()->name }} &nbsp;</span>
            <img src="{{ asset('assets/images/profile-avatar.png') }}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
          </button>
          <div class="dropdown-menu to-top dropdown-menu-sm" id="profile_dropdown">
            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ Auth::user()->name }}</h6>
                <span class="text-secondary-light fw-medium text-sm">Employee</span>
              </div>
              <button type="button" class="hover-text-danger">
                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon> 
              </button>
            </div>
            <ul class="to-top-list">
              
              <li>
                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('profile.edit') }}"> 
                <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon>  My Profile</a>
              </li>
             @can('manage-users')
              <li>
                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#"> 
                <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon>  Setting</a>
              </li>
              @endcan

              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                <button type="submit" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="javascript:void(0)"> 
                <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>  Log Out</button>
                </form>
              </li>
            </ul>
          </div>
        </div><!-- Profile dropdown end -->
      </div>
    </div>
  </div>
</div> 

  <div class="dashboard-main-body">

<!--contents -->
@yield('content')

<!--//Endcontents -->
  </div>


    <!-- Toast Notifications for form submission -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
      <div class="toast-container position-fixed top-0 end-0 p-3">
        @if(session('success'))
        <div class="toast align-items-center bg-success-subtle border-0 text-success" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
          <div class="d-flex">
            <div class="toast-body">
              {{ session('success') }}
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" style="padding-right: 10px !important;"></button>         
          </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
          <div class="d-flex">
            <div class="toast-body">
              {{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
        @endif
      </div>
    </div>
</div>
 <!-- End Toast Notifications for form submission -->




  <footer class="d-footer">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <p class="mb-0">
        @if(isset($systemSetting) && $systemSetting !=NULL)
        {{ $systemSetting->custom_system_name }}
        @else
        © {{ date('Y') }}   Document Tracking System <small>| Developed by : Stephen R. Pascual</small>
        @endif
      </p>
    </div>
    <div class="col-auto">
      <p class="mb-0">DTS <span class="text-success-400">ver 3.4 </span></p>
    </div>
  </div>
</footer>
</main>
  
  <!-- jQuery library js -->
  <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
  <!-- Bootstrap js -->
  <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
  <!-- Apex Chart js -->
  <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
  <!-- Data Table js -->
  <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
  <!-- Iconify Font js -->
  <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
  <!-- jQuery UI js -->
  <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
  <!-- Vector Map js -->
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
  <!-- Popup js -->
  <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
  <!-- Slick Slider js -->
  <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
  <!-- main js -->
  <script src="{{ asset('assets/js/app.js') }}"></script>
   
  <script>
    function submitSectionForm(sectionId) {
        document.getElementById('station-id').value = sectionId;
        document.getElementById('section-form').submit();
    }
</script>
<script>
  $(document).ready(function() {
      $.ajax({
          url: '{{ route("dts.section-stat") }}',
          method: 'GET',
          success: function(data) {
              // Update Guest Docs count
              if (data.guestdoc_count && data.guestdoc_count != 0) {
                  $('#guest-doc-badge').text(data.guestdoc_count).css('display', 'inline-block');
              } else {
                  $('#guest-doc-badge').css('display', 'none'); // Ensure badge is hidden if count is 0
              }
  
              // Update Incoming Route count
              if (data.incoming_count && data.incoming_count != 0) {
                  $('#incoming-doc-badge').text(data.incoming_count).css('display', 'inline-block');
              } else {
                  $('#incoming-doc-badge').css('display', 'none'); // Ensure badge is hidden if count is 0
              }
  
              // Update Received Docs count
              if (data.received_count && data.received_count != 0) {
                  $('#received-doc-badge').text(data.received_count).css('display', 'inline-block');
              } else {
                  $('#received-doc-badge').css('display', 'none'); // Ensure badge is hidden if count is 0
              }
  
              // Update Forwarded Docs count
              if (data.forwarded_count && data.forwarded_count != 0) {
                  $('#forwarded-doc-badge').text(data.forwarded_count).css('display', 'inline-block');
              } else {
                  $('#forwarded-doc-badge').css('display', 'none'); // Ensure badge is hidden if count is 0
              }
  
              // Update Deferred Docs count
              if (data.deferred_count && data.deferred_count != 0) {
                  $('#deferred-doc-badge').text(data.deferred_count).css('display', 'inline-block');
              } else {
                  $('#deferred-doc-badge').css('display', 'none'); // Ensure badge is hidden if count is 0
              }
          },
          error: function(xhr, status, error) {
              console.error('Error fetching section stats:', error);
          }
      });
  });
  </script>
 
<script>    
  $('.remove-button').on('click', function() {
      $(this).closest('.alert').addClass('d-none')
  }); 
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      var toastElList = [].slice.call(document.querySelectorAll('.toast'))
      var toastList = toastElList.map(function (toastEl) {
          return new bootstrap.Toast(toastEl)
      })
      toastList.forEach(toast => toast.show())
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      var searchInput = document.getElementById('searchInput');
      var searchIcon = document.getElementById('searchIcon');

      // Submit form when Enter key is pressed
      searchInput.addEventListener('keypress', function(event) {
          if (event.key === 'Enter') {
              event.preventDefault(); // Prevent the default form submission
              this.form.submit(); // Submit the form
          }
      });

      // Set focus on the search input when the search icon is clicked
    searchIcon.addEventListener('click', function() {
              searchInput.focus(); // Set focus on the search input
           //   searchInput.form.submit(); // Submit the form
          });
  });
</script>
  
@yield('scripts')
</body>
</html>