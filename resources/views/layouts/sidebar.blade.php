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

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarLayouts">
                          <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Layouts</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarLayouts">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="layouts-horizontal.html" target="_blank" class="nav-link"
                                      data-key="t-horizontal">Horizontal</a>
                              </li>
                              <li class="nav-item">
                                  <a href="layouts-detached.html" target="_blank" class="nav-link"
                                      data-key="t-detached">Detached</a>
                              </li>
                              <li class="nav-item">
                                  <a href="layouts-two-column.html" target="_blank" class="nav-link"
                                      data-key="t-two-column">Two Column</a>
                              </li>
                              <li class="nav-item">
                                  <a href="layouts-vertical-hovered.html" target="_blank" class="nav-link"
                                      data-key="t-hovered">Hovered</a>
                              </li>
                          </ul>
                      </div>
                  </li> <!-- end Dashboard Menu -->

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



                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarCharts" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarCharts">
                          <i class="ri-pie-chart-line"></i> <span data-key="t-charts">Charts</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarCharts">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="#sidebarApexcharts" class="nav-link" data-bs-toggle="collapse"
                                      role="button" aria-expanded="false" aria-controls="sidebarApexcharts"
                                      data-key="t-apexcharts"> Apexcharts
                                  </a>
                                  <div class="collapse menu-dropdown" id="sidebarApexcharts">
                                      <ul class="nav nav-sm flex-column">
                                          <li class="nav-item">
                                              <a href="charts-apex-line.html" class="nav-link" data-key="t-line">
                                                  Line </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-area.html" class="nav-link" data-key="t-area">
                                                  Area </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-column.html" class="nav-link" data-key="t-column">
                                                  Column </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-bar.html" class="nav-link" data-key="t-bar">
                                                  Bar </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-mixed.html" class="nav-link" data-key="t-mixed">
                                                  Mixed </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-timeline.html" class="nav-link"
                                                  data-key="t-timeline"> Timeline </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-candlestick.html" class="nav-link"
                                                  data-key="t-candlstick"> Candlstick </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-boxplot.html" class="nav-link"
                                                  data-key="t-boxplot"> Boxplot </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-bubble.html" class="nav-link" data-key="t-bubble">
                                                  Bubble </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-scatter.html" class="nav-link"
                                                  data-key="t-scatter"> Scatter </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-heatmap.html" class="nav-link"
                                                  data-key="t-heatmap"> Heatmap </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-treemap.html" class="nav-link"
                                                  data-key="t-treemap"> Treemap </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-pie.html" class="nav-link" data-key="t-pie">
                                                  Pie </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-radialbar.html" class="nav-link"
                                                  data-key="t-radialbar"> Radialbar </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-radar.html" class="nav-link" data-key="t-radar">
                                                  Radar </a>
                                          </li>
                                          <li class="nav-item">
                                              <a href="charts-apex-polar.html" class="nav-link"
                                                  data-key="t-polar-area"> Polar Area </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a href="charts-chartjs.html" class="nav-link" data-key="t-chartjs">
                                      Chartjs </a>
                              </li>
                              <li class="nav-item">
                                  <a href="charts-echarts.html" class="nav-link" data-key="t-echarts">
                                      Echarts </a>
                              </li>
                          </ul>
                      </div>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button"
                          aria-expanded="false" aria-controls="sidebarIcons">
                          <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Icons</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarIcons">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                  <a href="icons-remix.html" class="nav-link" data-key="t-remix">Remix</a>
                              </li>
                              <li class="nav-item">
                                  <a href="icons-boxicons.html" class="nav-link" data-key="t-boxicons">Boxicons</a>
                              </li>
                              <li class="nav-item">
                                  <a href="icons-materialdesign.html" class="nav-link"
                                      data-key="t-material-design">Material Design</a>
                              </li>
                              <li class="nav-item">
                                  <a href="icons-lineawesome.html" class="nav-link" data-key="t-line-awesome">Line
                                      Awesome</a>
                              </li>
                              <li class="nav-item">
                                  <a href="icons-feather.html" class="nav-link" data-key="t-feather">Feather</a>
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
