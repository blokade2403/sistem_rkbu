  <div class="row">
      <div class="col-xl-12">
          <div class="card crm-widget">
              <div class="card-body p-0">
                  <div class="row row-cols-xxl-4 row-cols-md-3 row-cols-1 g-0">
                      <div class="col">
                          <div class="py-4 px-3">
                              <h5 class="text-muted text-uppercase fs-13">TOTAL ANGGARAN<i
                                      class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                              </h5>
                              <div class="d-flex align-items-center">
                                  <div class="flex-shrink-0">
                                      <lord-icon src="https://cdn.lordicon.com/fhtaantg.json" trigger="loop"
                                          colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                      </lord-icon>
                                  </div>
                                  <div class="flex-grow-1 ms-3">
                                      <h2 class="mb-0"><span>Rp.
                                              {{ number_format($total_apbd_blud, 0, ',', ',') }}</span>
                                      </h2>
                                  </div>
                              </div>
                              <p class="text-muted text-truncate mb-0">Projects this month</p>
                          </div>
                      </div><!-- end col -->
                      <div class="col">
                          <div class="mt-3 mt-md-0 py-4 px-3">
                              <h5 class="text-muted text-uppercase fs-13">Belanja Pegawai<i
                                      class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                              </h5>
                              <div class="d-flex align-items-center">
                                  <div class="flex-shrink-0">
                                      <lord-icon src="https://cdn.lordicon.com/qhviklyi.json" trigger="loop"
                                          colors="primary:#405189,secondary:#0ab39c"
                                          style="width:55px;height:55px"></lord-icon>
                                  </div>
                                  <div class="flex-grow-1 ms-3">
                                      <h2 class="mb-0"><span>Rp.
                                              {{ number_format($total_apbd_blud_pegawai, 0, ',', ',') }}</span>
                                      </h2>
                                  </div>
                              </div>
                          </div>
                      </div><!-- end col -->
                      <div class="col">
                          <div class="mt-3 mt-md-0 py-4 px-3">
                              <h5 class="text-muted text-uppercase fs-13">Belanja Barang Jasa<i
                                      class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                              </h5>
                              <div class="d-flex align-items-center">
                                  <div class="flex-shrink-0">
                                      <i class="ri-pulse-line display-6 text-muted"></i>
                                  </div>
                                  <div class="flex-grow-1 ms-3">
                                      <h2 class="mb-0"><span>Rp.
                                              {{ number_format($total_apbd_blud_barjas, 0, ',', ',') }}</span>
                                      </h2>
                                  </div>
                              </div>
                          </div>
                      </div><!-- end col -->
                      <div class="col">
                          <div class="mt-3 mt-lg-0 py-4 px-3">
                              <h5 class="text-muted text-uppercase fs-13">Belanja Modal<i
                                      class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                              </h5>
                              <div class="d-flex align-items-center">
                                  <div class="flex-shrink-0">
                                      <lord-icon src="https://cdn.lordicon.com/vaeagfzc.json" trigger="loop"
                                          colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                      </lord-icon>
                                  </div>
                                  <div class="flex-grow-1 ms-3">
                                      <h2 class="mb-0"><span>Rp.
                                              {{ number_format($total_apbd_blud_modal, 0, ',', ',') }}</span>
                                      </h2>
                                  </div>
                              </div>
                          </div>
                      </div><!-- end col -->
                  </div><!-- end row -->
              </div><!-- end card body -->
          </div><!-- end card -->
      </div><!-- end col -->
  </div>
