 <script>
     $(document).ready(function() {

         let table = $('#kt_customers_table').DataTable({
             pageLength: 5,
             searching: true,
             paging: true,
             ordering: true,
             info: true,
             lengthChange: false,

             // HILANGKAN SEARCH DEFAULT DATATABLES
             dom: 'rtip',

             language: {
                 paginate: {
                     previous: "Prev",
                     next: "Next"
                 }
             }
         });

         // Custom Search
         $('#customSearch').on('keyup', function() {
             table.search(this.value).draw();
         });

         // Custom Show Entries
         $('#customLength').on('change', function() {
             table.page.len(this.value).draw();
         });

     });
 </script>

 @if (session('success'))
     <script>
         Swal.fire({
             icon: 'success',
             title: 'Berhasil',
             text: '{{ session('success') }}',
             showConfirmButton: false,
             timer: 2500
         });
     </script>
 @endif

 @if (session('error'))
     <script>
         Swal.fire({
             icon: 'error',
             title: 'Gagal',
             text: '{{ session('error') }}',
             showConfirmButton: false,
             timer: 2500
         });
     </script>
 @endif

 @if ($errors->any())
     <script>
         Swal.fire({
             icon: 'warning',
             title: 'Validasi Error',
             html: `
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            `,
         });
     </script>
 @endif

 <script>
     document.querySelectorAll('.form-delete').forEach(form => {

         form.addEventListener('submit', function(e) {

             e.preventDefault();

             Swal.fire({
                 title: 'Yakin hapus data?',
                 text: "Data yang dihapus tidak bisa dikembalikan!",
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#d33',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: 'Ya, Hapus!',
                 cancelButtonText: 'Batal'
             }).then((result) => {

                 if (result.isConfirmed) {
                     form.submit();
                 }

             });

         });

     });
 </script>

 <script>
    document.querySelectorAll('.form-submit').forEach(form => {

        form.addEventListener('submit', function(e) {

            e.preventDefault();

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Pastikan data yang anda input sudah benar",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#009ef7',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Data sedang diproses',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    form.submit();
                }

            });

        });

    });
</script>

<script>
$(document).ready(function () {
    $('#kt_modal_new_target').on('shown.bs.modal', function () {
        $(this).find('select[data-control="select2"]').each(function () {

            // Hancurkan jika sudah pernah diinisialisasi
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
            }

            $(this).select2({
                dropdownParent: $('#kt_modal_new_target'),
                width: '100%'
            });
        });
    });
});
</script>
