<!doctype html>
<html lang="en">
@include('layouts.head')

<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
    style="
      --kt-toolbar-height: 55px;
      --kt-toolbar-height-tablet-and-mobile: 55px;">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
        </div>
    </div>
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            @include('layouts.sidebar')
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('layouts.header')
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    @include('layouts.toolsbar')
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <!--begin::Container-->
                        {{-- @include('layouts.content') --}}
                        <div id="kt_content_container" class="container-xxl">
                            @yield('content')
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Post-->
                </div>
                <!--end::Content-->
                @include('layouts.footer')
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    @include('layouts.modals')
    @include('layouts.scrolling')
    <!--end::Main-->
    @include('layouts.jquery')
    @include('layouts.js')
    @include('layouts.js_rkbu')
    @include('layouts.js_workflow')
    @stack('scripts')

</body>
<!--end::Body-->

</html>
