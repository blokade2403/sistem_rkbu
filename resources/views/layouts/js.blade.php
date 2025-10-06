<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (document.querySelectorAll("[data-choices]").length) {
            document.querySelectorAll("[data-choices]").forEach(function(el) {
                new Choices(el, {
                    searchEnabled: true,
                    removeItemButton: true
                });
            });
        }
    });
</script>

<script>
    $(function() {
        $('#example-movie').barrating({
            theme: 'bars-movie',
            initialRating: 3, // otomatis aktif "Prioritas 3"
            readonly: true // kalau mau cuma tampil, tidak bisa diubah user
        });
    });
</script>


<!-- SweetAlert2 -->
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#28a745'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc3545'
        });
    </script>
@endif


<script>
    // Format ke Rupiah saat user mengetik
    document.querySelectorAll('.currency').forEach(function(el) {
        el.addEventListener('keyup', function(e) {
            let value = this.value.replace(/\D/g, ""); // hanya angka
            let formatted = new Intl.NumberFormat('id-ID').format(value);
            this.value = formatted;
        });
    });

    // Saat submit form → hapus format, biar masuk DB angka murni
    document.querySelector('form').addEventListener('submit', function() {
        document.querySelectorAll('.currency').forEach(function(el) {
            el.value = el.value.replace(/\./g, ''); // hapus titik pemisah ribuan
        });
    });
</script>


<script>
    // Fungsi untuk layout detail custom
    // JS: inisialisasi DataTables + toggle child row pada tombol .details-btn
    $(document).ready(function() {
        // Pastikan DataTables sudah di-load (jQuery + datatables)
        var table = $('#manage-review-table').DataTable({
            responsive: false, // matikan responsive bawaan jika menimbulkan masalah
            // Jika Anda menggunakan DOM-sourced rows (seperti contoh), tidak perlu pass data options
        });

        // Helper: fungsi format, menerima data row (array dari cell HTML/text)
        function format(d) {
            // d adalah array (sumber dari DOM), kita bersihin HTML untuk ambil teks sederhana
            var productHtml = $('<div>').html(d[2]);
            var productText = productHtml.text().trim();
            var productImg = productHtml.find('img').attr('src') || '';

            var reviewerText = $('<div>').html(d[3]).text().trim();
            var reviewText = $('<div>').html(d[4]).text().trim();
            var dateText = $('<div>').html(d[5]).text().trim();
            var statusHtml = d[6]; // biarkan html (badge)
            var actionHtml = d[7];

            return `
            <div class="detail-wrapper">
                <div style="display:flex;gap:20px;align-items:flex-start;">
                    <div class="product-info">
                        ${ productImg ? `<img src="${productImg}" style="max-width:90px;border-radius:6px;">` : '' }
                        <div>
                            <h5 style="margin:0 0 6px 0;">${productText}</h5>
                            <p style="margin:0;"><strong>Reviewer:</strong> ${reviewerText}</p>
                        </div>
                    </div>
                    <div style="flex:1;">
                        <p style="margin:0 0 10px 0;"><strong>Review:</strong><br>${reviewText}</p>
                        <p style="margin:0;"><strong>Date:</strong> ${dateText} &nbsp;&nbsp; <strong>Status:</strong> ${statusHtml}</p>
                    </div>
                    <div style="min-width:110px;">
                        <p style="margin:0;"><strong>Action:</strong><br>${actionHtml}</p>
                    </div>
                </div>
            </div>`;
        }

        // Event: klik tombol details-btn untuk toggle child row
        $('#manage-review-table tbody').on('click', 'button.details-btn', function(e) {
            e.stopPropagation(); // supaya tidak memicu event klik row lain
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // hide
                row.child.hide();
                tr.removeClass('shown');
                $(this).text('+');
            } else {
                // show
                row.child(format(row.data())).show();
                tr.addClass('shown');
                $(this).text('−');
            }
        });

        // opsional: kalau mau tutup semua saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#manage-review-table').length) {
                table.rows().every(function() {
                    if (this.child.isShown()) {
                        this.child.hide();
                        $(this.node()).removeClass('shown');
                        $(this.node()).find('button.details-btn').text('+');
                    }
                });
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ], // Mengatur opsi panjang halaman
            "searching": true, // Mengaktifkan fitur pencarian
            "paging": true, // Mengaktifkan pagination
            "ordering": true, // Mengaktifkan pengurutan kolom
            "info": true, // Menampilkan informasi tentang jumlah data
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            paging: true,
            searching: true,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            autoWidth: false,
            responsive: true,
            dom:
                // baris atas
                "<'row mb-2'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
                // tabel
                "<'row'<'col-12'tr>>" +
                // baris bawah
                "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Semua tabel dengan class .datatable-modal otomatis jadi DataTables
        $('.datatable-modal').DataTable({
            paging: true,
            searching: true,
            info: true,
            lengthChange: true,
            pageLength: 5,
            autoWidth: false,
            responsive: true,
            dom: "<'row mb-3'<'col-md-6'l><'col-md-6'f>>" +
                "<'row'<'col-12'tr>>" +
                "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    next: "›",
                    previous: "‹"
                }
            }
        });


    });
</script>

<script>
    function formatCurrency(input) {
        // Ambil nilai input
        let value = input.value.replace(/[^0-9]/g, '');

        // Ubah ke format uang
        value = new Intl.NumberFormat('id-ID', {
            style: 'decimal',
            maximumFractionDigits: 0
        }).format(value);

        // Tampilkan nilai kembali ke input
        input.value = value;
    }

    function parseCurrency(value) {
        // Hapus format uang dan ubah menjadi angka
        return parseInt(value.replace(/[^0-9]/g, '') || 0);
    }

    function spj() {
        var txtFirstNumberValue = parseCurrency(document.getElementById('harga_dasar').value);
        var txtSecondNumberValue = parseCurrency(document.getElementById('ppn').value);
        var txtTigaNumberValue = parseCurrency(document.getElementById('pph22').value);
        var txtempatNumberValue = parseCurrency(document.getElementById('pph21').value);
        var txttujuhNumberValue = parseCurrency(document.getElementById('pph23').value);
        var txtdelapanNumberValue = parseCurrency(document.getElementById('pp05').value);
        var txtlimaNumberValue = parseCurrency(document.getElementById('bpjs_kes').value);
        var txtenamNumberValue = parseCurrency(document.getElementById('bpjs_tk').value);
        var txtsembilanNumberValue = parseCurrency(document.getElementById('bruto').value);
        var txtsepuluhNumberValue = parseCurrency(document.getElementById('pembayaran').value);

        var result1 = Math.round((txtFirstNumberValue * txtSecondNumberValue) / 100);
        document.getElementById('pajakppn').value = formatCurrencyOutput(result1);

        var result2 = Math.round((txtFirstNumberValue * txtTigaNumberValue) / 100);
        document.getElementById('pajakpph22').value = formatCurrencyOutput(result2);

        var result3 = Math.round((txtFirstNumberValue * txtempatNumberValue) / 100);
        document.getElementById('pajakpph21').value = formatCurrencyOutput(result3);

        var result4 = Math.round((txtFirstNumberValue * txttujuhNumberValue) / 100);
        document.getElementById('pajakpph23').value = formatCurrencyOutput(result4);

        var result5 = Math.round((txtFirstNumberValue * txtdelapanNumberValue) / 100);
        document.getElementById('pajakpp05').value = formatCurrencyOutput(result5);

        var result6 = result1 + result2 + result3 + result4 + result5;
        document.getElementById('jumlahpajak').value = formatCurrencyOutput(result6);

        var result7 = txtsembilanNumberValue - result6;
        document.getElementById('harga_bersih').value = formatCurrencyOutput(result7);

        var result8 = result7 + result6;
        document.getElementById('total_bruto').value = formatCurrencyOutput(result8);

        var result9 = txtsembilanNumberValue - txtsepuluhNumberValue;
        document.getElementById('sisa_pembayaran').value = formatCurrencyOutput(result9);
    }

    function formatCurrencyOutput(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'decimal',
            maximumFractionDigits: 0
        }).format(value);
    }
</script>

<script>
    function spj_pengadaan() {
        // Ambil nilai bruto (hilangkan semua non digit)
        var brutoRaw = document.getElementById('harga_bruto').value.replace(/\./g, '');
        var bruto = parseFloat(brutoRaw) || 0;

        // Ambil nilai PPN
        var ppn = parseFloat(document.getElementById('ppn_pengadaan').value) || 0;

        // Hitung harga dasar
        var result1 = bruto / (1 + (ppn / 100));

        if (!isNaN(result1)) {
            var roundedResult = Math.round(result1);

            // Format ke currency untuk input harga_dasar (readonly)
            document.getElementById('harga_dasar_pengadaan').value =
                new Intl.NumberFormat('id-ID').format(roundedResult);
        }
    }

    // Format currency saat ketik di bruto
    document.getElementById('harga_bruto').addEventListener('keyup', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(value);
        spj_pengadaan(); // otomatis hitung ulang
    });
</script>

<script type="text/javascript">
    // Fungsi untuk memformat angka dengan koma sebagai pemisah ribuan
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, ''); // Hanya ambil angka
        let formattedValue = new Intl.NumberFormat('id-ID').format(value); // Format ke angka dengan pemisah ribuan
        input.value = formattedValue; // Tampilkan angka terformat di input
    }

    function sum() {
        // Ambil nilai vol1 dan vol2
        var vol1 = parseFloat(document.getElementById('vol1').value) || 0;
        var vol2 = parseFloat(document.getElementById('vol2').value) || 0;

        // Ambil nilai harga_barang dan konversi dari string yang sudah diformat menjadi angka
        var hargaBarang = parseFloat(document.getElementById('harga_barang').value.replace(/\D/g, '')) || 0;

        // Ambil nilai persen PPN
        var persen = parseFloat(document.getElementById('persen').value) || 0;

        // Hitung PPN
        var ppn = ((vol1 * vol2) * hargaBarang) * persen / 100;

        // Hitung total dengan PPN
        var result = ((vol1 * vol2) * hargaBarang) + ppn;

        // Format hasil sebelum ditampilkan
        if (!isNaN(result)) {
            document.getElementById('hasil').value = new Intl.NumberFormat('id-ID').format(
                result); // Format angka dengan koma
        }
    }
</script>


<script>
    $(document).ready(function() {
        $('#example2').DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ], // Mengatur opsi panjang halaman
            "searching": true, // Mengaktifkan fitur pencarian
            "paging": true, // Mengaktifkan pagination
            "ordering": true, // Mengaktifkan pengurutan kolom
            "info": true, // Menampilkan informasi tentang jumlah data
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>

<script>
    document.getElementById('id_kegiatan').addEventListener('change', function() {
        var kegiatanId = this.value;

        if (kegiatanId) {
            fetch(`/get-program-details/${kegiatanId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('kode_program').textContent = data.kode_program;
                        document.getElementById('nama_program').textContent = data.nama_program;
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>


{{-- <script>
    document.write(new Date().getFullYear());
</script> --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<script>
    document.querySelectorAll('.profile-img-file-input').forEach((input, index) => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Update gambar preview sesuai urutan input
                    document.getElementById('preview-img-' + (index + 1)).src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>


{{-- <script type="text/javascript">
    $('#sub_kategori_rkbu').change(function() {
        var subKategoriId = $(this).val();

        // Mengirim AJAX request ke server
        $.ajax({
            url: "{{ route('rkbu_barang_jasas.getData') }}", // Perbaiki route di sini
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id_sub_kategori_rkbu: subKategoriId
            },
            success: function(response) {
                // Memasukkan data yang didapatkan ke input atau dropdown terkait
                $('#id_aktivitas').val(response.id_aktivitas);
                $('#id_sub_kegiatan').val(response.id_sub_kegiatan);
                $('#id_kegiatan').val(response.id_kegiatan);
                $('#id_program').val(response.id_program);
                $('#id_jenis_kategori_rkbu').val(response.id_jenis_kategori_rkbu);
                $('#id_kode_rekening_belanja').val(response.id_kode_rekening_belanja);
            },
            error: function(xhr, status, error) {
                console.error('Error: ', error);
                alert('Data tidak ditemukan atau terjadi kesalahan server.');
            }
        });
    });
</script> --}}


<script type="text/javascript">
    $(document).ready(function() {
        $("#pilihBarang").click(function() {
            // Mengambil nilai dari radio button yang dipilih
            var selectedBarang = $("input[name='selected_barang']:checked");

            if (selectedBarang.length > 0) {
                // Memasukkan nilai ke dalam input "nama_komponen"
                $("#nama_komponen").val(selectedBarang.val());

                // Mengisi input "harga_barang" dengan harga dari radio button yang dipilih
                var hargaBarang = selectedBarang.data("harga");
                $("#harga_barang").val(hargaBarang);

                // Mengisi input "spek" dengan spek dari radio button yang dipilih
                var spek = selectedBarang.data("spek");
                $("#spek").val(spek);

                // Mengisi input "satuan_1" dengan satuan_1 dari radio button yang dipilih
                var satuan_1 = selectedBarang.data("satuan_1");
                $("#satuan_1").val(satuan_1);

                // Menghitung total anggaran
                var vol1 = parseInt($("#vol1").val()) || 0;
                var vol2 = parseInt($("#vol2").val()) || 0;
                var harga_barang = parseInt($("#harga_barang").val()) || 0;
                var persen = parseInt($("#persen").val()) || 0;

                // Menghitung nilai ppn
                var ppn = ((vol1 * vol2) * harga_barang) * persen / 100;

                // Menghitung nilai hasil
                var result = ((vol1 * vol2) * harga_barang) + ppn;

                // Menyimpan hasil ke dalam input "hasil"
                $("#hasil").val(result);

                // Menutup modal
                $('#large-Modal').modal('hide');
            } else {
                alert('Please select an item.');
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const elements = document.querySelectorAll("[data-choices]");
        elements.forEach(function(el) {
            new Choices(el, {
                searchEnabled: true, // aktifkan fitur search
                itemSelectText: '', // hilangkan teks select
                shouldSort: false // biar urutannya sesuai option awal
            });
        });
    });
</script>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#example-movie').barrating({
                theme: 'bars-movie',
                showSelectedRating: true // tampilkan nilai yang dipilih
            });
        });
    </script>
@endpush

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('confirmSubmitForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menyimpan Data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('confirmUpdateForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menyimpan perubahan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // cegah submit default

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data ini akan dihapus secara permanen!!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // submit form kalau setuju
                    }
                });
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editButtons = document.querySelectorAll(".edit-item-btn");
        const form = document.getElementById("confirmUpdateForm");

        editButtons.forEach(btn => {
            btn.addEventListener("click", function() {
                let id = this.dataset.id;
                let no = this.dataset.no;
                let kategori = this.dataset.kategori;
                let pengusul = this.dataset.pengusul;
                let status = this.dataset.status;
                // let total = this.dataset.total;
                let route = this.dataset.route;

                // isi form
                form.action = route;
                document.getElementById("id-field").value = id;
                document.getElementById("orderId").value = no;
                document.getElementById("kategori-field").value = kategori;
                document.getElementById("pengusul-field").value = pengusul;
                document.getElementById("status-field").value = status;
                // document.getElementById("total-field").value = total;
            });
        });
    });
</script>



{{-- <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $('#komponen-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('komponens.index') }}",
                type: "GET"
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kategori_lengkap',
                    name: 'kategori_lengkap'
                },
                {
                    data: 'kode_barang',
                    name: 'kode_barang'
                },
                {
                    data: 'nama_barang',
                    name: 'nama_barang',
                    render: function(data, type, row) {
                        return '<span class="text-primary">' + data + '</span>';
                    }
                },
                {
                    data: 'harga_barang',
                    name: 'harga_barang',
                    render: $.fn.dataTable.render.number(',', '.', 0, 'Rp ')
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });
</script> --}}

{{-- <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#rekap_usulan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('rekap_usulans.index') }}",
                type: "GET",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'detail_usulan',
                    name: 'detail_usulan',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'uraian_usulan',
                    name: 'uraian_usulan',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'detail_biaya',
                    name: 'detail_biaya',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ]
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#assets-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('barang_assets.index') }}",
                type: "GET",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.tahun_perolehan = $('#tahun_perolehan').val(); // dropdown tahun
                    d.kondisi_asset = $('#kondisi_asset').val(); // dropdown kondisi
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode_asset',
                    name: 'kode_asset'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },
                {
                    data: 'detail',
                    name: 'detail',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'satuan',
                    name: 'satuan',
                    render: function(data) {
                        return '<span class="text-primary">' + data + '</span>';
                    }
                },
                {
                    data: 'harga_asset',
                    name: 'harga_asset',
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ')
                },
                {
                    data: 'kondisi_asset',
                    name: 'kondisi_asset',
                    render: function(data) {
                        let badgeClass = '';
                        if (data === 'Baik') {
                            badgeClass = 'badge bg-success';
                        } else if (data === 'Rusak Ringan') {
                            badgeClass = 'badge bg-warning';
                        } else if (data === 'Rusak Berat') {
                            badgeClass = 'badge bg-danger';
                        }
                        return '<span class="' + badgeClass + '">' + data + '</span>';
                    }
                },
                {
                    data: 'qrcode_path',
                    name: 'qrcode_path'
                },
                {
                    data: 'foto',
                    name: 'foto'
                },
                {
                    data: 'status_asset',
                    name: 'status_asset'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ]
        });
        // 🔄 Filter Button Click
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // 🔄 Reset Button Click
        $('#reset-btn').on('click', function() {
            $('#tahun_perolehan').val('').trigger('change');
            $('#kondisi_asset').val('').trigger('change');
            table.ajax.reload();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#reklas_arbs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('reklas_arbs.index') }}",
                type: "GET",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode_asset',
                    name: 'kode_asset'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },
                {
                    data: 'detail',
                    name: 'detail',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'satuan',
                    name: 'satuan',
                    render: function(data) {
                        return '<span class="text-primary">' + data + '</span>';
                    }
                },
                {
                    data: 'harga_asset',
                    name: 'harga_asset',
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ')
                },
                {
                    data: 'kondisi_asset',
                    name: 'kondisi_asset',
                    render: function(data) {
                        let badgeClass = '';
                        if (data === 'Baik') {
                            badgeClass = 'badge bg-success';
                        } else if (data === 'Rusak Ringan') {
                            badgeClass = 'badge bg-warning';
                        } else if (data === 'Rusak Berat') {
                            badgeClass = 'badge bg-danger';
                        }
                        return '<span class="' + badgeClass + '">' + data + '</span>';
                    }
                },
                {
                    data: 'qrcode_path',
                    name: 'qrcode_path'
                },
                {
                    data: 'foto',
                    name: 'foto'
                },
                {
                    data: 'status_asset',
                    name: 'status_asset'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ]
        });
    });
</script>



<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#validasicontrollerbarangjasaadmin-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('validasi_barang_jasa_admins.index') }}",
                type: "GET",
                data: function(d) {
                    d.sub_kategori_rkbu = $('#sub_kategori_rkbu').val();
                    d.id_status_validasi_rka = $('#id_status_validasi_rka').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'sub_kategori',
                    name: 'sub_kategori',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'barang',
                    name: 'barang',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'detail_anggaran',
                    name: 'detail_anggaran',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'detail_usulan',
                    name: 'detail_usulan',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },


                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],

        });
        // 🔄 Filter Button Click
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // 🔄 Reset Button Click
        $('#reset-btn').on('click', function() {
            $('#sub_kategori_rkbu').val('').trigger('change');
            $('#id_status_validasi_rka').val('').trigger('change');
            table.ajax.reload();
        });
    });
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#validasicontrollermodaladmin-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('validasi_modal_admins.index') }}",
                type: "GET",
                data: function(d) {
                    d.sub_kategori_rkbu = $('#sub_kategori_rkbu').val();
                    d.id_status_validasi_rka = $('#id_status_validasi_rka').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'sub_kategori',
                    name: 'sub_kategori',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'barang',
                    name: 'barang',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'detail_anggaran',
                    name: 'detail_anggaran',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'detail_usulan',
                    name: 'detail_usulan',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },


                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],

        });
        // 🔄 Filter Button Click
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // 🔄 Reset Button Click
        $('#reset-btn').on('click', function() {
            $('#sub_kategori_rkbu').val('').trigger('change');
            $('#id_status_validasi_rka').val('').trigger('change');
            table.ajax.reload();
        });
    });
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#validasicontrollerpersediaanadmin-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('validasi_persediaan_admins.index') }}",
                type: "GET",
                data: function(d) {
                    d.sub_kategori_rkbu = $('#sub_kategori_rkbu').val();
                    d.id_status_validasi_rka = $('#id_status_validasi_rka').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'sub_kategori',
                    name: 'sub_kategori',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'barang',
                    name: 'barang',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'detail_anggaran',
                    name: 'detail_anggaran',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'detail_usulan',
                    name: 'detail_usulan',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return data; // Render HTML langsung tanpa parse
                    }
                },


                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],

        });
        // 🔄 Filter Button Click
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // 🔄 Reset Button Click
        $('#reset-btn').on('click', function() {
            $('#sub_kategori_rkbu').val('').trigger('change');
            $('#id_status_validasi_rka').val('').trigger('change');
            table.ajax.reload();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#jenisBelanjaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('jenis_belanjas.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode_jenis_belanja',
                    name: 'kode_jenis_belanja'
                },
                {
                    data: 'nama_jenis_belanja',
                    name: 'nama_jenis_belanja'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Konfirmasi sebelum menghapus data
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<script>
    function tea() {
        var txtFirstNumberValue = document.getElementById('id_kategori_rkbu').value;
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#cari_kategori').change(function() {
            var subKategoriId = $(this).val();

            // Mengirim AJAX request ke server
            $.ajax({
                url: "{{ route('rkbu_barang_jasas.get_data_by_subkategori') }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id_sub_kategori_rkbu: subKategoriId
                },
                success: function(response) {
                    // Memasukkan data yang didapatkan ke input atau dropdown terkait
                    $('#id_kategori_rkbu').val(response.id_kategori_rkbu);
                    $('#id_jenis_belanja').val(response.id_jenis_belanja);
                    $('#id_jenis_kategori_rkbu').val(response.id_jenis_kategori_rkbu);
                    $('#id_admin_pendukung_ppk').val(response.id_admin_pendukung_ppk);
                    $('#id_sub_kategori_rekening').val(response.id_sub_kategori_rekening);
                    $('#id_kategori_rekening').val(response.id_kategori_rekening);
                    $('#id_aktivitas').val(response.id_aktivitas);
                    $('#nama_aktivitas').val(response.nama_aktivitas);
                    $('#id_kegiatan').val(response.id_sub_kegiatan);
                    $('#nama_kegiatan').val(response.nama_kegiatan);
                    $('#id_program').val(response.id_program);
                }
            });
        });
    });
</script> --}}

<script type="text/javascript">
    function sum_modal() {
        var vol_1 = parseFloat(document.getElementById('vol_1').value) || 0;
        var vol_2 = parseFloat(document.getElementById('vol_2').value) || 0;
        var harga_barang = parseFloat(document.getElementById('harga_barang').value) || 0;
        var ppnValue = parseFloat(document.getElementById('ppn').value) || 0;

        var jumlah_usulan_barang = (vol_1 * vol_2);
        var ppn = Math.round((jumlah_usulan_barang * harga_barang * ppnValue) / 100);
        var result = Math.round(jumlah_usulan_barang * harga_barang + ppn);

        if (!isNaN(result)) {
            document.getElementById('hasil').value = result.toString();
        }

        if (!isNaN(jumlah_usulan_barang)) {
            document.getElementById('jumlah_usulan_barang').value = jumlah_usulan_barang.toString();
        }

        if (!isNaN(ppn)) {
            document.getElementById('hasil_ppn').value = ppn.toString();
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#subKategoriSelect').change(function() {
            var subKategoriId = $(this).val();

            if (subKategoriId) {
                $.ajax({
                    url: "{{ route('rkbu_barang_jasas.get_data_by_subkategori') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_sub_kategori_rkbu: subKategoriId
                    },
                    success: function(response) {
                        console.log(response); // Debug respons dari server

                        // Isi nilai ID ke dalam form input
                        $('#id_sub_kategori_rekening').val(response
                            .id_sub_kategori_rekening);
                        $('#id_sub_kegiatan').val(response.id_aktivitas);

                        // Tampilkan data string dalam elemen HTML
                        $('#nama_program').text(response.kode_program + '. ' + response
                            .nama_program);
                        $('#nama_kegiatan').text(response.kode_kegiatan + '. ' + response
                            .nama_kegiatan);
                        $('#nama_sub_kegiatan').text(response.kode_sub_kegiatan + '. ' +
                            response.nama_sub_kegiatan);
                        $('#nama_kategori_rkbu').text('Kategori RKBU : ' + response
                            .nama_kategori_rkbu);
                        $('#nama_aktivitas').text('Aktivitas : ' + response.nama_aktivitas);
                        $('#nama_jenis_belanja').text('Jenis Belanja : ' + response
                            .nama_jenis_belanja);
                        $('#nama_kategori_rekening').text(response.kode_kategori_rekening +
                            '. ' + response.nama_kategori_rekening);
                        $('#nama_sub_kategori_rekening').text(response
                            .kode_sub_kategori_rekening + '. ' + response
                            .nama_sub_kategori_rekening);
                    }
                });
            } else {
                // Jika tidak ada yang dipilih, kosongkan nilai input dan teks
                $('#id_sub_kategori_rekening, #id_sub_kegiatan').val('');
                $('#nama_kategori_rekening, #nama_aktivitas, #nama_kegiatan, #nama_sub_kegiatan, #nama_program')
                    .text('');
            }
        });
    });
</script>

{{-- 


<script type="text/javascript">
    $(document).ready(function() {
        $('#subKategoriSelectRekeningBelanja').change(function() {
            var subKategoriId = $(this).val();

            if (subKategoriId) {
                $.ajax({
                    url: "{{ route('rkbu_barang_jasas.get_data_by_subkategori_rekening_belanja') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_sub_kategori_rkbu: subKategoriId
                    },
                    success: function(response) {
                        console.log(response); // Debug respons dari server

                        // Isi nilai ID ke dalam form input
                        $('#id_sub_kategori_rekening').val(response
                            .id_sub_kategori_rekening);
                        $('#id_sub_kegiatan').val(response.id_aktivitas);

                        // Tampilkan data string dalam elemen HTML
                        $('#nama_program').text(response.kode_program + '. ' + response
                            .nama_program);
                        $('#nama_kegiatan').text(response.kode_kegiatan + '. ' + response
                            .nama_kegiatan);
                        $('#nama_sub_kegiatan').text(response.kode_sub_kegiatan + '. ' +
                            response.nama_sub_kegiatan);
                        $('#nama_kategori_rkbu').text('Kategori RKBU : ' + response
                            .nama_kategori_rkbu);
                        $('#nama_aktivitas').text('Aktivitas : ' + response.nama_aktivitas);
                        $('#nama_jenis_belanja').text('Jenis Belanja : ' + response
                            .nama_jenis_belanja);
                        $('#nama_kategori_rekening').text(response.kode_kategori_rekening +
                            '. ' + response.nama_kategori_rekening);
                        $('#nama_sub_kategori_rekening').text(response
                            .kode_sub_kategori_rekening + '. ' + response
                            .nama_sub_kategori_rekening);
                    }
                });
            } else {
                // Jika tidak ada yang dipilih, kosongkan nilai input dan teks
                $('#id_sub_kategori_rekening, #id_sub_kegiatan').val('');
                $('#nama_kategori_rekening, #nama_aktivitas, #nama_kegiatan, #nama_sub_kegiatan, #nama_program')
                    .text('');
            }
        });
    });
</script>

 --}}

<script type="text/javascript">
    $(document).ready(function() {
        $('#subKategoriSelect2').change(function() {
            var subKategoriId = $(this).val();

            if (subKategoriId) {
                $.ajax({
                    url: "{{ route('usulan_barang_barjass.get_data_by_subkategori') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_sub_kategori_rkbu: subKategoriId
                    },
                    success: function(response) {
                        console.log(response); // Debug respons dari server

                        // Isi nilai ID ke dalam form input
                        $('#id_sub_kategori_rekening').val(response
                            .id_sub_kategori_rekening);
                        $('#id_kategori_rkbu').val(response.id_kategori_rkbu);
                        $('#id_jenis_kategori_rkbu').val(response.id_jenis_kategori_rkbu);
                        $('#id_sub_kegiatan').val(response.id_aktivitas);

                        // Tampilkan data string dalam elemen HTML
                        $('#nama_program').text(response.kode_program + '. ' + response
                            .nama_program);
                        $('#nama_kegiatan').text(response.kode_kegiatan + '. ' + response
                            .nama_kegiatan);
                        $('#nama_sub_kegiatan').text(response.kode_sub_kegiatan + '. ' +
                            response.nama_sub_kegiatan);
                        $('#nama_kategori_rkbu').text('Kategori RKBU : ' + response
                            .nama_kategori_rkbu);
                        $('#nama_aktivitas').text('Aktivitas : ' + response.nama_aktivitas);
                        $('#nama_jenis_belanja').text('Jenis Belanja : ' + response
                            .nama_jenis_belanja);
                        $('#nama_kategori_rekening').text(response.kode_kategori_rekening +
                            '. ' + response.nama_kategori_rekening);
                        $('#nama_sub_kategori_rekening').text(response
                            .kode_sub_kategori_rekening + '. ' + response
                            .nama_sub_kategori_rekening);
                    }
                });
            } else {
                // Jika tidak ada yang dipilih, kosongkan nilai input dan teks
                $('#id_sub_kategori_rekening, #id_sub_kegiatan').val('');
                $('#nama_kategori_rekening, #nama_aktivitas, #nama_kegiatan, #nama_sub_kegiatan, #nama_program')
                    .text('');
            }
        });
    });
</script>

<script>
    function total() {
        var txtFirstNumberValue = document.getElementById('sisa_stok').value;
        var txtSecondNumberValue = document.getElementById('pengadaan_sebelumnya').value;
        var txtTigaNumberValue = document.getElementById('rata_rata_pemakaian').value;
        var txtEmpatNumberValue = document.getElementById('kebutuhan_per_bulan').value;
        var txtlimaNumberValue = document.getElementById('buffer').value;
        var txtEnamNumberValue = document.getElementById('harga_satuan').value;
        var txttujuhNumberValue = document.getElementById('persen').value;
        var result1 = ((parseInt(txtFirstNumberValue) + parseInt(txtSecondNumberValue))) - (parseInt(
            txtTigaNumberValue) * parseInt(txtEmpatNumberValue));
        if (!isNaN(result1)) {
            document.getElementById('proyeksi_sisa_stok').value = result1;
        }
        var result2 = parseInt(txtTigaNumberValue) * parseInt(txtEmpatNumberValue);
        if (!isNaN(result2)) {
            document.getElementById('kebutuhan_tahun_x1').value = result2;
        }
        var result3 = parseInt(result2) + (parseInt(txtTigaNumberValue) * parseInt(txtlimaNumberValue));
        if (!isNaN(result3)) {
            document.getElementById('kebutuhan_plus_buffer').value = result3;
        }
        var result4 = parseInt(result3) - parseInt(result1);
        if (!isNaN(result4)) {
            document.getElementById('rencana_pengadaan_tahun_x1').value = result4;
        }
        var ppn = parseInt(result4) * parseInt(txtEnamNumberValue) * parseInt(txttujuhNumberValue) / 100;
        var result5 = (parseInt(result4) * parseInt(txtEnamNumberValue)) + parseInt(ppn);
        if (!isNaN(result5)) {
            document.getElementById('total_persediaan').value = result5.toLocaleString('id-ID');
        }
    }
</script>


{{-- <script>
    function showDetailRkbu(id) {
        // Ganti isi modal dengan loading spinner saat data di-load
        $('#pricingModal .modal-body').html(
            '<div class="text-center"><span class="spinner-border" role="status" aria-hidden="true"></span> Loading...</div>'
        );

        // Panggil AJAX ke endpoint detail
        $.ajax({
            url: `/rkbu_modal_kantors/${id}/show`, // Pastikan route ini sesuai
            type: 'GET',
            success: function(data) {
                // Masukkan data yang diterima ke dalam modal-body
                $('#pricingModal .modal-body').html(data);
                // Tampilkan modal
                $('#pricingModal').modal('show');
            },
            error: function(xhr, status, error) {
                $('#pricingModal .modal-body').html(
                    '<div class="alert alert-danger text-center">Unable to load data.</div>');
                console.error("Error:", error);
            }
        });
    }
</script>

<script>
    function showDetailRkbuPersediaan(id) {
        // Ganti isi modal dengan loading spinner saat data di-load
        $('#pricingModal .modal-body').html(
            '<div class="text-center"><span class="spinner-border" role="status" aria-hidden="true"></span> Loading...</div>'
        );

        // Panggil AJAX ke endpoint detail
        $.ajax({
            url: `/rkbu_persediaans/${id}/show`, // Pastikan route ini sesuai
            type: 'GET',
            success: function(data) {
                // Masukkan data yang diterima ke dalam modal-body
                $('#pricingModal .modal-body').html(data);
                // Tampilkan modal
                $('#pricingModal').modal('show');
            },
            error: function(xhr, status, error) {
                $('#pricingModal .modal-body').html(
                    '<div class="alert alert-danger text-center">Unable to load data.</div>');
                console.error("Error:", error);
            }
        });
    }
</script>

<script>
    function showDetailRkbuBarjas(id) {
        // Ganti isi modal dengan loading spinner saat data di-load
        $('#pricingModal .modal-body').html(
            '<div class="text-center"><span class="spinner-border" role="status" aria-hidden="true"></span> Loading...</div>'
        );

        // Panggil AJAX ke endpoint detail
        $.ajax({
            url: `/rkbu_barang_jasas/${id}/show`, // Pastikan route ini sesuai
            type: 'GET',
            success: function(data) {
                // Masukkan data yang diterima ke dalam modal-body
                $('#pricingModal .modal-body').html(data);
                // Tampilkan modal
                $('#pricingModal').modal('show');
            },
            error: function(xhr, status, error) {
                $('#pricingModal .modal-body').html(
                    '<div class="alert alert-danger text-center">Unable to load data.</div>');
                console.error("Error:", error);
            }
        });
    }
</script> --}}

<script>
    function showDetailMasterSpj(id) {
        // Reset isi modal sebelum request baru
        $('#modalDetailContent').html(
            '<div class="text-center"><span class="spinner-border text-primary" role="status"></span> Memuat data...</div>'
        );

        // Tampilkan modal
        $('#modalDetailMasterSpj').modal('show');

        // Kirim AJAX request untuk mengambil data detail
        $.ajax({
            url: "{{ route('master_spjs.show', ':id') }}".replace(':id', id),
            method: 'GET',
            success: function(response) {
                $('#modalDetailContent').html(response);
            },
            error: function(xhr) {
                $('#modalDetailContent').html(
                    '<div class="alert alert-danger">Terjadi kesalahan saat memuat data.</div>'
                );
                console.log("DEBUG ERROR:", xhr.responseText);
            }
        });

    }
</script>


<script>
    function formatNumber(input) {
        let value = input.value.replace(/[^0-9,]/g, ''); // Hanya izinkan angka dan koma
        let parts = value.split(',');

        if (parts.length > 2) {
            value = parts[0] + ',' + parts.slice(1).join(''); // Pastikan hanya ada satu koma
        }

        input.value = value;
    }
</script>

<script type="text/javascript">
    function sum_farmasi() {
        var harga_barang = parseFloat(document.getElementById('harga_barang').value) || 0;
        var ppn = parseFloat(document.getElementById('ppn').value) || 0;
        var rata2_pemakaian = parseFloat(document.getElementById('rata2_pemakaian').value) || 0;
        var sisa_stok = parseFloat(document.getElementById('sisa_stok').value) || 0;
        var buffer_stok = parseFloat(document.getElementById('buffer_stok').value) || 0;
        var pengkali = parseFloat(document.getElementById('pengkali').value) || 0;

        var result1 = (pengkali * rata2_pemakaian);
        if (!isNaN(result1)) {
            document.getElementById('minimal_stok').value = result1.toFixed(2);
        }

        var result2 = (buffer_stok + result1) - sisa_stok;
        if (!isNaN(result2)) {
            document.getElementById('usulan_barang').value = result2.toFixed(2);
        }

        var result3 = (harga_barang * result2);
        if (!isNaN(result3)) {
            document.getElementById('total_usulan_barang_ppn').value = result3.toFixed(2);
        }

        var result4 = (result3 * ppn) / 100;
        if (!isNaN(result4)) {
            document.getElementById('hasil_ppn').value = result4.toFixed(2);
        }

        var result5 = result3 + result4;
        if (!isNaN(result5)) {
            document.getElementById('total_usulan_barang_ppn').value = result5.toFixed(2);
        }
    }
</script>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.check-item');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = document.getElementById('checkAll').checked;
        });
    });

    document.querySelectorAll('.check-item').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.check-item:checked').length === document
                .querySelectorAll('.check-item').length;
            document.getElementById('checkAll').checked = allChecked;
        });
    });
</script>

<script>
    function tot_remunerasi() {
        var txtFirstNumberValue = document.getElementById('gaji_pokok').value;
        var txtSecondNumberValue = document.getElementById('koefisien_gaji').value;
        var txtTigaNumberValue = document.getElementById('remunerasi').value;
        var txtEmpatNumberValue = document.getElementById('koefisien_remunerasi').value;
        var txtlimaNumberValue = document.getElementById('bpjs_kesehatan').value;
        var txtEnamNumberValue = document.getElementById('bpjs_tk').value;
        var txttujuhNumberValue = document.getElementById('bpjs_jht').value;
        var result1 = (parseInt(txtFirstNumberValue) * parseInt(txtSecondNumberValue));
        if (!isNaN(result1)) {
            document.getElementById('tot_gaji_pokok').value = result1;
        }
        var result2 = parseInt(txtTigaNumberValue) * parseInt(txtEmpatNumberValue);
        if (!isNaN(result2)) {
            document.getElementById('remun').value = result2;
        }
        var bpjs = (parseInt(result1) * parseInt(txtlimaNumberValue) / 100) + (parseInt(result1) * parseInt(
            txtEnamNumberValue) / 100) + (parseInt(result1) * parseInt(txttujuhNumberValue) / 100);
        var result3 = (parseInt(result1) + parseInt(bpjs) + parseInt(result2));
        if (!isNaN(result3)) {
            document.getElementById('total_pegawai').value = result3;
        }
    }
</script>

{{-- <script>
    $(document).ready(function() {
        $('.show_detail').click(function() {
            var indexTable = $(this).data('indextable');
            var idUsulanBarang = $(this).data('id_usulan_barang');
            var idJenisKategori = $(this).data('id_jenis_kategori_rkbu'); // Tambahkan untuk kategori

            console.log('Index Table:', indexTable);
            console.log('ID Usulan Barang:', idUsulanBarang);
            console.log('ID Jenis Kategori:', idJenisKategori); // Debug kategori

            // Bind hanya jika id_jenis_kategori_rkbu sesuai
            if (!$.fn.DataTable.isDataTable('#example' + indexTable)) {
                $('#example' + indexTable).DataTable();
            }

            var table = $('#example' + indexTable).DataTable();

            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                var rowNode = this.node();
                $(rowNode).find('.bind_id_usulan_barang').val(idUsulanBarang);
            });
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
        $('.show_detail').click(function() {
            var indexTable = $(this).data('indextable');
            var idUsulanBarang = $(this).data('id_usulan_barang');
            var idJenisKategori = $(this).data('id_jenis_kategori_rkbu');

            // Kalau tabel belum jadi DataTable, baru inisialisasi
            if (!$.fn.DataTable.isDataTable('#example' + indexTable)) {
                $('#example' + indexTable).DataTable({
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    autoWidth: false,
                    responsive: true,
                    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                        // baris atas: left=length, right=search
                        'rt' + // isi tabel
                        '<"row"<"col-sm-6"i><"col-sm-6"p>>', // baris bawah: info dan pagination
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        }
                    }
                });
            }
        });
    });
</script>




{{-- 
<script type="text/javascript">
    $(document).ready(function() {
        // Trigger saat sub kategori dipilih
        $('#subKategoriSelect').on('change', function() {
            var idSubKategoriRkbu = $(this).val();

            // Lakukan request AJAX untuk mendapatkan data dari sub kategori
            $.ajax({
                url: '/get-data-by-sub-kategori', // URL ke route
                type: 'GET',
                data: {
                    id_sub_kategori_rkbu: idSubKategoriRkbu
                },
                success: function(data) {
                    // Menampilkan data ke dalam span atau element lain
                    $('#id_kategori_rkbu').text('ID Kategori RKBU: ' + data
                        .id_kategori_rkbu);
                    $('#id_jenis_belanja').text('ID Jenis Belanja: ' + data
                        .id_jenis_belanja);
                    $('#id_jenis_kategori_rkbu').text('ID Jenis Kategori RKBU: ' + data
                        .id_jenis_kategori_rkbu);
                    $('#id_admin_pendukung_ppk').text('ID Admin Pendukung PPK: ' + data
                        .id_admin_pendukung_ppk);
                    $('#id_aktivitas').text('ID Aktivitas: ' + data.id_aktivitas);
                    $('#nama_kategori_rkbu').text('Nama Kategori: ' + data
                        .nama_kategori_rkbu);
                    $('#nama_program').text('Program: ' + data.kode_program + '. ' + data
                        .nama_program);
                    $('#nama_aktivitas').text('Aktivitas: ' + data.nama_aktivitas);
                    $('#nama_kegiatan').text('Kegiatan: ' + data.kode_kegiatan + '. ' + data
                        .nama_kegiatan);
                    $('#nama_sub_kegiatan').text('Sub Kegiatan: ' + data.kode_sub_kegiatan +
                        '. ' + data.nama_sub_kegiatan);
                    $('#nama_jenis_belanja').text('Nama Jenis Belanja: ' + data
                        .nama_jenis_belanja);
                    $('#nama_kategori_rekening').text('Kategori Rekening: ' + data
                        .kode_kategori_rekening + '. ' + data.nama_kategori_rekening);
                    $('#nama_sub_kategori_rekening').text('Sub Kategori Rekening: ' + data
                        .kode_sub_kategori_rekening + '. ' + data
                        .nama_sub_kategori_rekening);
                    $('#id_kegiatan').text('ID Kegiatan: ' + data.id_sub_kegiatan);
                    $('#id_kategori_rekening').text('ID Kategori Rekening: ' + data
                        .id_kategori_rekening);
                    $('#id_sub_kategori_rekening').text('ID Sub Kategori Rekening: ' + data
                        .id_sub_kategori_rekening);

                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        });
    });
</script> --}}

<script type="text/javascript">
    function sum_belanja() {
        var vol1 = parseFloat(document.getElementById('vol1').value) || 0;
        var vol2 = parseFloat(document.getElementById('vol2').value) || 0;
        var hargaBarang = parseFloat(document.getElementById('harga_satuan').value) || 0;
        var persen = parseFloat(document.getElementById('persen').value) || 0;
        var jumlah_vol = parseFloat(document.getElementById('jumlah_vol').value) || 0;
        var total_anggaran = parseFloat(document.getElementById('total_anggaran').value) || 0;
        var id_rkbu = parseFloat(document.getElementById('id_rkbu').value) || 0;
        var id_sumber_dana = parseFloat(document.getElementById('id_sumber_dana').value) || 0;
        var id_kategori_rkbu = parseFloat(document.getElementById('id_kategori_rkbu').value) || 0;
        var id_sub_kategori_rkbu = parseFloat(document.getElementById('id_sub_kategori_rkbu').value) || 0;
        var id_jenis_kategori_rkbu = parseFloat(document.getElementById('id_jenis_kategori_rkbu').value) || 0;
        var id_kode_rekening_belanja = parseFloat(document.getElementById('id_kode_rekening_belanja').value) || 0;

        var sisa_vol = jumlah_vol - (vol1 * vol2);
        var ppn = ((vol1 * vol2) * hargaBarang) * persen / 100;
        var result = ((vol1 * vol2) * hargaBarang) + ppn;
        var result2 = total_anggaran - (((vol1 * vol2) * hargaBarang) + ppn);

        if (!isNaN(result)) {
            document.getElementById('hasil').value = result;
        }

        if (!isNaN(result2)) {
            document.getElementById('sisa_anggaran').value = result2;
        }

        var sisaVolumeElement = document.getElementById('sisa_volume');

        if (!isNaN(sisa_vol)) {
            sisaVolumeElement.value = sisa_vol;

            // Periksa apakah sisa_vol lebih kecil dari 0
            if (sisa_vol < 0) {
                sisaVolumeElement.style.color = 'red'; // Atur warna teks menjadi merah
                alert("Sisa volume kurang dari 0!");
            } else {
                sisaVolumeElement.style.color = ''; // Reset warna teks jika tidak kurang dari 0
            }
        }
    }
</script>
<script>
    $(document).ready(function() {
        // Event listener ketika modal ditampilkan
        $('#referAndEarn').on('shown.bs.modal', function() {
            // Event listener untuk select option di dalam modal
            $('#getkategori').on('change', function() {
                var selectedOption = $(this).find('option:selected');

                var id_kategori_rkbu = selectedOption.data('id_kategori_rkbu');
                var id_jenis_kategori_rkbu = selectedOption.data('id_jenis_kategori_rkbu');
                var id_jenis_belanja = selectedOption.data('id_jenis_belanja');

                console.log('Kategori RKBU:', id_kategori_rkbu);
                console.log('Jenis Kategori RKBU:', id_jenis_kategori_rkbu);
                console.log('Jenis Belanja:', id_jenis_belanja);

                // Isi nilai ke hidden input
                $('#id_kategori_rkbu').val(id_kategori_rkbu);
                $('#id_jenis_kategori_rkbu').val(id_jenis_kategori_rkbu);
                $('#id_jenis_belanja').val(id_jenis_belanja);
            });
        });
    });
</script>
@stack('scripts')
</body>

</html>
