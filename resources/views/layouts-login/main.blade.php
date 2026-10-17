<!DOCTYPE html>
<html lang="en">
@include('layouts-login.head')

<body id="kt_body" class="bg-body">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            >

            @yield('container')

            @include('layouts-login.footer')
        </div>
    </div>

    @include('layouts-login.jquery')
    @include('layouts-login.js')
</body>

</html>
