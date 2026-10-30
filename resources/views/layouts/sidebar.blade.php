 <div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
     data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_aside_mobile_toggle">
     <div class="aside-logo flex-column-auto" id="kt_aside_logo">
         <!--begin::Logo-->
         <a href="{{ route('dashboard') }}">
             <img alt="Logo" src="{{ asset('assets/media/logos/logo-1-dark.svg') }}" class="h-25px logo" />
         </a>
         <!--end::Logo-->
         <!--begin::Aside toggler-->
         <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="aside-minimize">
             <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
             <span class="svg-icon svg-icon-1 rotate-180">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none">
                     <path opacity="0.5"
                         d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                         fill="black" />
                     <path
                         d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                         fill="black" />
                 </svg>
             </span>
             <!--end::Svg Icon-->
         </div>
         <!--end::Aside toggler-->
     </div>
     <!--end::Brand-->
     <!--begin::Aside menu-->
     <div class="aside-menu flex-column-fluid">
         <!--begin::Aside Menu-->
         <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
             data-kt-scroll-offset="0">
             <!--begin::Menu-->
             <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                 id="kt_aside_menu" data-kt-menu="true">
                 <div class="menu-item">
                     <div class="menu-content pb-2">
                         <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard2</span>
                     </div>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="{{ route('module-portal') }}">
                         <span class="menu-icon"><i class="fas fa-layer-group fs-4"></i></span>
                         <span class="menu-title">Beranda Modul</span>
                     </a>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link active" href="{{ route('dashboard') }}">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                     <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                         fill="black" />
                                     <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                         fill="black" />
                                     <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Halaman Dashboard</span>
                     </a>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="{{ route('monitoring-anggaran.blud') }}">
                         <span class="menu-icon"><i class="fas fa-chart-line fs-4"></i></span>
                         <span class="menu-title">Monitoring Realisasi BLUD</span>
                     </a>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="{{ route('monitoring-anggaran.keseluruhan') }}">
                         <span class="menu-icon"><i class="fas fa-chart-pie fs-4"></i></span>
                         <span class="menu-title">Monitoring Keseluruhan Anggaran</span>
                     </a>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="{{ route('monitoring-anggaran.laporan-bulanan') }}">
                         <span class="menu-icon"><i class="fas fa-calendar-alt fs-4"></i></span>
                         <span class="menu-title">Laporan Realisasi Bulanan</span>
                     </a>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="{{ route('dashboard') }}">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25"
                                     viewBox="0 0 24 25" fill="none">
                                     <path opacity="0.3"
                                         d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
                                         fill="black" />
                                     <path
                                         d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">No Toolbar</span>
                     </a>
                 </div>
                  @if (auth()->user()->hasRole('Super Admin'))
                 <div class="menu-item">
                     <div class="menu-content pt-8 pb-2">
                         <span class="menu-section text-muted text-uppercase fs-8 ls-1">Administrator</span>
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm007.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M21 9V11C21 11.6 20.6 12 20 12H14V8H20C20.6 8 21 8.4 21 9ZM10 8H4C3.4 8 3 8.4 3 9V11C3 11.6 3.4 12 4 12H10V8Z"
                                         fill="black" />
                                     <path d="M15 2C13.3 2 12 3.3 12 5V8H15C16.7 8 18 6.7 18 5C18 3.3 16.7 2 15 2Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M9 2C10.7 2 12 3.3 12 5V8H9C7.3 8 6 6.7 6 5C6 3.3 7.3 2 9 2ZM4 12V21C4 21.6 4.4 22 5 22H10V12H4ZM20 12V21C20 21.6 19.6 22 19 22H14V12H20Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Halaman Utama</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                         <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                             <span class="menu-link">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Anggaran</span>
                                 <span class="menu-arrow"></span>
                             </span>
                             <div class="menu-sub menu-sub-accordion menu-active-bg">
                                 <div class="menu-item">
                                     <a class="menu-link" href="../../demo1/dist/pages/profile/overview.html">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">APBD</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="../../demo1/dist/pages/profile/connections.html">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">BLUD</span>
                                     </a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                         fill="black" />
                                     <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Konfigurasi User</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                         <div class="menu-item">
                             <div class="menu-content">
                                 <span class="menu-section text-muted text-uppercase fs-8 ls-1">User</span>
                             </div>
                             <a class="menu-link" href="{{ route('positions.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Jabatan</span>
                             </a>
                             <a class="menu-link" href="{{ route('position_assignments.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Penugasan Pejabat</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('units.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Unit</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('roles.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Role</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('users.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Users</span>
                             </a>
                         </div>
                         {{-- <div class="menu-item">
                                        <a class="menu-link" href="{{ route('user-pptk.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">User PPTK</span>
                                        </a>
                                    </div> --}}
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('fases.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Fases</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/technology/teh004.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path opacity="0.3"
                                         d="M21 10.7192H3C2.4 10.7192 2 11.1192 2 11.7192C2 12.3192 2.4 12.7192 3 12.7192H6V14.7192C6 18.0192 8.7 20.7192 12 20.7192C15.3 20.7192 18 18.0192 18 14.7192V12.7192H21C21.6 12.7192 22 12.3192 22 11.7192C22 11.1192 21.6 10.7192 21 10.7192Z"
                                         fill="black" />
                                     <path
                                         d="M11.6 21.9192C11.4 21.9192 11.2 21.8192 11 21.7192C10.6 21.4192 10.5 20.7191 10.8 20.3191C11.7 19.1191 12.3 17.8191 12.7 16.3191C12.8 15.8191 13.4 15.4192 13.9 15.6192C14.4 15.7192 14.8 16.3191 14.6 16.8191C14.2 18.5191 13.4 20.1192 12.4 21.5192C12.2 21.7192 11.9 21.9192 11.6 21.9192ZM8.7 19.7192C10.2 18.1192 11 15.9192 11 13.7192V8.71917C11 8.11917 11.4 7.71917 12 7.71917C12.6 7.71917 13 8.11917 13 8.71917V13.0192C13 13.6192 13.4 14.0192 14 14.0192C14.6 14.0192 15 13.6192 15 13.0192V8.71917C15 7.01917 13.7 5.71917 12 5.71917C10.3 5.71917 9 7.01917 9 8.71917V13.7192C9 15.4192 8.4 17.1191 7.2 18.3191C6.8 18.7191 6.9 19.3192 7.3 19.7192C7.5 19.9192 7.7 20.0192 8 20.0192C8.3 20.0192 8.5 19.9192 8.7 19.7192ZM6 16.7192C6.5 16.7192 7 16.2192 7 15.7192V8.71917C7 8.11917 7.1 7.51918 7.3 6.91918C7.5 6.41918 7.2 5.8192 6.7 5.6192C6.2 5.4192 5.59999 5.71917 5.39999 6.21917C5.09999 7.01917 5 7.81917 5 8.71917V15.7192V15.8191C5 16.3191 5.5 16.7192 6 16.7192ZM9 4.71917C9.5 4.31917 10.1 4.11918 10.7 3.91918C11.2 3.81918 11.5 3.21917 11.4 2.71917C11.3 2.21917 10.7 1.91916 10.2 2.01916C9.4 2.21916 8.59999 2.6192 7.89999 3.1192C7.49999 3.4192 7.4 4.11916 7.7 4.51916C7.9 4.81916 8.2 4.91918 8.5 4.91918C8.6 4.91918 8.8 4.81917 9 4.71917ZM18.2 18.9192C18.7 17.2192 19 15.5192 19 13.7192V8.71917C19 5.71917 17.1 3.1192 14.3 2.1192C13.8 1.9192 13.2 2.21917 13 2.71917C12.8 3.21917 13.1 3.81916 13.6 4.01916C15.6 4.71916 17 6.61917 17 8.71917V13.7192C17 15.3192 16.8 16.8191 16.3 18.3191C16.1 18.8191 16.4 19.4192 16.9 19.6192C17 19.6192 17.1 19.6192 17.2 19.6192C17.7 19.6192 18 19.3192 18.2 18.9192Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Konfigurasi</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                         <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                             <span class="menu-link">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Setting Input</span>
                                 <span class="menu-arrow"></span>
                             </span>
                             <div class="menu-sub menu-sub-accordion menu-active-bg">
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('tahun_anggarans.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Tahun Anggaran</span>
                                     </a>
                                 </div>
                                 @if (auth()->user()?->hasRole('Administrator'))
                                     <div class="menu-item">
                                         <a class="menu-link" href="{{ route('pagu_anggaran.index') }}">
                                             <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                             <span class="menu-title">Pagu Anggaran</span>
                                         </a>
                                     </div>
                                 @endif
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('komponens.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Komponen</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('uraian_satus.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Uraian 1</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('uraian_duas.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Uraian 2</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('ppns.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">PPN</span>
                                     </a>
                                 </div>
                             </div>
                         </div>
                         <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                             <span class="menu-link">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Setting RKBU</span>
                                 <span class="menu-arrow"></span>
                             </span>
                             <div class="menu-sub menu-sub-accordion menu-active-bg">
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('aktivitas.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Aktivitas</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('jenis_belanjas.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Jenis Belanja</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('jenis_kategori_rkbus.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Jenis Kategori RKBU</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('programs.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Program</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('kegiatans.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Kegiatan</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('sub_kegiatans.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Sub Kegiatan</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('kategori_rekenings.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Kategori Rekening</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('obyek_belanjas.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Obyek Belanja</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('kategori_rkbus.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Kategori RKBU</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('sumber_danas.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Sumber Dana</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('sub_kegiatans.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Sub Kegiatan</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('rekening_belanjas.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Rekening Belanja</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('sub_kategori_rekenings.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Sub Kategori Rekening</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('sub_kategori_rkbus.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Sub Kategori RKBU</span>
                                     </a>
                                 </div>
                             </div>
                         </div>

                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('judul_headers.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Judul Header</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen022.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z"
                                         fill="black" />
                                     <path
                                         d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Work Flow Setting</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('workflows.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Workflow</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('workflow_steps.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Workflow Step</span>
                             </a>
                         </div>
                         <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                             <span class="menu-link">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Roles Sub Kategori</span>
                                 <span class="menu-arrow"></span>
                             </span>
                             <div class="menu-sub menu-sub-accordion">
                                 <div class="menu-item">
                                     <a class="menu-link"
                                         href="{{ route('workflow-step-sub-kategori-rkbu.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Workflow Step Sub Kategori RKBU</span>
                                     </a>
                                 </div>
                                 {{-- <div class="menu-item">
                                        <a class="menu-link" href="{{ route('workflow_step_pptks.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Workflow Step PPTK</span>
                                        </a>
                                    </div> --}}
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('position_sub_kategori_rkbu.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Position Sub Kategori RKBU</span>
                                     </a>
                                 </div>
                             </div>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="#">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Approval</span>
                             </a>
                         </div>
                         {{-- <div class="menu-item">
                                        <a class="menu-link" href="{{ route('pptk_kategori.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">PPTK Kategori</span>
                                        </a>
                                    </div> --}}
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen051.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path opacity="0.3"
                                         d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                         fill="black" />
                                     <path
                                         d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">User Management</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion">
                         <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                             <span class="menu-link">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Users Validator</span>
                                 <span class="menu-arrow"></span>
                             </span>
                             <div class="menu-sub menu-sub-accordion">
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('validator-roles.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">Validator Roles</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link" href="{{ route('user-validator-roles.index') }}">
                                         <span class="menu-bullet">
                                             <span class="bullet bullet-dot"></span>
                                         </span>
                                         <span class="menu-title">User Validator Roles</span>
                                     </a>
                                 </div>
                             </div>
                         </div>

                     </div>
                 </div>
                 @endif

                 <div class="menu-item">
                     <div class="menu-content pt-8 pb-2">
                         <span class="menu-section text-muted text-uppercase fs-8 ls-1">Apps</span>
                     </div>
                 </div>
                 @if (auth()->user()->hasRole('Operator') || auth()->user()->hasRole('Super Admin'))
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path opacity="0.3"
                                         d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z"
                                         fill="black" />
                                     <path
                                         d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Perencanaan</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion">
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('rkbu.pegawai.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">RKBU Pegawai</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('rkbu.barjas.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">RKBU Barjas</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('rkbu.persediaan.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">RKBU Persediaan</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('rkbu.modal_kantor.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">RKBU Modal Kantor</span>
                             </a>
                         </div>
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('rkbu.modal_alkes.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">RKBU Modal Alkes</span>
                             </a>
                         </div>
                     </div>
                 </div>
                @endif
                @if (auth()->user()->hasRole('Validator') || auth()->user()->hasRole('Super Admin'))
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M21 10H13V11C13 11.6 12.6 12 12 12C11.4 12 11 11.6 11 11V10H3C2.4 10 2 10.4 2 11V13H22V11C22 10.4 21.6 10 21 10Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M12 12C11.4 12 11 11.6 11 11V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V11C13 11.6 12.6 12 12 12Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M18.1 21H5.9C5.4 21 4.9 20.6 4.8 20.1L3 13H21L19.2 20.1C19.1 20.6 18.6 21 18.1 21ZM13 18V15C13 14.4 12.6 14 12 14C11.4 14 11 14.4 11 15V18C11 18.6 11.4 19 12 19C12.6 19 13 18.6 13 18ZM17 18V15C17 14.4 16.6 14 16 14C15.4 14 15 14.4 15 15V18C15 18.6 15.4 19 16 19C16.6 19 17 18.6 17 18ZM9 18V15C9 14.4 8.6 14 8 14C7.4 14 7 14.4 7 15V18C7 18.6 7.4 19 8 19C8.6 19 9 18.6 9 18Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Usulan Belanja</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion">
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('usulan-belanja.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Usulan Belanja</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 @endif
                 @if (!auth()->user()->hasRole('Operator') || auth()->user()->hasRole('Super Admin'))
                 <div class="menu-item">
                     <div class="menu-content pt-8 pb-0">
                         <span class="menu-section text-muted text-uppercase fs-8 ls-1">Validasi</span>
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/abstract/abs042.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M18 21.6C16.6 20.4 9.1 20.3 6.3 21.2C5.7 21.4 5.1 21.2 4.7 20.8L2 18C4.2 15.8 10.8 15.1 15.8 15.8C16.2 18.3 17 20.5 18 21.6ZM18.8 2.8C18.4 2.4 17.8 2.20001 17.2 2.40001C14.4 3.30001 6.9 3.2 5.5 2C6.8 3.3 7.4 5.5 7.7 7.7C9 7.9 10.3 8 11.7 8C15.8 8 19.8 7.2 21.5 5.5L18.8 2.8Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M21.2 17.3C21.4 17.9 21.2 18.5 20.8 18.9L18 21.6C15.8 19.4 15.1 12.8 15.8 7.8C18.3 7.4 20.4 6.70001 21.5 5.60001C20.4 7.00001 20.2 14.5 21.2 17.3ZM8 11.7C8 9 7.7 4.2 5.5 2L2.8 4.8C2.4 5.2 2.2 5.80001 2.4 6.40001C2.7 7.40001 3.00001 9.2 3.10001 11.7C3.10001 15.5 2.40001 17.6 2.10001 18C3.20001 16.9 5.3 16.2 7.8 15.8C8 14.2 8 12.7 8 11.7Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Approval RKBU</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                             <div class="menu-item">
                                 <a class="menu-link"
                                     href="{{ route('workflowapproval.index', ['module' => 'barjas']) }}">
                                     <span class="menu-bullet">
                                         <span class="bullet bullet-dot"></span>
                                     </span>
                                     <span class="menu-title">Barang Jasa</span>
                                 </a>
                             </div>
                             <div class="menu-item">
                                 <a class="menu-link"
                                     href="{{ route('workflowapproval.index', ['module' => 'pegawai']) }}">
                                     <span class="menu-bullet">
                                         <span class="bullet bullet-dot"></span>
                                     </span>
                                     <span class="menu-title">Pegawai</span>
                                 </a>
                             </div>
                             <div class="menu-item">
                                 <a class="menu-link"
                                     href="{{ route('workflowapproval.index', ['module' => 'persediaan']) }}">
                                     <span class="menu-bullet">
                                         <span class="bullet bullet-dot"></span>
                                     </span>
                                     <span class="menu-title">Persediaan</span>
                                 </a>
                             </div>
                             <div class="menu-item">
                                 <a class="menu-link"
                                     href="{{ route('workflowapproval.index', ['module' => 'modal']) }}">
                                     <span class="menu-bullet">
                                         <span class="bullet bullet-dot"></span>
                                     </span>
                                     <span class="menu-title">Modal</span>
                                 </a>
                             </div>
                             <div class="menu-item">
                                 <a class="menu-link"
                                     href="{{ route('workflowapproval.index', ['module' => 'modal-kantor']) }}">
                                     <span class="menu-bullet">
                                         <span class="bullet bullet-dot"></span>
                                     </span>
                                     <span class="menu-title">Modal Kantor</span>
                                 </a>
                             </div>
                             <div class="menu-item">
                                 <a class="menu-link"
                                     href="{{ route('workflowapproval.index', ['module' => 'modal-alkes']) }}">
                                     <span class="menu-bullet">
                                         <span class="bullet bullet-dot"></span>
                                     </span>
                                     <span class="menu-title">Modal Alkes</span>
                                 </a>
                             </div>
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen009.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path opacity="0.3"
                                         d="M21 22H14C13.4 22 13 21.6 13 21V3C13 2.4 13.4 2 14 2H21C21.6 2 22 2.4 22 3V21C22 21.6 21.6 22 21 22Z"
                                         fill="black" />
                                     <path
                                         d="M10 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H10C10.6 2 11 2.4 11 3V21C11 21.6 10.6 22 10 22Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Approval Belanja</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('usulan-belanja.validasi.index') }}">
                                 <span class="menu-bullet">
                                     <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Validasi Usulan Belanja</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 @endif
                 <div class="menu-item">
                     <div class="menu-content pt-8 pb-2">
                         <span class="menu-section text-muted text-uppercase fs-8 ls-1">Belanja</span>
                     </div>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="{{ route('pendapatan.index') }}">
                         <span class="menu-icon"><i class="fas fa-money-bill-wave fs-4"></i></span>
                         <span class="menu-title">Keuangan Pendapatan</span>
                     </a>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="{{ route('usulan-belanja.rekap') }}">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Rekap Usulan Belanja</span>
                     </a>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/abstract/abs042.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M18 21.6C16.6 20.4 9.1 20.3 6.3 21.2C5.7 21.4 5.1 21.2 4.7 20.8L2 18C4.2 15.8 10.8 15.1 15.8 15.8C16.2 18.3 17 20.5 18 21.6ZM18.8 2.8C18.4 2.4 17.8 2.20001 17.2 2.40001C14.4 3.30001 6.9 3.2 5.5 2C6.8 3.3 7.4 5.5 7.7 7.7C9 7.9 10.3 8 11.7 8C15.8 8 19.8 7.2 21.5 5.5L18.8 2.8Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M21.2 17.3C21.4 17.9 21.2 18.5 20.8 18.9L18 21.6C15.8 19.4 15.1 12.8 15.8 7.8C18.3 7.4 20.4 6.70001 21.5 5.60001C20.4 7.00001 20.2 14.5 21.2 17.3ZM8 11.7C8 9 7.7 4.2 5.5 2L2.8 4.8C2.4 5.2 2.2 5.80001 2.4 6.40001C2.7 7.40001 3.00001 9.2 3.10001 11.7C3.10001 15.5 2.40001 17.6 2.10001 18C3.20001 16.9 5.3 16.2 7.8 15.8C8 14.2 8 12.7 8 11.7Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Data SPJ</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('usulan-belanja.spj.index') }}">
                                 <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                 <span class="menu-title">SPJ dari Usulan Belanja</span>
                             </a>
                         </div>

                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('usulan-belanja.spj.providers') }}">
                                 <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                 <span class="menu-title">SPJ per Penyedia</span>
                             </a>
                         </div>
                     </div>
                 </div>
                 <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                     <span class="menu-link">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen009.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path opacity="0.3"
                                         d="M21 22H14C13.4 22 13 21.6 13 21V3C13 2.4 13.4 2 14 2H21C21.6 2 22 2.4 22 3V21C22 21.6 21.6 22 21 22Z"
                                         fill="black" />
                                     <path
                                         d="M10 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H10C10.6 2 11 2.4 11 3V21C11 21.6 10.6 22 10 22Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Data Pengadaan</span>
                         <span class="menu-arrow"></span>
                     </span>
                     <div class="menu-sub menu-sub-accordion menu-active-bg">
                         <div class="menu-item">
                             <a class="menu-link" href="{{ route('perusahaans.index') }}">
                                 <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                 <span class="menu-title">Data Perusahaan Penyedia</span>
                             </a>
                         </div>
                         @if (auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'))
                             <div class="menu-item">
                                 <a class="menu-link" href="{{ route('usulan-belanja.pengadaan.assignments') }}">
                                     <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                     <span class="menu-title">Penugasan Pendukung PPK</span>
                                 </a>
                             </div>
                         @endif
                     </div>
                 </div>

                 <div class="menu-item">
                     <div class="menu-content">
                         <div class="separator mx-1 my-4"></div>
                     </div>
                 </div>
                 <div class="menu-item">
                     <a class="menu-link" href="../../demo1/dist/documentation/getting-started/changelog.html">
                         <span class="menu-icon">
                             <!--begin::Svg Icon | path: icons/duotune/coding/cod003.svg-->
                             <span class="svg-icon svg-icon-2">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </span>
                         <span class="menu-title">Changelog v8.0.25sfsf</span>
                     </a>
                 </div>
             </div>
             <!--end::Menu-->
         </div>
         <!--end::Aside Menu-->
     </div>
     <!--end::Aside menu-->
     <!--begin::Footer-->
     <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
         <a href="../../demo1/dist/documentation/getting-started.html" class="btn btn-custom btn-primary w-100"
             data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
             title="200+ in-house components and 3rd-party plugins">
             <span class="btn-label">Docs &amp; Components</span>
             <!--begin::Svg Icon | path: icons/duotune/general/gen005.svg-->
             <span class="svg-icon btn-icon svg-icon-2">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none">
                     <path opacity="0.3"
                         d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM15 17C15 16.4 14.6 16 14 16H8C7.4 16 7 16.4 7 17C7 17.6 7.4 18 8 18H14C14.6 18 15 17.6 15 17ZM17 12C17 11.4 16.6 11 16 11H8C7.4 11 7 11.4 7 12C7 12.6 7.4 13 8 13H16C16.6 13 17 12.6 17 12ZM17 7C17 6.4 16.6 6 16 6H8C7.4 6 7 6.4 7 7C7 7.6 7.4 8 8 8H16C16.6 8 17 7.6 17 7Z"
                         fill="black" />
                     <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black" />
                 </svg>
             </span>
             <!--end::Svg Icon-->
         </a>
     </div>
     <!--end::Footer-->
 </div>
