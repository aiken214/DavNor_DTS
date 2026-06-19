@extends('layouts.dts-admin')
@section('content')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Document Tracking System</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li class="fw-medium">
                @if (isset($mySection) && $mySection != null)
                    <div class="btn-group dropstart">
                        <button
                            class="btn btn-success-600 bg-success-100 border-success-100 text-success-600 hover-text-success not-active px-18 py-11 dropdown-toggle toggle-icon icon-left"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $mySection }}
                        </button>
                        <ul class="dropdown-menu">
                            @if (isset($myAllSections))
                                @foreach ($myAllSections as $section)
                                    <li>
                                        <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900"
                                            href="javascript:void(0)" onclick="submitSectionForm('{{ $section->id }}')">
                                            {{ $section->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <!-- Form to submit the selected section_id -->
                    <form id="section-form" method="POST" action="{{ route('user.updateStation') }}"
                        style="display: none;">
                        @csrf
                        <input type="hidden" name="station_id" id="station-id">
                    </form>
                @endif
            </li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0">Profile Page</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between"
                    role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                        {{ session('success') }}
                    </div>
                    <button class="remove-button text-success-600 text-xxl line-height-1"> <iconify-icon
                            icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
                </div>
            @endif


            <!--contents-->
            <div class="row g-3 align-items-stretch">

                {{-- Card 1: Name & Email --}}
                <div class="col-12 col-md-6 d-flex">
                    <div class="card w-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Name and Email Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf
                                @method('patch')

                                <div class="row mb-24 gy-3 align-items-center">
                                    <label class="form-label mb-0 col-sm-2">Name</label>
                                    <div class="col-sm-10">
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="f7:person"></iconify-icon></span>
                                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                                            <input type="hidden" name="name" value="{{ $user->name }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-24 gy-3 align-items-center">
                                    <label class="form-label mb-0 col-sm-2">Email</label>
                                    <div class="col-sm-10">
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="mage:email"></iconify-icon></span>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $user->email }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-24 gy-3 align-items-center">
                                    <label class="form-label mb-0 col-sm-2">DTS Section</label>
                                    <div class="col-sm-10">
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon
                                                    icon="icon-park-outline:intersection"></iconify-icon></span>
                                            <input type="text" class="form-control" value="{{ $user->section->name }}"
                                                @readonly(true)>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10 text-end">
                                        <button type="submit" class="btn btn-success-600">Save Changes</button>
                                    </div>
                                </div>

                            </form>
                        </div>{{-- /.card-body --}}
                    </div>{{-- /.card --}}
                </div>{{-- /.col --}}

                {{-- Card 2: Change Password --}}
                <div class="col-12 col-md-6 d-flex">
                    <div class="card w-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('password.update') }}">
                                @csrf
                                @method('put')

                                <div class="row mb-24 gy-3 align-items-center">
                                    <label class="form-label mb-0 col-sm-3">Current Password</label>
                                    <div class="col-sm-9">
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon
                                                    icon="fluent:key-16-regular"></iconify-icon></span>
                                            <input type="password" name="current_password" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-24 gy-3 align-items-center">
                                    <label class="form-label mb-0 col-sm-3">New Password</label>
                                    <div class="col-sm-9">
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon
                                                    icon="fluent:key-16-regular"></iconify-icon></span>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-24 gy-3 align-items-center">
                                    <label class="form-label mb-0 col-sm-3">Confirm Password</label>
                                    <div class="col-sm-9">
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon
                                                    icon="fluent:key-16-regular"></iconify-icon></span>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-end">
                                        <button type="submit" class="btn btn-danger-600">Change Password</button>
                                    </div>
                                </div>

                            </form>
                        </div>{{-- /.card-body --}}
                    </div>{{-- /.card --}}
                </div>{{-- /.col --}}

            </div>{{-- /.row --}}





            <!--end contents-->
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
