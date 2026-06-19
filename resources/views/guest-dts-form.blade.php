<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'DepEd DTS') }}</title>
  
  {{-- <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16"> --}}
  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
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
  <!-- Modern UI Theme -->
  <link rel="stylesheet" href="{{ asset('assets/css/modern-theme.css') }}">
</head>
<body>
@if (Auth::check())
    <script>
     window.location.href = "{{ url('/dashboard') }}";
    </script>
 @endif

<section class="auth bg-base d-flex flex-wrap">  
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{ asset('assets/images/dts-system-welcome.png') }}" alt="Auth Image">
        </div>
    </div>
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-800-px mx-auto w-100">
            <div class="mx-auto text-center mb-24">
                 <h6 class="mb-10">DTS Document Details</h6>
                 <p class="mb-32 text-secondary-light text-lg">If you don't have DTS Account. Please fill in the details below.</p>
            </div>
            <form action="{{ route('guest-document-store') }}" method="POST">
                @csrf
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="from" class="form-label mb-0 col-sm-3">Guest Name (From)</label>
                    <div class="col-sm-9">
                        <input type="text" id="from" class="form-control form-control-sm" name="guestname" required>
                        
                    </div>
                </div>

                <div class="row mb-24 gy-3 align-items-center">
                    <label for="officefrom" class="form-label mb-0 col-sm-3">Organization/Office From</label>
                    <div class="col-sm-9">
                        <input type="text" id="officefrom" class="form-control form-control-sm" name="organization">
                        
                    </div>
                </div>
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="doctype_id" class="form-label mb-0 col-sm-3">Document Type</label>
                    <div class="col-sm-9">
                        @if(isset($docTypes))
                        <select name="doctype_id" id="doctype_id" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select Document Type</option>
                            @foreach ($docTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->description }}</option>
                            @endforeach
                        </select>
                        @endif
                       
                    </div>
                </div>
              
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="description" class="form-label mb-0 col-sm-3">Description</label>
                    <div class="col-sm-9">
                          <textarea id="description" class="form-control text-box-small" name="doc_description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="actions_needed" class="form-label mb-0 col-sm-3">Actions Needed (Route Purpose)</label>
                    <div class="col-sm-9">
                        <input type="text" id="actions_needed" class="form-control form-control-sm" name="actions_needed">
                        
                    </div>
                </div>
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="to_section_id" class="form-label mb-0 col-sm-3">Route to Section</label>
                    <div class="col-sm-9">
                      
                        <select name="to_section_id" id="to_section_id" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                       
                    </div>
                </div>
                <div class="row mb-24 gy-3 align-items-center">
                    <label for="to_user_id" class="form-label mb-0 col-sm-3">Employee</label>
                    <div class="col-sm-9">
                        <select name="to_user_id" id="to_user_id" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select Staff</option>
                            <!-- Options will be dynamically populated here -->
                        </select>
                         <div class="alert alert-danger mt-2 d-none" id="user-fetch-error">
                            An error occurred while fetching users. Please try again.
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary-600">Submit</button>
                    </div>
                </div>
            </form>

            <div class="mt-32 text-center text-sm">
               
                <p>Back to    <a href="{{ route('login') }}" class="text-primary-600 fw-semibold">Sign-In Page</a></p>
            </div>

            {{-- <div>
                <p class="text-center text-sm text-secondary-light">© 2025 DTS. All Rights Reserved.</p>
            </div> --}}
        </div>
    </div>
</section>

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
  // Password Show/Hide Toggle
  function initializePasswordToggle(toggleSelector) {
    $(toggleSelector).on('click', function() {
      $(this).toggleClass("ri-eye-off-line");
      var input = $($(this).attr("data-toggle"));
      if (input.attr("type") === "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  }
  // Call the function
  initializePasswordToggle('.toggle-password');
</script>

<script>
    $(document).ready(function() {
        var baseUrl = "{{ url('/') }}"; // Get the base URL

        $('#to_section_id').change(function() {
            var sectionId = $(this).val();
            var userDropdown = $('#to_user_id');
            var errorAlert = $('#user-fetch-error');
            
            userDropdown.empty().append('<option value="">Select Staff</option>'); // Clear current options

            if (sectionId) {
                $.ajax({
                    url: baseUrl + '/user-by-section/' + sectionId,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        userDropdown.prop('disabled', true);
                        errorAlert.addClass('d-none');
                    },
                    success: function(data) {
                        $.each(data, function(index, user) {
                            userDropdown.append('<option value="' + user.id + '">' + user.name + '</option>');
                        });
                        userDropdown.prop('disabled', false);
                    },
                    error: function(error) {
                        console.error('Error fetching users:', error);
                        errorAlert.removeClass('d-none');
                        userDropdown.prop('disabled', false);
                    }
                });
            }
        });
    });
</script>


</body>
</html>
