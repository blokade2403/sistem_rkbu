<!DOCTYPE html>
<html lang="id">
@include('layouts-login.head')

<body id="kt_body">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            >

            @yield('container')

        </div>
    </div>

    @include('layouts-login.jquery')
    @include('layouts-login.js')
</body>

</html>
