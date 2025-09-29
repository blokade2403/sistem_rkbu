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
                  <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="17">
              </span>
          </a>
          <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
              id="vertical-hover">
              <i class="ri-record-circle-line"></i>
          </button>
      </div>

      <div id="scrollbar">
          <div class="container-fluid">

              <div id="two-column-menu">
              </div>
              <ul class="navbar-nav" id="navbar-nav">
                  <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarDashboards">
                          <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarDashboards">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="dashboard-analytics.html" class="nav-link" data-key="t-analytics">
                                      Analytics </a>
                              </li>
                              <li class="nav-item">
                                  <a href="dashboard-crm.html" class="nav-link" data-key="t-crm"> CRM </a>
                              </li>
                              <li class="nav-item">
                                  <a href="index.html" class="nav-link" data-key="t-ecommerce"> Ecommerce </a>
                              </li>
                          </ul>
                      </div>
                  </li> <!-- end Dashboard Menu -->
                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarApps">
                          <i class="ri-apps-2-line"></i> <span data-key="t-apps">Apps</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarApps">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="#sidebarEcommerce" class="nav-link" data-bs-toggle="collapse" role="button"
                                      aria-expanded="false" aria-controls="sidebarEcommerce" data-key="t-ecommerce">
                                      Ecommerce
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarEcommerce">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="apps-ecommerce-products.html" class="nav-link"
                                                  data-key="t-products"> Products </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-ecommerce-product-details.html" class="nav-link"
                                                  data-key="t-product-Details"> Product Details </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-ecommerce-add-product.html" class="nav-link"
                                                  data-key="t-create-product"> Create Product </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-ecommerce-orders.html" class="nav-link" data-key="t-orders">
                                                  Orders </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-ecommerce-order-details.html" class="nav-link"
                                                  data-key="t-order-details"> Order Details </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a href="#sidebarProjects" class="nav-link" data-bs-toggle="collapse"
                                      role="button" aria-expanded="false" aria-controls="sidebarProjects"
                                      data-key="t-projects"> Projects
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarProjects">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="apps-projects-list.html" class="nav-link" data-key="t-list">
                                                  List </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-projects-overview.html" class="nav-link"
                                                  data-key="t-overview"> Overview </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-projects-create.html" class="nav-link"
                                                  data-key="t-create-project"> Create Project </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a href="#sidebarTasks" class="nav-link" data-bs-toggle="collapse" role="button"
                                      aria-expanded="false" aria-controls="sidebarTasks" data-key="t-tasks"> Tasks
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarTasks">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="apps-tasks-kanban.html" class="nav-link"
                                                  data-key="t-kanbanboard"> Kanban Board </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-tasks-list-view.html" class="nav-link"
                                                  data-key="t-list-view"> List View </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-tasks-details.html" class="nav-link"
                                                  data-key="t-task-details"> Task Details </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a href="#sidebarCRM" class="nav-link" data-bs-toggle="collapse" role="button"
                                      aria-expanded="false" aria-controls="sidebarCRM" data-key="t-crm"> CRM
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarCRM">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="apps-crm-contacts.html" class="nav-link"
                                                  data-key="t-contacts"> Contacts </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="apps-crm-companies.html" class="nav-link"
                                                  data-key="t-companies"> Companies </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarAuth">
                          <i class="ri-account-circle-line"></i> <span data-key="t-authentication">User Profil</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarAuth">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse" role="button"
                                      aria-expanded="false" aria-controls="sidebarSignIn" data-key="t-signin"> User
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarSignIn">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="{{ route('users.index') }}" class="nav-link"
                                                  data-key="t-basic">
                                                  User Login </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="auth-signin-cover.html" class="nav-link" data-key="t-cover">
                                                  Cover </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse" role="button"
                                      aria-expanded="false" aria-controls="sidebarSignUp" data-key="t-signup"> Sign
                                      Up
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarSignUp">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="auth-signup-basic.html" class="nav-link" data-key="t-basic">
                                                  Basic </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="auth-signup-cover.html" class="nav-link" data-key="t-cover">
                                                  Cover </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarPages">
                          <i class="ri-pages-line"></i> <span data-key="t-pages">Pages</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarPages">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="pages-starter.html" class="nav-link" data-key="t-starter">
                                      Starter </a>
                              </li>
                              <li class="nav-item">
                                  <a href="#sidebarProfile" class="nav-link" data-bs-toggle="collapse"
                                      role="button" aria-expanded="false" aria-controls="sidebarProfile"
                                      data-key="t-profile"> Profile
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarProfile">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="pages-profile.html" class="nav-link" data-key="t-simple-page">
                                                  Simple Page </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="pages-profile-settings.html" class="nav-link"
                                                  data-key="t-settings"> Settings </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a href="#sidebarProfile2" class="nav-link" data-bs-toggle="collapse"
                                      role="button" aria-expanded="false" aria-controls="sidebarProfile2"
                                      data-key="t-profile"> Profile
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarProfile2">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="pages-profile.html" class="nav-link" data-key="t-simple-page">
                                                  Simple Page </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="pages-profile-settings.html" class="nav-link"
                                                  data-key="t-settings"> Settings </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a href="pages-team.html" class="nav-link" data-key="t-team"> Team </a>
                              </li>
                              <li class="nav-item">
                                  <a href="pages-search-results.html" class="nav-link" data-key="t-search-results">
                                      Search Results </a>
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" target="_blank" href="landing.html">
                          <i class="ri-rocket-line"></i> <span data-key="t-landing">Landing</span>
                          <span class="badge badge-pill bg-danger" data-key="t-new">New</span>
                      </a>
                  </li>

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
                                          <a href="ui-alerts.html" class="nav-link" data-key="t-alerts">Remunerasi
                                              PNS</a>
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
                                  <a href="forms-elements.html" class="nav-link"
                                      data-key="t-basic-elements">Remunerasi Non PNS</a>
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
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="widgets.html">
                          <i class="ri-honour-line"></i> <span data-key="t-widgets">Widgets</span>
                      </a>
                  </li>

                  <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Pengadaan
                          Belanja</span>
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
                                  <li class="nav-item">
                                      <a href="#sidebarApexcharts" class="nav-link" data-bs-toggle="collapse"
                                          role="button" aria-expanded="false" aria-controls="sidebarApexcharts"
                                          data-key="t-apexcharts"> Cek Usulan Belanja
                                      </a>
                                      <div class="collapse menu-dropdown" id="sidebarApexcharts">
                                          <ul class="nav nav-sm flex-column">
                                              <li class="nav-item">
                                                  <a href="{{ route('cek_usulan_barang_barjass.index') }}"
                                                      class="nav-link" data-key="t-line">
                                                      Belanja Barang dan Jasa </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('cek_usulan_barang_modals.index') }}"
                                                      class="nav-link" data-key="t-area">
                                                      Modal </a>
                                              </li>
                                              <li class="nav-item">
                                                  <a href="{{ route('cek_usulan_barang_persediaans.index') }}"
                                                      class="nav-link" data-key="t-bar">
                                                      Persediaan </a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                              </ul>
                          </div>
                      </li>
                  @endif
                  <li class="nav-item">
                      <a class="nav-link menu-link collapsed" href="#sidebarLayouts" data-bs-toggle="collapse"
                          role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                          <i class="mdi mdi-view-carousel-outline"></i> <span data-key="t-layouts">Usulan SPJ</span>
                      </a>
                      <div class="menu-dropdown collapse" id="sidebarLayouts" style="">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="{{ route('usulan_spj_barjass.index') }}" class="nav-link" target="_blank"
                                      data-key="t-horizontal">SPJ Barang Jasa</a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('usulan_spj_modals.index') }}" class="nav-link" target="_blank"
                                      data-key="t-detached">SPJ Modal</a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('usulan_spj_persediaans.index') }}" class="nav-link"
                                      target="_blank" data-key="t-two-column">SPJ Persediaan</a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  @php
                      $pptk = [
                          '9d7a2cd3-1ab2-4f6b-bba3-3ee6bcc06e85',
                          '9dfafd48-b536-42a0-985e-97eb47168589',
                          '9dfafd54-6bae-4814-b380-dd7b701ae2e7',
                      ];
                  @endphp
                  <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Validasi
                          Belanja</span>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarIcons">
                          <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Validasi Belanja</span>
                      </a>
                      @if (in_array(session('id_level'), $pptk))
                          <div class="collapse menu-dropdown" id="sidebarIcons">
                              <ul class="nav nav-sm flex-column">
                                  <li class="nav-item">
                                      <a href="{{ route('validasi_usulan_barang_pptks.index') }}" class="nav-link"
                                          data-key="t-remix">Barjas</a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route('validasi_usulan_modal_pptks.index') }}" class="nav-link"
                                          data-key="t-boxicons">Modal</a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route('validasi_usulan_persediaan_pptks.index') }}"
                                          class="nav-link" data-key="t-material-design">Persediaan</a>
                                  </li>
                              </ul>
                          </div>
                      @endif
                  </li>
              </ul>
          </div>
          <!-- Sidebar -->
      </div>
  </div>
  <!-- Left Sidebar End -->
  <!-- Vertical Overlay-->
  <div class="vertical-overlay"></div>
