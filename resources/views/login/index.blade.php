  @extends('layouts-login.main')
  @section('container')
      <!-- auth page content -->
      <div class="auth-page-content">
          <div class="container">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="text-center mt-sm-5 mb-4 text-white-50">
                          <div>
                              <a href="index.html" class="d-inline-block auth-logo">
                                  <img src="assets/images/logo-light.png" alt="" height="20">
                              </a>
                          </div>
                          <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p>
                      </div>
                  </div>
              </div>
              <!-- end row -->

              <div class="row justify-content-center">
                  <div class="col-md-8 col-lg-6 col-xl-5">
                      <div class="card mt-4">

                          <div class="card-body p-4">
                              <div class="text-center mt-2">
                                  <h5 class="text-primary">Welcome Back !</h5>
                                  <p class="text-muted">Sign in to continue to Velzon.</p>
                              </div>
                              @if (session('error'))
                                  <div class="alert alert-danger alert-border-left alert-dismissible fade shadow show mb-xl-0"
                                      role="alert">
                                      <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Session Timeout
                                          !!!</strong>
                                      - {{ session('error') }}
                                      <button type="button" class="btn-close" data-bs-dismiss="alert"
                                          aria-label="Close"></button>
                                  </div>
                              @endif

                              @if ($errors->any())
                                  <div class="alert alert-danger alert-dismissible alert-additional shadow fade show mb-xl-0"
                                      role="alert">
                                      <div class="alert-body">
                                          <button type="button" class="btn-close" data-bs-dismiss="alert"
                                              aria-label="Close"></button>
                                          <div class="d-flex">
                                              <div class="flex-shrink-0 me-3">
                                                  <i class="ri-alert-line fs-16 align-middle"></i>
                                              </div>
                                              <div class="flex-grow-1">
                                                  <h5 class="alert-heading">Something is very wrong!</h5>
                                                  <p class="mb-0">Login Anda Gagal !!!!</p>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="alert-content">
                                          @foreach ($errors->all() as $error)
                                              <p class="mb-0">{{ $error }}</p>
                                          @endforeach
                                      </div>
                                  </div>
                              @endif


                              <div class="p-2 mt-4">
                                  <form class="mb-3" action="{{ route('login') }}" method="POST">
                                      @csrf
                                      <div class="mb-3">
                                          <label for="username" class="form-label">Username</label>
                                          <input type="text" class="form-control" name="username" id="username"
                                              placeholder="Enter username">
                                      </div>

                                      <div class="mb-3">
                                          <div class="float-end">
                                              <a href="auth-pass-reset-basic.html" class="text-muted">Forgot
                                                  password?</a>
                                          </div>
                                          <label class="form-label" for="password-input">Password</label>
                                          <div class="position-relative auth-pass-inputgroup mb-3">
                                              <input type="password" name="password" class="form-control pe-5"
                                                  placeholder="Enter password" id="password-input">
                                              <button
                                                  class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                  type="button" id="password-addon"><i
                                                      class="ri-eye-fill align-middle"></i></button>
                                          </div>
                                      </div>

                                      <div class="mb-3">
                                          <label for="username" class="form-label">Tahun Anggaran</label>
                                          <select class="form-control choices-select" name="tahun_anggaran">
                                              <option selected="" disabled="">Pilih Tahun Anggaran</option>
                                              @foreach ($tahun_anggarans as $tahun_anggaran)
                                                  <option value="{{ $tahun_anggaran->id }}">
                                                      {{ $tahun_anggaran->nama_tahun_anggaran }}</option>
                                              @endforeach
                                          </select>
                                      </div>

                                      <div class="form-check">
                                          <input class="form-check-input" type="checkbox" value=""
                                              id="auth-remember-check">
                                          <label class="form-check-label" for="auth-remember-check">Remember
                                              me</label>
                                      </div>

                                      <div class="mt-4">
                                          <button class="btn btn-info w-100" type="submit">Sign In</button>
                                      </div>


                                  </form>
                              </div>
                          </div>
                          <!-- end card body -->
                      </div>
                      <!-- end card -->

                      <div class="mt-4 text-center">
                          <p class="mb-0">Don't have an account ? <a href="auth-signup-basic.html"
                                  class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                      </div>

                  </div>
              </div>
              <!-- end row -->
          </div>
          <!-- end container -->
      </div>
      <!-- end auth page content -->
  @endsection
