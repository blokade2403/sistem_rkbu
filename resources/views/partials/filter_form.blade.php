  <div class="card-body border border-dashed border-end-0 border-start-0">
      <form>
          <div class="row g-3">
              <div class="card-body border-bottom-dashed border-bottom">
                  <form action="{{ route($routePrefix . '.index') }}" method="GET">
                      <div class="d-flex flex-wrap align-items-end gap-3">
                          <!-- Sub Kategori RKBU -->
                          <div class="flex-grow-1" style="min-width:220px;">
                              <label class="form-label">Sub Kategori RKBU</label>
                              <select name="sub_kategori_rkbu" id="sub_kategori_rkbu" class="form-select" data-choices
                                  data-choices-sorting="true">
                                  <option value="">-- Pilih Sub Kategori --</option>
                              </select>
                          </div>

                          <!-- Status Validasi -->
                          <div class="flex-grow-1" style="min-width:220px;">
                              <label class="form-label">Status</label>
                              <select name="status_pembayaran" id="status_pembayaran" class="form-select" data-choices
                                  data-choices-sorting="true">
                                  <option value="">-- Pilih Status --</option>
                                  <option value="Sudah di Bayar">
                                      Sudah di Bayar
                                  </option>
                                  <option value="Parsial">
                                      Parsial
                                  </option>
                              </select>
                          </div>

                          <div class="flex-grow-1" style="min-width:220px;">
                              <label class="form-label">Tanggal Faktur Dari</label>
                              <input type="date" name="tanggal_dari" class="form-control" value="">
                          </div>

                          <div class="flex-grow-1" style="min-width:220px;">
                              <label class="form-label">Tanggal Faktur Sampai</label>
                              <input type="date" name="tanggal_sampai" class="form-control" value="">
                          </div>

                          <!-- Tombol -->
                          <div class="d-flex flex-wrap gap-2">

                              <button type="submit" class="btn btn-primary btn-label">
                                  <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                  Filter
                              </button>

                              <a href="{{ url()->current() }}" class="btn btn-secondary btn-label">
                                  <i class="ri-repeat-fill label-icon align-middle fs-16 me-2"></i>
                                  Reset
                              </a>
                          </div>

                      </div>
                  </form>
              </div>

              <!--end col-->
          </div>
          <!--end row-->
      </form>
  </div>
