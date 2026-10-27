<script>
    $(document).ready(function() {

        $('#btnPilihKomponen').click(function() {

            let selected = $('input[name="selected_barang"]:checked');

            if (selected.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih komponen terlebih dahulu'
                });

                return;
            }

            $('#nama_barang').val(
                selected.val()
            );

            $('#spesifikasi').val(
                selected.data('spek')
            );

            $('#harga_satuan').val(
                selected.data('harga')
            );

            $('#satuan_1').val(selected.data('satuan')).trigger('change');

            $('#kode_komponen').val(
                selected.data('kode')
            );

            $('#kt_modal_new_komponen').modal('hide');

        });

    });
</script>

<script>
    $(document).ready(function() {
        if (!$('#volume_1').length || !$('#volume_2').length) {
            return;
        }

        function hitungAnggaran() {

            let volume1 = parseFloat($('#volume_1').val()) || 0;
            let volume2 = parseFloat($('#volume_2').val()) || 0;
            let harga = parseFloat($('#harga_satuan').val()) || 0;
            let ppn = parseFloat($('#ppn').val()) || 0;

            // jumlah volume
            let jumlahVolume = volume1 * volume2;

            // subtotal
            let subtotal = jumlahVolume * harga;

            // cek ppn aktif
            let total = subtotal;

            if ($('#is_ppn').is(':checked')) {
                total = subtotal + (subtotal * ppn / 100);
            }

            $('#total_anggaran_real').val(total);

            $('#total_anggaran').val(
                total.toLocaleString('id-ID')
            );
        }

        $('#volume_1').on('keyup change', hitungAnggaran);
        $('#volume_2').on('keyup change', hitungAnggaran);
        $('#harga_satuan').on('keyup change', hitungAnggaran);
        $('#is_ppn').on('change', hitungAnggaran);

    });
</script>

<script>
    $(document).on('click', '.btn-show', function() {

        let url = $(this).data('url');

        console.log('URL:', url);

        $.ajax({
            url: url,
            type: 'GET',

            success: function(response) {
                console.log(response);
                $('#detail-barjas-content').html(response);
            },

            error: function(xhr) {

                console.log(xhr);

                $('#detail-barjas-content').html(`
                <div class="alert alert-danger m-5">
                    Error ${xhr.status}<br>
                    ${xhr.responseText}
                </div>
            `);
            }
        });

    });
</script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.btn-submit', function() {

        let url = $(this).data('url');

        Swal.fire({
            title: 'Ajukan RKBU?',
            text: 'RKBU akan masuk proses validasi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ajukan',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: url,
                    type: 'POST',
                    success: function(response) {

                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        );

                        $('.dataTable').DataTable().ajax.reload();
                    },

                    // error: function(xhr) {

                    //     Swal.fire(
                    //         'Error!',
                    //         'Terjadi kesalahan',
                    //         'error'
                    //     );

                    // }

                   error: function(xhr) {

    console.log('STATUS:', xhr.status);
    console.log('RESPONSE:', xhr.responseText);

    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: '<pre style="text-align:left;max-height:400px;overflow:auto;">'
            + xhr.responseText +
            '</pre>'
    });

}
                });

            }
        });
    });

    function barjasSelectionTable() {
        const selector = $('#barjas-bulk-submit').data('table');
        return selector && $.fn.DataTable.isDataTable(selector) ? $(selector).DataTable() : null;
    }

    function updateBarjasSelection() {
        const table = barjasSelectionTable();
        if (!table) return;
        const selectedCount = table.$('input[type="checkbox"]:checked:not(:disabled):not(#barjas-select-all)').length;
        $('#barjas-selected-count').text(selectedCount + ' dipilih');
        $('#barjas-bulk-submit').prop('disabled', selectedCount === 0);
    }

    $(document).on('change', '#rkbumodalkantor-table input[type="checkbox"]:not(#barjas-select-all), #rkbubarjas-table input[type="checkbox"]:not(#barjas-select-all)', updateBarjasSelection);
    $(document).on('change', '#barjas-select-all', function() {
        const table = barjasSelectionTable();
        if (!table) return;
        table.$('input[type="checkbox"]:not(:disabled):not(#barjas-select-all)')
            .prop('checked', this.checked);
        updateBarjasSelection();
    });
    $('#rkbumodalkantor-table, #rkbubarjas-table').on('draw.dt', function() {
        $('#barjas-select-all').prop('checked', false);
        updateBarjasSelection();
    });

    $(document).on('click', '#barjas-bulk-submit', function() {
        const table = barjasSelectionTable();
        if (!table) return;
        const ids = table
            .$('input[type="checkbox"]:checked:not(:disabled):not(#barjas-select-all)')
            .map(function() { return this.value; })
            .get();

        if (!ids.length) {
            return;
        }

        Swal.fire({
            title: 'Ajukan RKBU terpilih?',
            text: ids.length + ' data akan diajukan untuk proses validasi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ajukan',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: $('#barjas-bulk-submit').data('submit-url'),
                type: 'POST',
                data: { ids: ids },
                success: function(response) {
                    table.ajax.reload();
                    const failures = (response.results || []).filter(function(item) {
                        return !item.success;
                    });
                    const detail = failures.map(function(item) {
                        return $('<div>').text(item.message).html();
                    }).join('<br>');

                    Swal.fire({
                        icon: failures.length ? 'warning' : 'success',
                        title: failures.length ? 'Sebagian pengajuan gagal' : 'Berhasil',
                        html: response.message + (detail ? '<br><br>' + detail : '')
                    });
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal mengajukan RKBU terpilih.');
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('sisa_stok_rkbu')) {
            return;
        }

        const listenIds = [
            'sisa_stok_rkbu',
            'pengadaan_sebelumnya_rkbu',
            'rata_rata_pemakaian_rkbu',
            'kebutuhan_per_bulan_rkbu',
            'buffer_rkbu',
            'is_ppn'
        ];

        listenIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', hitungRKBU);
                el.addEventListener('change', hitungRKBU);
            }
        });

        // === FORMAT RUPIAH ===
        const hargaInput = document.getElementById('harga_satuan');
        const hargaHidden = document.getElementById('harga_satuan_number_rkbu');

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        if (hargaInput && hargaHidden) {
            const hargaAwal = hargaInput.value.replace(/\D/g, '');
            if (hargaAwal) {
                hargaHidden.value = hargaAwal;
                hargaInput.value = formatRupiah(hargaAwal);
            } else if (hargaHidden.value) {
                hargaInput.value = formatRupiah(hargaHidden.value.replace(/\D/g, ''));
            }

            hargaInput.addEventListener('input', function() {
                const raw = this.value.replace(/\D/g, '');
                hargaHidden.value = raw;
                this.value = formatRupiah(raw);
                hitungRKBU();
            });
        }

        function getVal(id) {
            return parseFloat(document.getElementById(id)?.value) || 0;
        }

        function getHarga() {
            return parseFloat(hargaHidden?.value) || 0;
        }

        function setRupiah(id, value) {
            const el = document.getElementById(id);
            if (el) el.value = formatRupiah(Math.max(0, Math.round(value)));
        }

        function hitungRKBU() {

            const sisaStok = getVal('sisa_stok_rkbu');
            const pengadaan = getVal('pengadaan_sebelumnya_rkbu');
            const rata = getVal('rata_rata_pemakaian_rkbu');
            const kebutuhan = getVal('kebutuhan_per_bulan_rkbu');
            const buffer = getVal('buffer_rkbu');
            const harga = getHarga();
            const ppn = document.getElementById('is_ppn')?.checked ? getVal('ppn') : 0;

            // 1️⃣ Prediksi Sisa Stok
            const proyeksiSisaStok =
                (sisaStok + pengadaan) - (rata * kebutuhan);

            // 2️⃣ Kebutuhan Tahun
            const kebutuhanTahun =
                rata * kebutuhan;

            // 3️⃣ Kebutuhan + Buffer
            const kebutuhanPlusBuffer =
                kebutuhanTahun + (rata * buffer);

            // 4️⃣ Rencana Pengadaan
            const rencanaPengadaan =
                kebutuhanPlusBuffer - proyeksiSisaStok;

            // 5️⃣ Total Anggaran + PPN
            const subtotal = Math.max(0, rencanaPengadaan) * harga;
            const totalAnggaran = subtotal + (subtotal * ppn / 100);

            setRupiah('proyeksi_sisa_stok_rkbu', proyeksiSisaStok);
            setRupiah('kebutuhan_tahun_x1_rkbu', kebutuhanTahun);
            setRupiah('kebutuhan_plus_buffer_rkbu', kebutuhanPlusBuffer);
            setRupiah('rencana_pengadaan_tahun_x1_rkbu', rencanaPengadaan);
            setRupiah('total_persediaan_rkbu', totalAnggaran);

            const totalAnggaranNumber = document.getElementById('total_persediaan_number_rkbu');
            if (totalAnggaranNumber) {
                totalAnggaranNumber.value = Math.round(totalAnggaran);
            }

            const totalAnggaranReal = document.getElementById('total_anggaran_real');
            if (totalAnggaranReal) {
                totalAnggaranReal.value = Math.round(totalAnggaran);
            }
        }

        // hitung saat load (edit data)
        hitungRKBU();
    });
</script>
