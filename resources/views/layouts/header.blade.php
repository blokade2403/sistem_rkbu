 <style>
     .approval-notification-menu { width: min(375px, calc(100vw - 24px)); border-radius: 16px !important; }
     .approval-notification-item { border: 1px solid #edf0f5; background: #fff; text-decoration: none; transition: border-color .15s ease, background-color .15s ease, transform .15s ease; }
     .approval-notification-item:hover { border-color: #cbdcff; background: #f6f9ff; transform: translateY(-1px); }
     .header-user-initials { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #fff; border-radius: 13px; color: #fff; background: linear-gradient(145deg, #3987f6, #1749a5); box-shadow: 0 4px 12px rgba(36, 99, 211, .2); font-size: 13px; font-weight: 800; letter-spacing: .04em; }
     .header-user-initials.header-user-initials-lg { width: 50px; height: 50px; border-radius: 16px; font-size: 16px; }
 </style>
 @php
     $headerUser = auth()->user();
     $headerName = trim($headerUser?->nama ?: $headerUser?->username ?: 'User');
     $headerNameParts = preg_split('/\s+/u', $headerName, -1, PREG_SPLIT_NO_EMPTY);
     $headerInitials = mb_strtoupper(mb_substr($headerNameParts[0] ?? 'U', 0, 1) . (count($headerNameParts) > 1 ? mb_substr(end($headerNameParts), 0, 1) : ''));
 @endphp
 <!--begin::Header-->
 <div id="kt_header" style="" class="header align-items-stretch">
     <!--begin::Container-->
     <div class="container-fluid d-flex align-items-stretch justify-content-between">
         <!--begin::Aside mobile toggle-->
         <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
             <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                 id="kt_aside_mobile_toggle">
                 <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                 <span class="svg-icon svg-icon-2x mt-1">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none">
                         <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                             fill="black" />
                         <path opacity="0.3"
                             d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                             fill="black" />
                     </svg>
                 </span>
                 <!--end::Svg Icon-->
             </div>
         </div>
         <!--end::Aside mobile toggle-->
         <!--begin::Mobile logo-->
         <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
             <a href="../../demo1/dist/index.html" class="d-lg-none">
                 <img alt="Logo" src="assets/media/logos/logo-2.svg" class="h-30px" />
             </a>
         </div>
         <!--end::Mobile logo-->
         <!--begin::Wrapper-->
         <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
             <!--begin::Navbar-->
             <div class="d-flex align-items-stretch" id="kt_header_nav">
                 <!--begin::Menu wrapper-->
                 <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
                     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                     data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end"
                     data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true"
                     data-kt-swapper-mode="prepend"
                     data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                     <!--begin::Menu-->
                     <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch"
                         id="#kt_header_menu" data-kt-menu="true">
                         <div class="menu-item me-lg-1">
                             <a class="menu-link active py-3" href="#">
                                 <span class="menu-title">Dashboard2</span>
                             </a>
                         </div>
                         <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
                             class="menu-item menu-lg-down-accordion me-lg-1">
                             <span class="menu-link py-3">
                                 <span class="menu-title">Layouts</span>
                                 <span class="menu-arrow d-lg-none"></span>
                             </span>
                             <div
                                 class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
                                 <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                     data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                     <span class="menu-link py-3">
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
                                         <span class="menu-title">Toolbars</span>
                                         <span class="menu-arrow"></span>
                                     </span>
                                     <div
                                         class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                         <div class="menu-item">
                                             <a class="menu-link py-3"
                                                 href="../../demo1/dist/layouts/toolbars/toolbar-1.html">
                                                 <span class="menu-bullet">
                                                     <span class="bullet bullet-dot"></span>
                                                 </span>
                                                 <span class="menu-title">Toolbar 1</span>
                                             </a>
                                         </div>
                                         <div class="menu-item">
                                             <a class="menu-link py-3"
                                                 href="../../demo1/dist/layouts/toolbars/toolbar-2.html">
                                                 <span class="menu-bullet">
                                                     <span class="bullet bullet-dot"></span>
                                                 </span>
                                                 <span class="menu-title">Toolbar 2</span>
                                             </a>
                                         </div>
                                     </div>
                                 </div>
                                 <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                     data-kt-menu-placement="right-start" class="menu-item menu-lg-dropdown">
                                     <span class="menu-link py-3">
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
                                         <span class="menu-title">Aside</span>
                                         <span class="menu-arrow"></span>
                                     </span>
                                     <div
                                         class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                         <div class="menu-item">
                                             <a class="menu-link py-3" href="../../demo1/dist/layouts/aside/light.html">
                                                 <span class="menu-bullet">
                                                     <span class="bullet bullet-dot"></span>
                                                 </span>
                                                 <span class="menu-title">Light Skin</span>
                                             </a>
                                         </div>
                                         <div class="menu-item">
                                             <a class="menu-link py-3"
                                                 href="../../demo1/dist/layouts/aside/font-icons.html">
                                                 <span class="menu-bullet">
                                                     <span class="bullet bullet-dot"></span>
                                                 </span>
                                                 <span class="menu-title">Font Icons</span>
                                             </a>
                                         </div>
                                         <div class="menu-item">
                                             <a class="menu-link py-3"
                                                 href="../../demo1/dist/layouts/aside/minimized.html">
                                                 <span class="menu-bullet">
                                                     <span class="bullet bullet-dot"></span>
                                                 </span>
                                                 <span class="menu-title">Minimized</span>
                                             </a>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
                             class="menu-item menu-lg-down-accordion me-lg-1">
                             <span class="menu-link py-3">
                                 <span class="menu-title">Resources</span>
                                 <span class="menu-arrow d-lg-none"></span>
                             </span>
                             <div
                                 class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
                                 <div class="menu-item">
                                     <a class="menu-link py-3"
                                         href="../../demo1/dist/documentation/base/utilities.html"
                                         title="Check out over 200 in-house components, plugins and ready for use solutions"
                                         data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                                         data-bs-placement="right">
                                         <span class="menu-icon">
                                             <!--begin::Svg Icon | path: /icons/duotune/general/gen002.svg-->
                                             <span class="svg-icon svg-icon-2">
                                                 <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                     height="24" viewBox="0 0 24 24" fill="none">
                                                     <path opacity="0.3"
                                                         d="M4.05424 15.1982C8.34524 7.76818 13.5782 3.26318 20.9282 2.01418C21.0729 1.98837 21.2216 1.99789 21.3618 2.04193C21.502 2.08597 21.6294 2.16323 21.7333 2.26712C21.8372 2.37101 21.9144 2.49846 21.9585 2.63863C22.0025 2.7788 22.012 2.92754 21.9862 3.07218C20.7372 10.4222 16.2322 15.6552 8.80224 19.9462L4.05424 15.1982ZM3.81924 17.3372L2.63324 20.4482C2.58427 20.5765 2.5735 20.7163 2.6022 20.8507C2.63091 20.9851 2.69788 21.1082 2.79503 21.2054C2.89218 21.3025 3.01536 21.3695 3.14972 21.3982C3.28408 21.4269 3.42387 21.4161 3.55224 21.3672L6.66524 20.1802L3.81924 17.3372ZM16.5002 5.99818C16.2036 5.99818 15.9136 6.08615 15.6669 6.25097C15.4202 6.41579 15.228 6.65006 15.1144 6.92415C15.0009 7.19824 14.9712 7.49984 15.0291 7.79081C15.0869 8.08178 15.2298 8.34906 15.4396 8.55884C15.6494 8.76862 15.9166 8.91148 16.2076 8.96935C16.4986 9.02723 16.8002 8.99753 17.0743 8.884C17.3484 8.77046 17.5826 8.5782 17.7474 8.33153C17.9123 8.08486 18.0002 7.79485 18.0002 7.49818C18.0002 7.10035 17.8422 6.71882 17.5609 6.43752C17.2796 6.15621 16.8981 5.99818 16.5002 5.99818Z"
                                                         fill="black" />
                                                     <path
                                                         d="M4.05423 15.1982L2.24723 13.3912C2.15505 13.299 2.08547 13.1867 2.04395 13.0632C2.00243 12.9396 1.9901 12.8081 2.00793 12.679C2.02575 12.5498 2.07325 12.4266 2.14669 12.3189C2.22013 12.2112 2.31752 12.1219 2.43123 12.0582L9.15323 8.28918C7.17353 10.3717 5.4607 12.6926 4.05423 15.1982ZM8.80023 19.9442L10.6072 21.7512C10.6994 21.8434 10.8117 21.9129 10.9352 21.9545C11.0588 21.996 11.1903 22.0083 11.3195 21.9905C11.4486 21.9727 11.5718 21.9252 11.6795 21.8517C11.7872 21.7783 11.8765 21.6809 11.9402 21.5672L15.7092 14.8442C13.6269 16.8245 11.3061 18.5377 8.80023 19.9442ZM7.04023 18.1832L12.5832 12.6402C12.7381 12.4759 12.8228 12.2577 12.8195 12.032C12.8161 11.8063 12.725 11.5907 12.5653 11.4311C12.4057 11.2714 12.1901 11.1803 11.9644 11.1769C11.7387 11.1736 11.5205 11.2583 11.3562 11.4132L5.81323 16.9562L7.04023 18.1832Z"
                                                         fill="black" />
                                                 </svg>
                                             </span>
                                             <!--end::Svg Icon-->
                                         </span>
                                         <span class="menu-title">Components</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link py-3"
                                         href="../../demo1/dist/documentation/getting-started.html"
                                         title="Check out the complete documentation" data-bs-toggle="tooltip"
                                         data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                         <span class="menu-icon">
                                             <!--begin::Svg Icon | path: icons/duotune/abstract/abs027.svg-->
                                             <span class="svg-icon svg-icon-2">
                                                 <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                     height="24" viewBox="0 0 24 24" fill="none">
                                                     <path opacity="0.3"
                                                         d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
                                                         fill="black" />
                                                     <path
                                                         d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
                                                         fill="black" />
                                                 </svg>
                                             </span>
                                             <!--end::Svg Icon-->
                                         </span>
                                         <span class="menu-title">Documentation</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link py-3"
                                         href="https://preview.keenthemes.com/metronic8/demo1/layout-builder.html"
                                         title="Build your layout, preview and export HTML for server side integration"
                                         data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                                         data-bs-placement="right">
                                         <span class="menu-icon">
                                             <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                             <span class="svg-icon svg-icon-2">
                                                 <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                     height="24" viewBox="0 0 24 24" fill="none">
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
                                         <span class="menu-title">Layout Builder</span>
                                     </a>
                                 </div>
                                 <div class="menu-item">
                                     <a class="menu-link py-3"
                                         href="../../demo1/dist/documentation/getting-started/changelog.html">
                                         <span class="menu-icon">
                                             <!--begin::Svg Icon | path: icons/duotune/coding/cod003.svg-->
                                             <span class="svg-icon svg-icon-2">
                                                 <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                     height="24" viewBox="0 0 24 24" fill="none">
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
                                         <span class="menu-title">Changelog v8.0.25</span>
                                     </a>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!--end::Menu-->
                 </div>
                 <!--end::Menu wrapper-->
             </div>
             <!--end::Navbar-->
             <!--begin::Topbar-->
             <div class="d-flex align-items-stretch flex-shrink-0">
                 <!--begin::Toolbar wrapper-->
                 <div class="d-flex align-items-stretch flex-shrink-0">
                     <!--begin::Notifications-->
                     <div class="d-flex align-items-center ms-1 ms-lg-3">
                         <!--begin::Menu- wrapper-->
                         <div class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px rounded-circle"
                             data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                             data-kt-menu-placement="bottom-end" aria-label="Notifikasi approval" title="Notifikasi approval">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen022.svg-->
                             <i class="fas fa-bell fs-3 text-gray-700"></i>
                             <!--end::Svg Icon-->
                             @if (($approvalNotificationTotal ?? 0) > 0)
                                 <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger fs-9">{{ $approvalNotificationTotal > 99 ? '99+' : $approvalNotificationTotal }}</span>
                             @endif
                         </div>
                         <!--begin::Menu-->
                         <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px approval-notification-menu shadow-lg border-0 overflow-hidden"
                             data-kt-menu="true">
                             <!--begin::Heading-->
                             <div class="px-6 py-5 bg-light-primary border-bottom">
                                 <div class="d-flex align-items-center justify-content-between">
                                     <div>
                                         <h3 class="fw-bold text-gray-900 mb-1">Notifikasi Approval</h3>
                                         <div class="text-muted fs-7">Daftar tugas yang menunggu tindakan Anda</div>
                                     </div>
                                     <span class="badge badge-primary rounded-pill px-3 py-2">{{ $approvalNotificationTotal ?? 0 }}</span>
                                 </div>
                             </div>
                             <!--end::Heading-->
                             <div class="approval-notification-list p-4">
                                 @forelse ($approvalNotifications ?? [] as $notification)
                                     <a href="{{ $notification['url'] }}" class="approval-notification-item d-flex align-items-center gap-3 p-4 mb-2 rounded-3 text-gray-800 text-hover-primary">
                                         <span class="symbol symbol-45px symbol-circle flex-shrink-0"><span class="symbol-label bg-light-primary"><i class="ki-duotone {{ $notification['icon'] }} fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i></span></span>
                                         <span class="d-flex flex-column flex-grow-1 min-w-0">
                                             <span class="fw-bold text-gray-900 text-hover-primary">{{ $notification['label'] }}</span>
                                             <span class="text-muted fs-7 mt-1">{{ $notification['count'] }} tugas menunggu</span>
                                         </span>
                                         <span class="badge badge-light-primary rounded-pill px-3 py-2">{{ $notification['count'] }}</span>
                                     </a>
                                 @empty
                                     <div class="text-center py-8 px-4">
                                         <span class="symbol symbol-60px symbol-circle mx-auto mb-4"><span class="symbol-label bg-light-success"><i class="ki-duotone ki-check-circle fs-2x text-success"><span class="path1"></span><span class="path2"></span></i></span></span>
                                         <div class="fw-bold text-gray-800 mb-1">Semua sudah diperiksa</div>
                                         <div class="text-muted fs-7">Tidak ada tugas approval yang menunggu.</div>
                                     </div>
                                 @endforelse
                             </div>

                         </div>
                         <!--end::Menu-->
                         <!--end::Menu wrapper-->
                     </div>
                     <!--end::Notifications-->
                     <!--begin::Quick links-->
                     <div class="d-flex align-items-center ms-1 ms-lg-3">
                         <!--begin::Menu wrapper-->
                         <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                             data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                             data-kt-menu-placement="bottom-end">
                             <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                             <span class="svg-icon svg-icon-1">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <rect x="2" y="2" width="9" height="9" rx="2"
                                         fill="black" />
                                     <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                         fill="black" />
                                     <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                         fill="black" />
                                     <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </div>
                         <!--begin::Menu-->
                         <div class="menu menu-sub menu-sub-dropdown menu-column w-250px w-lg-325px"
                             data-kt-menu="true">
                             <!--begin::Heading-->
                             <div class="d-flex flex-column flex-center bgi-no-repeat rounded-top px-9 py-10"
                                 style="background-image: url(&quot;assets/media/misc/pattern-1.jpg&quot;);">
                                 <!--begin::Title-->
                                 <h3 class="text-white fw-bold mb-3">Quick Links</h3>
                                 <!--end::Title-->
                                 <!--begin::Status-->
                                 <span class="badge bg-primary py-2 px-3">25 pending tasks</span>
                                 <!--end::Status-->
                             </div>
                             <!--end::Heading-->
                             <!--begin:Nav-->
                             <div class="row g-0">
                                 <!--begin:Item-->
                                 <div class="col-6">
                                     <a href="../../demo1/dist/pages/projects/budget.html"
                                         class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-end border-bottom">
                                         <!--begin::Svg Icon | path: icons/duotune/finance/fin009.svg-->
                                         <span class="svg-icon svg-icon-3x svg-icon-primary mb-2">
                                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none">
                                                 <path opacity="0.3"
                                                     d="M15.8 11.4H6C5.4 11.4 5 11 5 10.4C5 9.80002 5.4 9.40002 6 9.40002H15.8C16.4 9.40002 16.8 9.80002 16.8 10.4C16.8 11 16.3 11.4 15.8 11.4ZM15.7 13.7999C15.7 13.1999 15.3 12.7999 14.7 12.7999H6C5.4 12.7999 5 13.1999 5 13.7999C5 14.3999 5.4 14.7999 6 14.7999H14.8C15.3 14.7999 15.7 14.2999 15.7 13.7999Z"
                                                     fill="black" />
                                                 <path
                                                     d="M18.8 15.5C18.9 15.7 19 15.9 19.1 16.1C19.2 16.7 18.7 17.2 18.4 17.6C17.9 18.1 17.3 18.4999 16.6 18.7999C15.9 19.0999 15 19.2999 14.1 19.2999C13.4 19.2999 12.7 19.2 12.1 19.1C11.5 19 11 18.7 10.5 18.5C10 18.2 9.60001 17.7999 9.20001 17.2999C8.80001 16.8999 8.49999 16.3999 8.29999 15.7999C8.09999 15.1999 7.80001 14.7 7.70001 14.1C7.60001 13.5 7.5 12.8 7.5 12.2C7.5 11.1 7.7 10.1 8 9.19995C8.3 8.29995 8.79999 7.60002 9.39999 6.90002C9.99999 6.30002 10.7 5.8 11.5 5.5C12.3 5.2 13.2 5 14.1 5C15.2 5 16.2 5.19995 17.1 5.69995C17.8 6.09995 18.7 6.6 18.8 7.5C18.8 7.9 18.6 8.29998 18.3 8.59998C18.2 8.69998 18.1 8.69993 18 8.79993C17.7 8.89993 17.4 8.79995 17.2 8.69995C16.7 8.49995 16.5 7.99995 16 7.69995C15.5 7.39995 14.9 7.19995 14.2 7.19995C13.1 7.19995 12.1 7.6 11.5 8.5C10.9 9.4 10.5 10.6 10.5 12.2C10.5 13.3 10.7 14.2 11 14.9C11.3 15.6 11.7 16.1 12.3 16.5C12.9 16.9 13.5 17 14.2 17C15 17 15.7 16.8 16.2 16.4C16.8 16 17.2 15.2 17.9 15.1C18 15 18.5 15.2 18.8 15.5Z"
                                                     fill="black" />
                                             </svg>
                                         </span>
                                         <!--end::Svg Icon-->
                                         <span class="fs-5 fw-bold text-gray-800 mb-0">Accounting</span>
                                         <span class="fs-7 text-gray-400">eCommerce</span>
                                     </a>
                                 </div>
                                 <!--end:Item-->
                                 <!--begin:Item-->
                                 <div class="col-6">
                                     <a href="../../demo1/dist/pages/projects/settings.html"
                                         class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-bottom">
                                         <!--begin::Svg Icon | path: icons/duotune/communication/com010.svg-->
                                         <span class="svg-icon svg-icon-3x svg-icon-primary mb-2">
                                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none">
                                                 <path
                                                     d="M6 8.725C6 8.125 6.4 7.725 7 7.725H14L18 11.725V12.925L22 9.725L12.6 2.225C12.2 1.925 11.7 1.925 11.4 2.225L2 9.725L6 12.925V8.725Z"
                                                     fill="black" />
                                                 <path opacity="0.3"
                                                     d="M22 9.72498V20.725C22 21.325 21.6 21.725 21 21.725H3C2.4 21.725 2 21.325 2 20.725V9.72498L11.4 17.225C11.8 17.525 12.3 17.525 12.6 17.225L22 9.72498ZM15 11.725H18L14 7.72498V10.725C14 11.325 14.4 11.725 15 11.725Z"
                                                     fill="black" />
                                             </svg>
                                         </span>
                                         <!--end::Svg Icon-->
                                         <span class="fs-5 fw-bold text-gray-800 mb-0">Administration</span>
                                         <span class="fs-7 text-gray-400">Console</span>
                                     </a>
                                 </div>
                                 <!--end:Item-->
                                 <!--begin:Item-->
                                 <div class="col-6">
                                     <a href="../../demo1/dist/pages/projects/list.html"
                                         class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-end">
                                         <!--begin::Svg Icon | path: icons/duotune/abstract/abs042.svg-->
                                         <span class="svg-icon svg-icon-3x svg-icon-primary mb-2">
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
                                         <span class="fs-5 fw-bold text-gray-800 mb-0">Projects</span>
                                         <span class="fs-7 text-gray-400">Pending Tasks</span>
                                     </a>
                                 </div>
                                 <!--end:Item-->
                                 <!--begin:Item-->
                                 <div class="col-6">
                                     <a href="../../demo1/dist/pages/projects/users.html"
                                         class="d-flex flex-column flex-center h-100 p-6 bg-hover-light">
                                         <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                         <span class="svg-icon svg-icon-3x svg-icon-primary mb-2">
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
                                         <span class="fs-5 fw-bold text-gray-800 mb-0">Customers</span>
                                         <span class="fs-7 text-gray-400">Latest cases</span>
                                     </a>
                                 </div>
                                 <!--end:Item-->
                             </div>
                             <!--end:Nav-->
                             <!--begin::View more-->
                             <div class="py-2 text-center border-top">
                                 <a href="../../demo1/dist/pages/profile/activity.html"
                                     class="btn btn-color-gray-600 btn-active-color-primary">View All
                                     <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                     <span class="svg-icon svg-icon-5">
                                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
                                             <rect opacity="0.5" x="18" y="13" width="13" height="2"
                                                 rx="1" transform="rotate(-180 18 13)" fill="black" />
                                             <path
                                                 d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                 fill="black" />
                                         </svg>
                                     </span>
                                     <!--end::Svg Icon--></a>
                             </div>
                             <!--end::View more-->
                         </div>
                         <!--end::Menu-->
                         <!--end::Menu wrapper-->
                     </div>
                     <!--end::Quick links-->
                     <!--begin::User-->
                     <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                         <!--begin::Menu wrapper-->
                         <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click"
                             data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                             <span class="header-user-initials" aria-label="Inisial {{ $headerName }}">{{ $headerInitials }}</span>
                         </div>
                         <!--begin::Menu-->
                         <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                             data-kt-menu="true">
                             <!--begin::Menu item-->
                             <div class="menu-item px-3">
                                 <div class="menu-content d-flex align-items-center px-3">
                                     <!--begin::Avatar-->
                                     <div class="symbol symbol-50px me-5">
                                         <span class="header-user-initials header-user-initials-lg" aria-label="Inisial {{ $headerName }}">{{ $headerInitials }}</span>
                                     </div>
                                     <!--end::Avatar-->
                                     <!--begin::Username-->
                                     <div class="d-flex flex-column">
                                         <div class="fw-bolder d-flex align-items-center fs-5">
                                             {{ $headerUser?->nama ?? $headerUser?->username ?? 'Pengguna' }}
                                         </div>
                                         <a href="{{ route('profile.edit') }}"
                                             class="fw-bold text-muted text-hover-primary fs-7">{{ $headerUser?->email ?? $headerUser?->username }}</a>
                                     </div>
                                     <!--end::Username-->
                                 </div>
                             </div>
                             <!--end::Menu item-->
                             <!--begin::Menu separator-->
                             <div class="separator my-2"></div>
                             <!--end::Menu separator-->
                             <!--begin::Menu item-->
                             <div class="menu-item px-5">
                                 <a href="{{ route('profile.edit') }}" class="menu-link px-5">Profil Saya</a>
                             </div>
                             <!--begin::Menu separator-->
                             <div class="separator my-2"></div>
                             <!--end::Menu separator-->
                             <!--end::Menu item-->
                             <!--begin::Menu item-->
                             <div class="menu-item px-5">
                                 <form method="POST" action="{{ route('logout') }}">
                                     @csrf
                                     <button type="submit"
                                         class="menu-link px-5 border-0 bg-transparent w-100 text-start">
                                         Sign Out
                                     </button>
                                 </form>
                             </div>
                             <!--end::Menu item-->
                             <!--begin::Menu separator-->
                             <div class="separator my-2"></div>
                             <!--end::Menu separator-->
                             <!--begin::Menu item-->
                             <div class="menu-item px-5">
                                 <div class="menu-content px-5">
                                     <label
                                         class="form-check form-switch form-check-custom form-check-solid pulse pulse-success"
                                         for="kt_user_menu_dark_mode_toggle">
                                         <input class="form-check-input w-30px h-20px" type="checkbox" value="1"
                                             name="mode" id="kt_user_menu_dark_mode_toggle"
                                             data-kt-url="../../demo1/dist/index.html" />
                                         <span class="pulse-ring ms-n1"></span>
                                         <span class="form-check-label text-gray-600 fs-7">Dark
                                             Mode</span>
                                     </label>
                                 </div>
                             </div>
                             <!--end::Menu item-->
                         </div>
                         <!--end::Menu-->
                         <!--end::Menu wrapper-->
                     </div>
                     <!--end::User -->
                     <!--begin::Heaeder menu toggle-->
                     <div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
                         <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                             id="kt_header_menu_mobile_toggle">
                             <!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
                             <span class="svg-icon svg-icon-1">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none">
                                     <path
                                         d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z"
                                         fill="black" />
                                     <path opacity="0.3"
                                         d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z"
                                         fill="black" />
                                 </svg>
                             </span>
                             <!--end::Svg Icon-->
                         </div>
                     </div>
                     <!--end::Heaeder menu toggle-->
                 </div>
                 <!--end::Toolbar wrapper-->
             </div>
             <!--end::Topbar-->
         </div>
         <!--end::Wrapper-->
     </div>
     <!--end::Container-->
 </div>
 <!--end::Header-->
