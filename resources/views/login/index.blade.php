  @extends('layouts-login.main')
  @section('container')
      <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
          <a href="" class="mb-12">
              <img alt="Logo" src="{{ $header6LogoUrl }}" class="mw-100" style="width: 340px; max-height: 190px; object-fit: contain;" />
          </a>
          <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
              <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('login.process') }}"
                  method="POST">
                  @csrf
                  <div class="text-center mb-10">
                      <h3 class="text-dark mb-3">Halaman Login Si-Penguin</h3>
                      <div class="text-gray-400 fw-bold fx-4">Belum punya akun?
                          <a href=""
                              class="link-primary fw-bolder">Create an Account</a>
                      </div>
                  </div>

                  <div class="fv-row mb-10">
                      <label class="form-label fs-6 fw-bolder text-dark">Username</label>
                      <input class="form-control form-control-lg form-control-solid" type="text" name="username"
                          autocomplete="off" />
                  </div>
                  <div class="fv-row mb-10">
                      <div class="d-flex flex-stack mb-2">
                          <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                      </div>
                      <input class="form-control form-control-lg form-control-solid" type="password" name="password"
                          autocomplete="off" />
                  </div>
                  <div class="fv-row mb-10">
                      <div class="d-flex flex-stack mb-2">
                          <label class="form-label fw-bolder text-dark fs-6 mb-0">Tahun Anggaran</label>
                      </div>
                      <select name="tahun_anggaran_id" aria-label="Select a Timezone" data-control="select2"
                          data-placeholder="Pilih tahun anggaran" class="form-select form-select-solid form-select-lg">
                          <option value="">Select a Tahun Anggaran</option>
                          @foreach ($tahun as $tahun_anggaran)
                              <option value="{{ $tahun_anggaran->id }}">
                                  {{ $tahun_anggaran->nama_tahun_anggaran }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="text-center">
                      <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                          <span class="indicator-label">Sign In</span>
                          <span class="indicator-progress">Please wait...
                              <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                  </div>
              </form>
          </div>
      </div>
  @endsection
