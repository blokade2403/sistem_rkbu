@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            `,
            confirmButtonColor: '#009ef7'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ session('error') }}',
            confirmButtonColor: '#009ef7'
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonColor: '#009ef7',
            timer: 1500,
            showConfirmButton: false
        });
    </script>
@endif
