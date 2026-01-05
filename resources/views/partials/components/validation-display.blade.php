
  <!-- validation display -->
  @if(session('success'))
  <div class="m-3 alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
    <div class="d-flex align-items-center gap-2">
        <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
        {{ session('success') }}
    </div>
    <button class="remove-button text-success-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
  </div>
  @endif
  @if(session('error'))
  <div class="m-3 alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
  <div class="d-flex align-items-center gap-2">
  <iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
  {{ session('error') }} 
  </div>
  <button class="remove-button text-danger-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
  </div>
  @endif

  <!-- Display validation errors -->

<!-- Display validation errors -->
@if ($errors->any())
<div class="m-3 alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
<div class="d-flex align-items-center gap-2">
<iconify-icon icon="mdi:alert-circle-outline" class="icon text-xl"></iconify-icon>
<ul>
  @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
  @endforeach
</ul>
</div>
<button class="remove-button text-danger-600 text-xxl line-height-1"> <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon></button>
</div>
@endif