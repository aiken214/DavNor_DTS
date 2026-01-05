@extends('layouts.dts-admin')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">System Settings</h6>
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
        <h5 class="card-title mb-0">System Settings</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
              <div class="d-flex align-items-center gap-2">
                  <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                  {{ session('success') }}
              </div>
              <button class="remove-button text-success-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
          </div>
        @endif

        <table id="systemSetting" class="table bordered-table">
            <thead>
                <tr>
                  <th scope="col-sm">Setting</th>
                  <th scope="col-sm">Value</th>
                  <th scope="col-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
              <tr>
                <td>Organization Name</td>
                <td>{{ $setting->organization->name }}</td>
                <td></td>
              </tr>
                <tr>
                    <td>Organization DTS Code <small>(click to edit Code thru a dropdown menu and changes the Organization Name)</small></td>
                    <td>{{ $setting->org_dts_code }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrgDtsCodeModal">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td>Custom System Name</td>
                    <td>{{ $setting->custom_system_name }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCustomSystemNameModal">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td>Number Padding for Tracking Number <br><small style="font-size:0.7rem;"> (Ex. 0125070001 -padding 0125 is monthyear 07 is the DTS code the following digits is the padding  0001.  Last number is incrementing)</small></td>
                    <td>{{ $setting->number_of_padding }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editNumberOfPaddingModal">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td>Allow Auto Action/Parking of Non Acted Documents</td>
                    <td>{{ $setting->allow_auto_park ? 'Yes' : 'No' }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAllowAutoAcceptModal">Edit</button>
                        <form action="{{ route('admin.park-routes') }}" method="POST" style="display: inline;">
                          @csrf
                          <button type="submit" class="btn btn-danger-400 btn-sm">Run Manual</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>Number of Days for Auto Park on <br>Non Acted Forwarded Documents (Auto Housekeeping)</td>
                    <td>{{ $setting->auto_parkdays }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAutoParkDaysModal">Edit</button>
                    </td>
                </tr>
                <tr>
                  <td> Allowed Guest DTS Form (for offline)</td>
                  <td>
                    @if($setting->allow_guest_docform == true)
                      Yes
                    @else
                      No
                    @endif
                   
                  </td>
                  <td>
                      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAllowGuestDocFormModal">Edit</button>
                  </td>
                </tr>
                {{-- <tr>
                    <td>Logo <small>(168x40 or 1680 x 40 pixels)</small> </td>
                    <td>
                        @if($setting->logo_at)
                            <img src="{{ asset($setting->logo_at) }}" alt="Logo" style="height: 50px;">
                        @else
                            No logo uploaded
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editLogoModal">Edit</button>
                    </td>
                </tr> --}}
                {{-- <tr>
                  <td>Logo Light <small>For Dark Mode (168x40 or 1680 x 40 pixels)</small> </td>
                  <td>
                      @if($setting->logo_light_at)
                      <span style="background-color: rgb(19, 18, 18)">    <img src="{{ asset($setting->logo_light_at) }}" alt="Logo-light" style="height: 50px;"></span>
                      @else
                          No logo uploaded
                      @endif
                  </td>
                  <td>
                      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editLogoLightModal">Edit</button>
                  </td>
              </tr> --}}
                {{-- <tr>
                    <td>Login Welcome Image <small>(917 x 917)</small></td>
                    <td>
                        @if($setting->login_image_at)
                            <img src="{{ asset($setting->login_image_at) }}" alt="Login Image" style="height: 50px;">
                        @else
                            No login image uploaded
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editLoginImageModal">Edit</button>
                    </td>
                </tr> --}}
                {{-- <tr>
                    <td>Allow File Upload</td>
                    <td>{{ $setting->allow_fileupload ? 'Yes' : 'No' }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAllowFileUploadModal">Edit</button>
                    </td>
                </tr> --}}
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Templates -->
<!-- Edit Allow Guest Doc Form Modal -->
<div class="modal fade" id="editAllowGuestDocFormModal" tabindex="-1" aria-labelledby="editAllowGuestDocFormModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAllowGuestDocFormModalLabel">Edit Allow Guest Doc Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="allow_guest_docform" class="form-label">Allow Guest Doc Form</label>
            <select class="form-control" id="allow_guest_docform" name="allow_guest_docform" required>
              <option value="1" {{ $setting->allow_guest_docform ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ !$setting->allow_guest_docform ? 'selected' : '' }}>No</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Edit Organization DTS Code Modal -->
<div class="modal fade" id="editOrgDtsCodeModal" tabindex="-1" aria-labelledby="editOrgDtsCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="editOrgDtsCodeModalLabel">Edit Organization DTS Code</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="org_dts_code" class="form-label">Organization DTS Code</label>
            <select name="org_dts_code" id="org_dts_code" class="form-control">
              @foreach($organizations as $organization)
                  <option value="{{ $organization->dts_code }}" @if($setting->org_dts_code == $organization->dts_code) selected @endif>
                      {{ $organization->dts_code }} - {{ $organization->name }}
                  </option>
              @endforeach
          </select>
       
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Custom System Name Modal -->
<div class="modal fade" id="editCustomSystemNameModal" tabindex="-1" aria-labelledby="editCustomSystemNameModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCustomSystemNameModalLabel">Edit Custom System Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="custom_system_name" class="form-label">Custom System Name</label>
            <input type="text" class="form-control" id="custom_system_name" name="custom_system_name" value="{{ $setting->custom_system_name }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Number of Padding Modal -->
<div class="modal fade" id="editNumberOfPaddingModal" tabindex="-1" aria-labelledby="editNumberOfPaddingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editNumberOfPaddingModalLabel">Edit Number of Padding</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="number_of_padding" class="form-label">Number of Padding</label>
            {{-- <input type="number" class="form-control" id="number_of_padding" name="number_of_padding" value="{{ $setting->number_of_padding }}" required> --}}
            <select name="number_of_padding" id="number_of_padding" class="form-control">
              <option value="4" @if( $setting->number_of_padding==4) selected @endif >4 - Office receiving less than thousand per month</option>
              <option value="5" @if( $setting->number_of_padding==5) selected @endif>5 - Office receiving more than one thousand per month (less 10k)</option>
              <option value="6" @if( $setting->number_of_padding==6) selected @endif>6 - Office receiving more than ten thousand per month</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Allow Auto Accept Modal -->
<div class="modal fade" id="editAllowAutoAcceptModal" tabindex="-1" aria-labelledby="editAllowAutoParkModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAllowAutoAcceptModalLabel">Edit Allow Auto Park</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="allow_auto_park" class="form-label">Allow Auto Park</label>
            <select class="form-control" id="allow_auto_park" name="allow_auto_park" required>
              <option value="1" {{ $setting->allow_auto_park ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ !$setting->allow_auto_park ? 'selected' : '' }}>No</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Number of Days for Auto Accept Modal -->
<div class="modal fade" id="editAutoParkDaysModal" tabindex="-1" aria-labelledby="editAutoParkDaysModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="editAutoParkDaysModalLabel">Edit Number of Days for Auto Park</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="auto_parkdays" class="form-label">Number of Days for Auto Accept</label>
            <input type="number" class="form-control" id="auto_parkdays" name="auto_parkdays" value="{{ $setting->auto_parkdays }}" min="14" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Logo Modal -->
<div class="modal fade" id="editLogoModal" tabindex="-1" aria-labelledby="editLogoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLogoModalLabel">Edit Logo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="logo_at" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo_at" name="logo_at" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Logo-light Modal -->
<div class="modal fade" id="editLogoLightModal" tabindex="-1" aria-labelledby="editLogoLightModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLogoLightModalLabel">Edit Logo Light</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="logo_light_at" class="form-label">Logo Light</label>
            <input type="file" class="form-control" id="logo_light_at" name="logo_light_at" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Login Image Modal -->
<div class="modal fade" id="editLoginImageModal" tabindex="-1" aria-labelledby="editLoginImageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLoginImageModalLabel">Edit Login Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="login_image_at" class="form-label">Login Image</label>
            <input type="file" class="form-control" id="login_image_at" name="login_image_at" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Allow File Upload Modal -->
<div class="modal fade" id="editAllowFileUploadModal" tabindex="-1" aria-labelledby="editAllowFileUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAllowFileUploadModalLabel">Edit Allow File Upload</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dts.system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="mb-3">
            <label for="allow_fileupload" class="form-label">Allow File Upload</label>
            <select class="form-control" id="allow_fileupload" name="allow_fileupload" required>
              <option value="1" {{ $setting->allow_fileupload ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ !$setting->allow_fileupload ? 'selected' : '' }}>No</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      $('#systemSetting').DataTable({
          responsive: true,
          autoWidth: false, // Prevent auto-calculation of width by DataTables
          ordering: false // Disable automatic sorting
          
          
      });
  });

 
</script>
@endsection
