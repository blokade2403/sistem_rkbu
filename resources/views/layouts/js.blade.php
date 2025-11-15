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

<script>
    // Fungsi ubah string berformat Rupiah jadi angka murni
    function parseNumber(value) {
        if (!value) return 0;
        return parseFloat(value.toString().replace(/[^0-9.-]/g, '')) || 0;
    }

    // Fungsi ubah angka jadi format Rupiah
    function formatRupiah(angka) {
        return angka.toLocaleString('id-ID');
    }

    function sum_modal() {
        const vol_1 = parseFloat(document.getElementById('vol_1').value) || 0;
        const vol_2 = parseFloat(document.getElementById('vol_2').value) || 0;
        const harga_raw = document.getElementById('harga_barang').value;
        const harga_barang = parseNumber(harga_raw);
        const ppnValue = parseFloat(document.getElementById('ppn').value) || 0;

        // Hitung jumlah usulan (vol 1 * vol 2)
        const jumlah_usulan_barang = vol_1 * vol_2;
        document.getElementById('jumlah_usulan_barang').value = jumlah_usulan_barang.toFixed(2);

        // Hitung total harga + PPN
        const subtotal = jumlah_usulan_barang * harga_barang;
        const total_ppn = subtotal * ppnValue / 100;
        const total_anggaran = subtotal + total_ppn;

        // Tampilkan hasil ke input
        document.getElementById('hasil').value = formatRupiah(total_anggaran);
        document.getElementById('total_anggaran_number').value = total_anggaran; // untuk backend
        document.getElementById('harga_barang_number').value = harga_barang; // untuk backend

        // Format ulang harga di UI
        document.getElementById('harga_barang').value = formatRupiah(harga_barang);
    }

    // Event agar harga satuan otomatis format Rupiah
    document.getElementById('harga_barang').addEventListener('input', function(e) {
        const cursorPos = e.target.selectionStart;
        const angka = parseNumber(e.target.value);
        e.target.value = formatRupiah(angka);
        e.target.setSelectionRange(cursorPos, cursorPos);
        sum_modal();
    });
</script>

{{-- Script preview --}}
<script>
    function previewImage(inputId, imgId) {
        const input = document.getElementById(inputId);
        const img = document.getElementById(imgId);

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Hubungkan semua input ke preview-nya
    previewImage('profile-img-file-input', 'preview-img-1');
    previewImage('profile-img-file-input-2', 'preview-img-2');
    previewImage('profile-img-file-input-3', 'preview-img-3');
</script>


<!-- ==================== JS ==================== -->
<script>
    (function() {
        // helper: hapus semua karakter non-digit (biarkan angka saja)
        function digitsOnly(str) {
            return String(str).replace(/[^\d]/g, '');
        }

        // format ke tampilan id-ID (ribuan)
        function formatCurrencyInput(el) {
            let raw = digitsOnly(el.value);
            if (raw === '') {
                el.value = '';
                return;
            }
            // parse ke angka integer (hindari parseFloat pada string panjang)
            let n = parseInt(raw, 10) || 0;
            el.value = new Intl.NumberFormat('id-ID').format(n);
        }

        // fungsi hitung total
        function sum() {
            // ambil vol1, vol2
            const vol1 = parseFloat(document.getElementById('vol1').value) || 0;
            const vol2 = parseFloat(document.getElementById('vol2').value) || 0;

            // ambil harga, hapus format ribuan terlebih dahulu
            const hargaRaw = digitsOnly(document.getElementById('harga_barang').value);
            const hargaBarang = parseFloat(hargaRaw) || 0;

            const persen = parseFloat(document.getElementById('persen').value) || 0;

            const subtotal = vol1 * vol2 * hargaBarang; // number
            const ppn = subtotal * persen / 100;
            const total = subtotal + ppn;

            // tampilkan dalam format id-ID
            document.getElementById('hasil').value =
                (total === 0) ? '' : new Intl.NumberFormat('id-ID').format(Math.round(total));
        }

        // bind event pada element yang relevan
        function bindEvents() {
            const hargaEl = document.getElementById('harga_barang');
            const vol1El = document.getElementById('vol1');
            const vol2El = document.getElementById('vol2');
            const persenEl = document.getElementById('persen');

            // format saat mengetik, lalu hitung
            hargaEl.addEventListener('input', function(e) {
                // simpan posisi kursor sederhana: biarkan browser pindah (lebih aman)
                formatCurrencyInput(hargaEl);
                sum();
            });

            // untuk number fields, cukup hitung saat ada input
            [vol1El, vol2El, persenEl].forEach(function(el) {
                el.addEventListener('input', sum);
            });
        }

        // inisialisasi saat DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            // pastikan elemen ada
            if (!document.getElementById('harga_barang')) return;

            // 1. Format harga saat load (kalau ada)
            const hargaEl = document.getElementById('harga_barang');
            if (hargaEl.value && hargaEl.value.trim() !== '') {
                // apabila Blade memberi nilai terformat (misal "1.200.000") atau plain number, normalize dulu
                priceDigits = digitsOnly(hargaEl.value);
                if (priceDigits !== '') {
                    // set ke format id-ID
                    hargaEl.value = new Intl.NumberFormat('id-ID').format(parseInt(priceDigits, 10));
                } else {
                    hargaEl.value = '';
                }
            }

            // 2. Bind events
            bindEvents();

            // 3. Hitung sekali setelah format selesai
            // gunakan setTimeout kecil untuk memastikan DOM update selesai
            setTimeout(sum, 10);
        });
    })();
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

<script>
    // Fungsi parsing angka dari format Rupiah
    function parseNumber(value) {
        if (!value) return 0;
        return parseFloat(value.toString().replace(/[^0-9.-]/g, '')) || 0;
    }

    // Fungsi format angka ke Rupiah
    function formatRupiah(angka) {
        return angka.toLocaleString('id-ID');
    }

    function sum_farmasi() {
        // Ambil nilai dari input
        var harga_barang_raw = document.getElementById('harga_barang').value;
        var harga_barang = parseNumber(harga_barang_raw);
        var ppn = parseFloat(document.getElementById('ppn').value) || 0;
        var rata2_pemakaian = parseFloat(document.getElementById('rata2_pemakaian')?.value) || 0;
        var sisa_stok = parseFloat(document.getElementById('sisa_stok')?.value) || 0;
        var buffer_stok = parseFloat(document.getElementById('buffer_stok')?.value) || 0;
        var pengkali = parseFloat(document.getElementById('pengkali')?.value) || 1;

        // Hitung stok minimal
        var stok_minimal = pengkali * rata2_pemakaian;
        if (document.getElementById('minimal_stok'))
            document.getElementById('minimal_stok').value = !isNaN(stok_minimal) ? stok_minimal.toFixed(2) : 0;

        // Hitung jumlah usulan barang
        var jumlah_usulan = (buffer_stok + stok_minimal) - sisa_stok;
        if (document.getElementById('usulan_barang'))
            document.getElementById('usulan_barang').value = !isNaN(jumlah_usulan) ? jumlah_usulan.toFixed(2) : 0;

        // Hitung total harga
        var total_harga = harga_barang * jumlah_usulan;

        // Hitung total PPN
        var total_ppn = (total_harga * ppn) / 100;
        if (document.getElementById('hasil_ppn'))
            document.getElementById('hasil_ppn').value = !isNaN(total_ppn) ? total_ppn.toFixed(2) : 0;

        // Total akhir
        var total_anggaran = total_harga + total_ppn;

        // Tampilkan hasil ke field tampilan
        if (!isNaN(total_anggaran)) {
            document.getElementById('hasil').value = formatRupiah(total_anggaran);
            document.getElementById('total_anggaran_number').value = total_anggaran; // hidden numeric
        }

        // Simpan harga_barang numeric ke hidden field
        document.getElementById('harga_barang_hidden').value = harga_barang;

        // Format ulang harga_barang di tampilan
        document.getElementById('harga_barang').value = formatRupiah(harga_barang);
    }

    // Format harga_barang setiap kali diketik
    document.getElementById('harga_barang').addEventListener('input', function(e) {
        const angka = parseNumber(e.target.value);
        e.target.value = formatRupiah(angka);
        document.getElementById('harga_barang_hidden').value = angka;
        sum_farmasi();
    });
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
    // Fungsi parsing angka dari string berformat (hapus semua karakter non-numeric)
    function parseNumber(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/[^0-9]/g, '')) || 0;
    }

    // Fungsi format angka dengan titik ribuan
    function formatNumber(angka) {
        if (isNaN(angka)) return '';
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Fungsi utama menghitung total
    function tot_remunerasi() {
        let gaji_pokok = parseNumber(document.getElementById('gaji_pokok').value);
        let koef_gaji = parseFloat(document.getElementById('koefisien_gaji').value) || 0;
        let remunerasi = parseNumber(document.getElementById('remunerasi').value);
        let koef_remun = parseFloat(document.getElementById('koefisien_remunerasi').value) || 0;
        let bpjs_kes = parseFloat(document.getElementById('bpjs_kesehatan').value) || 0;
        let bpjs_tk = parseFloat(document.getElementById('bpjs_tk').value) || 0;
        let bpjs_jht = parseFloat(document.getElementById('bpjs_jht').value) || 0;

        // Perhitungan
        let total_gaji_pokok = gaji_pokok * koef_gaji;
        let total_remun = remunerasi * koef_remun;
        let total_bpjs =
            (total_gaji_pokok * bpjs_kes / 100) +
            (total_gaji_pokok * bpjs_tk / 100) +
            (total_gaji_pokok * bpjs_jht / 100);
        let total_pegawai = total_gaji_pokok + total_remun + total_bpjs;

        // Format tampilan (tapi tetap simpan angka murni di hidden input)
        document.getElementById('tot_gaji_pokok').value = formatNumber(total_gaji_pokok);
        document.getElementById('remun').value = formatNumber(total_remun);
        document.getElementById('total_pegawai').value = formatNumber(total_pegawai);

        // Format tampilan untuk input utama
        document.getElementById('gaji_pokok').value = formatNumber(gaji_pokok);
        document.getElementById('remunerasi').value = formatNumber(remunerasi);

        // Simpan nilai bersih di hidden input (supaya server dapat angka murni)
        document.getElementById('gaji_pokok_clean').value = gaji_pokok;
        document.getElementById('remunerasi_clean').value = remunerasi;
    }

    // 🧹 Bersihkan semua format (titik, spasi, simbol) sebelum form disubmit
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                const inputs = form.querySelectorAll('input');
                inputs.forEach(input => {
                    // Jika input punya angka yang diformat, bersihkan sebelum dikirim
                    input.value = input.value.replace(/[^0-9.-]/g, '');
                });
            });
        }
    });
</script>

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

<script>
    // === Fungsi parsing dan format ===
    function parseNumber(value) {
        if (!value) return 0;
        return Number(value.toString().replace(/\./g, '').replace(/,/g, '.')) || 0;
    }

    function formatRupiah(angka) {
        if (isNaN(angka)) return '';
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // === Fungsi utama perhitungan ===
    function hitungSemua() {
        const vol_1 = parseFloat(document.getElementById('vol_1')?.value) || 0;
        const vol_2 = parseFloat(document.getElementById('vol_2')?.value) || 0;
        const harga_barang_raw = document.getElementById('harga_barang')?.value || '0';
        const harga_barang = parseNumber(harga_barang_raw);
        const ppn = parseFloat(document.getElementById('ppn')?.value) || 0;
        const rata2_pemakaian = parseFloat(document.getElementById('rata2_pemakaian')?.value) || 0;
        const sisa_stok = parseFloat(document.getElementById('sisa_stok')?.value) || 0;
        const buffer_stok = parseFloat(document.getElementById('buffer_stok')?.value) || 0;
        const pengkali = parseFloat(document.getElementById('pengkali')?.value) || 1;

        // Farmasi
        const stok_minimal = pengkali * rata2_pemakaian;
        const jumlah_usulan_farmasi = Math.max((buffer_stok + stok_minimal) - sisa_stok, 0);

        if (document.getElementById('minimal_stok'))
            document.getElementById('minimal_stok').value = stok_minimal.toFixed(2);
        if (document.getElementById('usulan_barang'))
            document.getElementById('usulan_barang').value = jumlah_usulan_farmasi.toFixed(2);

        // Modal
        const jumlah_usulan_modal = vol_1 * vol_2;
        if (document.getElementById('jumlah_usulan_barang'))
            document.getElementById('jumlah_usulan_barang').value = jumlah_usulan_modal.toFixed(2);

        const jumlah_final = jumlah_usulan_farmasi > 0 ? jumlah_usulan_farmasi : jumlah_usulan_modal;

        const subtotal = jumlah_final * harga_barang;
        const total_ppn = subtotal * (ppn / 100);
        const total_anggaran = subtotal + total_ppn;

        if (document.getElementById('hasil'))
            document.getElementById('hasil').value = formatRupiah(total_anggaran.toFixed(0));
        if (document.getElementById('total_anggaran_number'))
            document.getElementById('total_anggaran_number').value = total_anggaran;
        if (document.getElementById('harga_barang_number'))
            document.getElementById('harga_barang_number').value = harga_barang;
    }

    // === Format harga otomatis ===
    const hargaInput = document.getElementById('harga_barang');
    if (hargaInput) {
        hargaInput.addEventListener('focus', function(e) {
            const angka = parseNumber(e.target.value);
            e.target.value = angka > 0 ? angka : '';
        });

        hargaInput.addEventListener('blur', function(e) {
            const angka = parseNumber(e.target.value);
            e.target.value = angka > 0 ? formatRupiah(angka) : '';
            hitungSemua();
        });

        hargaInput.addEventListener('input', function() {
            hitungSemua();
        });
    }

    // === Event Listener ===
    const fieldIds = [
        'vol_1', 'vol_2', 'ppn',
        'rata2_pemakaian', 'sisa_stok', 'buffer_stok', 'pengkali'
    ];
    fieldIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', hitungSemua);
    });

    // === Saat halaman dimuat ===
    window.addEventListener('DOMContentLoaded', function() {
        const hargaField = document.getElementById('harga_barang');
        if (hargaField && hargaField.value) {
            const angka = parseNumber(hargaField.value);
            hargaField.value = formatRupiah(angka);
        }
        hitungSemua();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Navigasi tab
        document.querySelectorAll('.nexttab').forEach(btn => {
            btn.addEventListener('click', function() {
                const nextTab = document.querySelector(`#${this.dataset.nexttab}`);
                if (nextTab) new bootstrap.Tab(nextTab).show();
            });
        });

        document.querySelectorAll('.previestab').forEach(btn => {
            btn.addEventListener('click', function() {
                const prevTab = document.querySelector(`#${this.dataset.previous}`);
                if (prevTab) new bootstrap.Tab(prevTab).show();
            });
        });

        // Preview gambar otomatis
        for (let p = 0; p <= 1; p++) { // prefix kosong dan 1 (Parsial)
            for (let i = 1; i <= 3; i++) {
                const prefix = p === 0 ? '' : '1';
                const input = document.getElementById(`profile-img-file-input-${prefix}-${i}`);
                const preview = document.getElementById(`preview-img-${prefix}-${i}`);
                if (input && preview) {
                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                preview.src = ev.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
            }
        }
    });
</script>


@stack('scripts')
</body>

</html>






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
