  <!-- ========== App Menu ========== -->
  <div class="app-menu navbar-menu">
      <!-- LOGO -->
      <div class="navbar-brand-box">
          <!-- Dark Logo-->
          <a href="index.html" class="logo logo-dark">
              <span class="logo-sm">
                  <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
              </span>
              <span class="logo-lg">
                  <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
              </span>
          </a>
          <!-- Light Logo-->
          <a href="index.html" class="logo logo-light">
              <span class="logo-sm">
                  <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
              </span>
              <span class="logo-lg">
                  <img src="{{ asset('assets/images/logo-light2.png') }}" alt="" height="17">
              </span>
          </a>
          <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
              id="vertical-hover">
              <i class="ri-record-circle-line"></i>
          </button>
      </div>
      @php
          $nama_level = ['Administartor', 'User'];
          $level_asset = ['Administartor', 'Pengurus Barang'];
          $level_ksp = ['Validasi', 'Administartor'];
          $level_kabag = ['Administrator', 'Validasi RKA'];
          $level_pb = ['Administrator', 'Pengurus Barang'];
          $level_admin_ppk1 = ['Administrator', 'Admin PPK', 'PPK', 'PPBJ'];
          $level_view = [
              'PPK',
              'PPBJ',
              'Pengurus Barang',
              'PPK Keuangan',
              'PPTK',
              'PPTK Alkes',
              'PPTK Umum',
              'Validasi Keuangan',
              'Verifikator',
              'Direktur',
              'Admin PPK',
              'Bendahara',
              'Pendukung PPK',
          ];
          $level_admin_ppk = ['PPTK', 'PPTK Alkes', 'PPTK Umum'];
          $level_pengadaan_pej = [
              'Administrator',
              'PPK',
              'PPBJ',
              'Pengurus Barang',
              'PPK Keuangan',
              'PPTK',
              'PPTK Alkes',
              'PPTK Umum',
              'Validasi Keuangan',
              'Verifikator',
              'Direktur',
              'Admin PPK',
              'Validasi RKA',
              'Bendahara',
              'Validasi',
              'Pendukung PPK',
          ];
      @endphp

      <div id="scrollbar">
          <div class="container-fluid">

              <div id="two-column-menu">
              </div>
              <ul class="navbar-nav" id="navbar-nav">
                  <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarDashboards">
                          <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Halaman Utama</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarDashboards">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="{{ route('dashbord') }}" class="nav-link" data-key="t-analytics">
                                      Data Anggaran </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('dashboard.apbd') }}" class="nav-link" data-key="t-crm"> Anggaran
                                      APBD </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('dashboard.realisasi') }}" class="nav-link" data-key="t-ecommerce">
                                      Data Asset </a>
                              </li>
                          </ul>
                      </div>
                  </li> <!-- end Dashboard Menu -->

                  @if (session('nama_level_user') == 'Administrator')
                      <li class="nav-item">
                          <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
                              aria-expanded="false" aria-controls="sidebarAuth">
                              <i class="ri-account-circle-line"></i> <span data-key="t-authentication">Konfigurasi
                                  User</span>
                          </a>
                          <div class="collapse menu-dropdown" id="sidebarAuth">
                              <ul class="nav nav-sm flex-column">
                                  <li class="nav-item">
                                      <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse" role="button"
                                          aria-expanded="false" aria-controls="sidebarSignIn" data-key="t-signin">
                                          Setting
                                          User
                                      </a>
                                      <div class="collapse menu-dropdown" id="sidebarSignIn">
                                          <ul class="nav nav-sm flex-column">
                                              <li class="nav-item">
                                                  <a href="{{ route('jabatans.index') }}" class="nav-link"
                                                      data-key="t-basic">
                                                      Struktur Jabatan </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('fungsionals.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Fungsional jabatan </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('units.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Nama Unit </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('level_users.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Level User </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('fases.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Fase User </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('fungsionals.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Fungsional jabatan </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('admin_pendukungs.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Admin PPK</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('pejabat_pengadaans.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Pejabat PPK</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('pptks.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      Pejabat PPTK</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('ppk_keuangans.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      PPK Keuangan</a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse"
                                          role="button" aria-expanded="false" aria-controls="sidebarSignUp"
                                          data-key="t-signup"> User
                                          Profil
                                      </a>
                                      <div class="collapse menu-dropdown" id="sidebarSignUp">
                                          <ul class="nav nav-sm flex-column">
                                              <li class="nav-item">
                                                  <a href="{{ route('pejabats.index') }}" class="nav-link"
                                                      data-key="t-basic">
                                                      Pejabat </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('ksps.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      KSP </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('users.index') }}" class="nav-link"
                                                      data-key="t-cover">
                                                      User </a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                              </ul>
                          </div>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse"
                              role="button" aria-expanded="false" aria-controls="sidebarPages">
                              <i class="ri-pages-line"></i> <span data-key="t-pages">Konfigurasi Input</span>
                          </a>
                          <div class="collapse menu-dropdown" id="sidebarPages">
                              <ul class="nav nav-sm flex-column">
                                  <li class="nav-item">
                                      <a href="{{ route('target_sps.index') }}" class="nav-link"
                                          data-key="t-starter">
                                          Target SPS </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#sidebarProfile" class="nav-link" data-bs-toggle="collapse"
                                          role="button" aria-expanded="false" aria-controls="sidebarProfile"
                                          data-key="t-profile"> Setting Input
                                      </a>
                                      <div class="collapse menu-dropdown" id="sidebarProfile">
                                          <ul class="nav nav-sm flex-column">
                                              <li class="nav-item">
                                                  <a href="{{ route('tahun_anggarans.index') }}" class="nav-link"
                                                      data-key="t-simple-page">
                                                      Tahun Anggaran </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('komponens.index') }}" class="nav-link"
                                                      data-key="t-settings"> Komponen </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('uraian_satus.index') }}" class="nav-link"
                                                      data-key="t-settings"> Uraian Satu </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('uraian_duas.index') }}" class="nav-link"
                                                      data-key="t-settings"> Uraian Dua </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('status_validasis.index') }}" class="nav-link"
                                                      data-key="t-settings"> Status Validasi </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('status_validasi_rkas.index') }}"
                                                      class="nav-link" data-key="t-settings"> Status Validasi RKA </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('judul_headers.index') }}" class="nav-link"
                                                      data-key="t-settings"> Judul Header </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('anggarans.index') }}" class="nav-link"
                                                      data-key="t-settings"> Anggaran </a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#sidebarProfile2" class="nav-link" data-bs-toggle="collapse"
                                          role="button" aria-expanded="false" aria-controls="sidebarProfile2"
                                          data-key="t-profile"> Setting RKBU
                                      </a>
                                      <div class="collapse menu-dropdown" id="sidebarProfile2">
                                          <ul class="nav nav-sm flex-column">
                                              <li class="nav-item">
                                                  <a href="{{ route('obyek_belanjas.index') }}" class="nav-link"
                                                      data-key="t-simple-page">
                                                      Obyek Belanja</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('kategori_rkbus.index') }}" class="nav-link"
                                                      data-key="t-settings"> Kategori RKBU </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('sub_kategori_rkbus.index') }}" class="nav-link"
                                                      data-key="t-settings">Sub Kategori RKBU </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('programs.index') }}" class="nav-link"
                                                      data-key="t-settings"> Program</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('kegiatans.index') }}" class="nav-link"
                                                      data-key="t-settings"> Kegiatan </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('sub_kegiatans.index') }}" class="nav-link"
                                                      data-key="t-settings">Sub Kegiatan</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('sumber_danas.index') }}" class="nav-link"
                                                      data-key="t-settings">Sumber Dana</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('jenis_kategori_rkbus.index') }}"
                                                      class="nav-link" data-key="t-settings">Jenis Kategori RKBU</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('kategori_rekenings.index') }}" class="nav-link"
                                                      data-key="t-settings">Kategori Rekening</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('jenis_belanjas.index') }}" class="nav-link"
                                                      data-key="t-settings">Jenis Belanja</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('aktivitas.index') }}" class="nav-link"
                                                      data-key="t-settings">Aktivitas</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="" class="nav-link" data-key="t-settings">Rekening
                                                      Belanja</a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                                  <li class="nav-item">
                                      <a href="pages-team.html" class="nav-link" data-key="t-team"> Team </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="pages-search-results.html" class="nav-link"
                                          data-key="t-search-results">
                                          Search Results </a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                  @endif

                  <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">RKBU Unit</span>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarUI" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarUI">
                          <i class="ri-pencil-ruler-2-line"></i> <span data-key="t-base-ui">RKBU Pegawai</span>
                      </a>
                      <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarUI">
                          <div class="row">
                              <div class="col-lg-4">
                                  <ul class="nav nav-sm flex-column">
                                      <li class="nav-item">
                                          @if (session('nama_level_user') == 'User')
                                              <a href="{{ route('rkbu_pegawai_pnss.index') }}" class="nav-link"
                                                  data-key="t-form-select">
                                                  Remunerasi PNS</a>
                                          @endif
                                          @if (session('nama_level_user') == 'Administrator')
                                              <a href="{{ route('rkbu_pegawai_pns_admins.index') }}" class="nav-link"
                                                  data-key="t-form-select">
                                                  Remunerasi PNS</a>
                                          @endif
                                          @if (in_array(session('nama_level_user'), $level_view))
                                              <a href="{{ route('rkbu_pegawai_pns_admins.index') }}" class="nav-link"
                                                  data-key="t-form-select">
                                                  Remunerasi PNS </a>
                                          @endif
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarForms">
                          <i class="ri-file-list-3-line"></i> <span data-key="t-forms">RKBU Barang Jasa</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarForms">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  @if (session('nama_level_user') == 'User')
                                      <a href="{{ route('rkbu_pegawai_non_pnss.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Remunerasi Non PNS</a>
                                  @endif
                              </li>
                              <li class="nav-item">
                                  @if (session('nama_level_user') == 'User')
                                      <a href="{{ route('rkbu_barang_jasas.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Barang dan Jasa </a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi')
                                      <a href="{{ route('validasi_ksp_rkbu_barang_jasas.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Barang dan Jasa </a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi RKA')
                                      <a href="{{ route('validasi_rka_rkbu_barjas.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Barang dan Jasa </a>
                                  @endif
                                  @if (session('nama_level_user') == 'Administrator')
                                      <a href="{{ route('rkbu_barjas_admins.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Barang dan Jasa </a>
                                  @endif
                                  @if (in_array(session('nama_level_user'), $level_view))
                                      <a href="{{ route('rkbu_barjas_admins.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Barang dan Jasa </a>
                                  @endif

                              </li>
                              <li class="nav-item">
                                  @if (session('nama_level_user') == 'User')
                                      <a href="{{ route('rkbu_persediaans.index') }}" class="nav-link"
                                          data-key="t-checkboxs-radios">Persediaan</a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi')
                                      <a href="{{ route('validasi_ksp_rkbu_persediaans.index') }}" class="nav-link"
                                          data-key="t-checkboxs-radios">Persediaan</a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi RKA')
                                      <a href="{{ route('validasi_rka_rkbu_persediaans.index') }}" class="nav-link"
                                          data-key="t-checkboxs-radios">Persediaan</a>
                                  @endif
                                  @if (session('nama_level_user') == 'Administrator')
                                      <a href="{{ route('rkbu_persediaan_admins.index') }}" class="nav-link"
                                          data-key="t-checkboxs-radios">Persediaan</a>
                                  @endif
                                  @if (in_array(session('nama_level_user'), $level_view))
                                      <a href="{{ route('rkbu_persediaan_admins.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Persediaan </a>
                                  @endif
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarAdvanceUI" data-bs-toggle="collapse"
                          role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                          <i class="ri-honour-line"></i> <span data-key="t-forms">RKBU Modal Mesin</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarAdvanceUI">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  @if (session('nama_level_user') == 'User')
                                      <a href="{{ route('rkbu_modal_alkess.index') }}" class="nav-link"
                                          data-key="t-basic-elements">Modal
                                          Alkes</a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi')
                                      <a href="{{ route('validasi_ksp_rkbu_modal_alkess.index') }}" class="nav-link"
                                          data-key="t-basic-elements">Modal
                                          Alkes</a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi RKA')
                                      <a href="{{ route('validasi_rka_modal_alkess.index') }}" class="nav-link"
                                          data-key="t-basic-elements">Modal
                                          Alkes</a>
                                  @endif
                                  @if (session('nama_level_user') == 'Administrator')
                                      <a href="{{ route('rkbu_modal_alkes_admins.index') }}" class="nav-link"
                                          data-key="t-basic-elements">Modal
                                          Alkes</a>
                                  @endif
                                  @if (in_array(session('nama_level_user'), $level_view))
                                      <a href="{{ route('rkbu_modal_alkes_admins.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Modal Alkes </a>
                                  @endif
                              </li>
                              <li class="nav-item">
                                  @if (session('nama_level_user') == 'User')
                                      <a href="{{ route('rkbu_modal_kantors.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Modal Kantor </a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi')
                                      <a href="{{ route('validasi_ksp_rkbu_modal_kantors.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Modal Kantor </a>
                                  @endif
                                  @if (session('nama_level_user') == 'Validasi RKA')
                                      <a href="{{ route('validasi_rka_modal_kantor.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Modal Kantor </a>
                                  @endif
                                  @if (session('nama_level_user') == 'Administrator')
                                      <a href="{{ route('rkbu_modal_kantor_admins.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Modal Kantor </a>
                                  @endif
                                  @if (in_array(session('nama_level_user'), $level_view))
                                      <a href="{{ route('rkbu_modal_kantor_admins.index') }}" class="nav-link"
                                          data-key="t-form-select">
                                          Modal Kantor </a>
                                  @endif
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarForms2" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarForms">
                          <i class="ri-file-list-3-line"></i> <span data-key="t-forms">APBD</span>
                          <span class="badge badge-pill bg-success" data-key="t-new">New</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarForms2">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="{{ route('rka_barjas_admins.index') }}" class="nav-link"
                                      data-key="t-form-select">
                                      Barang dan Jasa </a>
                              </li>
                              <li class="nav-item">
                                  <a href="" class="nav-link" data-key="t-form-select">
                                      Modal </a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link menu-link collapsed" href="#sidebarMultilevel" data-bs-toggle="collapse"
                          role="button" aria-expanded="false" aria-controls="sidebarMultilevel">
                          <i class="mdi mdi-share-variant-outline"></i> <span data-key="t-multi-level">History</span>
                      </a>
                      <div class="menu-dropdown collapse" id="sidebarMultilevel" style="">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="{{ route('history_barjas.index') }}" class="nav-link"
                                      data-key="t-level-1.1"> Barang Jasa </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('history_modal.index') }}" class="nav-link"
                                      data-key="t-level-1.1"> Modal </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('history_persediaan.index') }}" class="nav-link"
                                      data-key="t-level-1.1"> Persediaan </a>
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="{{ route('realisasi_rba.index') }}">
                          <i class="ri-rocket-line"></i> <span data-key="t-landing">Kertas Kerja</span>
                          <span class="badge badge-pill bg-success" data-key="t-new">New</span>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link menu-link" href="{{ route('grafik.index') }}">
                          <i class="ri-rocket-line"></i> <span data-key="t-landing">Grafik</span>
                          <span class="badge badge-pill bg-success" data-key="t-new">New</span>
                      </a>
                  </li>


                  <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Pengadaan
                          Belanja</span>
                  </li>
                  @if (in_array(session('nama_level_user'), $level_admin_ppk1))
                      <li class="nav-item">
                          <a class="nav-link menu-link" href="{{ route('perusahaans.index') }}">
                              <i class="ri-rocket-line"></i> <span data-key="t-landing">Perusahaan</span>
                              <span class="badge badge-pill bg-warning" data-key="t-new">New</span>
                          </a>
                      </li>
                  @endif

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarApps">
                          <i class="ri-apps-2-line"></i> <span data-key="t-apps">Master SPJ</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarApps">
                          <ul class="nav nav-sm flex-column">
                              @if (in_array(session('nama_level_user'), $level_admin_ppk1))
                                  <li class="nav-item">
                                      <a href="#sidebarProjects" class="nav-link" data-bs-toggle="collapse"
                                          role="button" aria-expanded="false" aria-controls="sidebarProjects"
                                          data-key="t-projects"> Usulan SPJ
                                      </a>
                                      <div class="collapse menu-dropdown" id="sidebarProjects">
                                          <ul class="nav nav-sm flex-column">
                                              <li class="nav-item">
                                                  <a href="{{ route('usulan_spj_barjass.index') }}" class="nav-link"
                                                      data-key="t-list">
                                                      Barang Jasa </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('usulan_spj_modals.index') }}" class="nav-link"
                                                      data-key="t-overview"> Modal </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('usulan_spj_persediaans.index') }}"
                                                      class="nav-link" data-key="t-create-project"> Persediaan </a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                              @endif
                              @if (in_array(session('nama_level_user'), $level_pengadaan_pej))
                                  <li class="nav-item">
                                      <a href="#sidebarEcommerce" class="nav-link" data-bs-toggle="collapse"
                                          role="button" aria-expanded="false" aria-controls="sidebarEcommerce"
                                          data-key="t-ecommerce">
                                          SPJ
                                          <span class="badge badge-pill bg-warning" data-key="t-new">New</span>
                                      </a>
                                      <div class="collapse menu-dropdown" id="sidebarEcommerce">
                                          <ul class="nav nav-sm flex-column">
                                              <li class="nav-item">
                                                  <a href="{{ route('master_spjs.index') }}" class="nav-link"
                                                      data-key="t-products"> Pengadaan Barang </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('rekap_usulan_belanjas.index') }}"
                                                      class="nav-link">
                                                      Rekap Usulan </a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                              @endif
                          </ul>
                      </div>
                  </li>
                  @if (session('nama_level_user') == 'Validasi')
                      <li class="nav-item">
                          <a class="nav-link menu-link" href="#sidebarCharts" data-bs-toggle="collapse"
                              role="button" aria-expanded="false" aria-controls="sidebarCharts">
                              <i class="ri-shopping-cart-line"></i> <span data-key="t-charts">Usulan Belanja</span>
                          </a>
                          <div class="collapse menu-dropdown" id="sidebarCharts">
                              <ul class="nav nav-sm flex-column">

                                  <li class="nav-item">
                                      <a href="{{ route('usulan_barang_barjass.index') }}" class="nav-link"
                                          data-key="t-chartjs">
                                          Barjas </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route('usulan_barang_persediaans.index') }}" class="nav-link"
                                          data-key="t-echarts">
                                          Persediaan </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route('usulan_barang_modals.index') }}" class="nav-link"
                                          data-key="t-echarts">
                                          Modal </a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                  @endif
                  <li class="nav-item">
                      <a class="nav-link menu-link collapsed" href="#sidebarLayouts" data-bs-toggle="collapse"
                          role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                          <i class="mdi mdi-view-carousel-outline"></i> <span data-key="t-layouts">Cek Usulan
                              Belanja</span>
                      </a>
                      <div class="menu-dropdown collapse" id="sidebarLayouts" style="">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="{{ route('cek_usulan_barang_barjass.index') }}" class="nav-link"
                                      data-key="t-horizontal">Barang Jasa</a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('cek_usulan_barang_modals.index') }}" class="nav-link"
                                      data-key="t-detached">Modal</a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('cek_usulan_barang_persediaans.index') }}" class="nav-link"
                                      data-key="t-two-column">Persediaan</a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  @php
                      // Daftar ID level untuk PPTK
                      $pptk = [
                          '9d7a2cd3-1ab2-4f6b-bba3-3ee6bcc06e85',
                          '9dfafd48-b536-42a0-985e-97eb47168589',
                          '9dfafd54-6bae-4814-b380-dd7b701ae2e7',
                      ];

                      // Tentukan jenis level user
                      if (in_array(session('id_level'), $pptk)) {
                          $role = 'pptk';
                      } elseif (session('nama_level_user') == 'Validasi RKA') {
                          $role = 'rka';
                      } elseif (session('nama_level_user') == 'Direktur') {
                          $role = 'direktur';
                      } else {
                          $role = null;
                      }

                      // Mapping route berdasarkan level user
                      $routes = [
                          'pptk' => [
                              'barang' => 'validasi_usulan_barang_pptks.index',
                              'modal' => 'validasi_usulan_modal_pptks.index',
                              'persediaan' => 'validasi_usulan_persediaan_pptks.index',
                          ],
                          'rka' => [
                              'barang' => 'validasi_usulan_barang_rkas.index',
                              'modal' => 'validasi_usulan_modal_rkas.index',
                              'persediaan' => 'validasi_usulan_persediaan_rkas.index',
                          ],
                          'direktur' => [
                              'barang' => 'validasi_usulan_barang_direkturs.index',
                              'modal' => 'validasi_usulan_modal_direkturs.index',
                              'persediaan' => 'validasi_usulan_persediaan_direkturs.index',
                          ],
                      ];
                  @endphp

                  @if ($role)
                      <li class="menu-title">
                          <i class="ri-more-fill"></i>
                          <span data-key="t-components">Validasi Belanja</span>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link menu-link" href="#sidebarValidasiBelanja" data-bs-toggle="collapse"
                              role="button" aria-expanded="false" aria-controls="sidebarValidasiBelanja">
                              <i class="ri-compasses-2-line"></i>
                              <span data-key="t-icons">Validasi Belanja</span>
                          </a>
                          <div class="collapse menu-dropdown" id="sidebarValidasiBelanja">
                              <ul class="nav nav-sm flex-column">
                                  <li class="nav-item">
                                      <a href="{{ route($routes[$role]['barang']) }}" class="nav-link"
                                          data-key="t-remix">Barjas</a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route($routes[$role]['modal']) }}" class="nav-link"
                                          data-key="t-boxicons">Modal</a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route($routes[$role]['persediaan']) }}" class="nav-link"
                                          data-key="t-material-design">Persediaan</a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                  @endif

                  </li>
                  <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Asset</span>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link menu-link collapsed" href="#sidebarMaps" data-bs-toggle="collapse"
                          role="button" aria-expanded="false" aria-controls="sidebarMaps">
                          <i class="mdi mdi-map-marker-outline"></i> <span data-key="t-maps">Data Asset</span>
                      </a>
                      <div class="menu-dropdown collapse" id="sidebarMaps" style="">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="{{ route('assets.index') }}" class="nav-link" data-key="t-google">
                                      Asset
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('penempatans.index') }}" class="nav-link" data-key="t-vector">
                                      Lokasi Barang
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('reklas_arbs.index') }}" class="nav-link" data-key="t-leaflet">
                                      Reklas ARB
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('laporan_kirs.index') }}" class="nav-link" data-key="t-leaflet">
                                      Laporan KIR
                                  </a>
                              </li>
                          </ul>
                      </div>
                  </li>

              </ul>
          </div>
          <!-- Sidebar -->
      </div>
  </div>
  <!-- Left Sidebar End -->
  <!-- Vertical Overlay-->
  <div class="vertical-overlay"></div>
