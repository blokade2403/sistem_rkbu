<script>
    $.fn.dataTable.ext.errMode = 'none';

    $('#tableApproval').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('workflowapproval.data') }}",
            data: function(data) {
                data.module = $('#approval-module-filter').val() || 'all';
                data.sumber_dana_id = $('#approval-sumber-dana-filter').val();
                data.sub_kategori_rkbu_id = $('#approval-subkategori-filter').val();
                data.approval_status = $('#approval-status-filter').val();
            },
            dataSrc: function(response) {
                if (response.notice) {
                    toastr.warning(response.notice);
                }

                return response.data || [];
            }
        },

        columns: [{
                data: 'checkbox',
                searchable: false,
                orderable: false
            },
            {
                data: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },
            {
                data: 'nama_komponen',
                orderable: false,
                searchable: true
            },
            {
                data: 'satuan',
                orderable: false,
                searchable: false
            },
            {
                data: 'harga_barang',
                orderable: false,
                searchable: false
            },
            {
                data: 'total',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                searchable: false,
                orderable: false
            }
        ]
    });

    $('#tableApproval').on('error.dt', function(event, settings) {
        const xhr = settings.jqXHR;
        const responseMessage = xhr?.status < 500 ? xhr?.responseJSON?.message : null;
        const errorMessage = responseMessage ||
            `Data approval gagal dimuat (HTTP ${xhr?.status || 'tidak diketahui'}). Silakan coba lagi atau hubungi administrator.`;

        toastr.error(errorMessage);
    });

    $('#approval-module-filter, #approval-sumber-dana-filter, #approval-subkategori-filter, #approval-status-filter').on('change', function() {
        $('#tableApproval').DataTable().ajax.reload();
    });

    function updateApprovalSelection() {
        const table = $('#tableApproval').DataTable();
        const selectedCount = table.$('.approval-row-checkbox:checked').length;
        $('#approval-selected-count').text(selectedCount + ' dipilih');
        $('#approval-bulk-approve, #approval-bulk-revision, #approval-bulk-reject')
            .prop('disabled', selectedCount === 0);
    }

    $(document).on('change', '#tableApproval .approval-row-checkbox', updateApprovalSelection);
    $(document).on('change', '#approval-select-all', function() {
        const table = $('#tableApproval').DataTable();
        table.$('.approval-row-checkbox:not(:disabled)').prop('checked', this.checked);
        updateApprovalSelection();
    });
    $('#tableApproval').on('draw.dt', function() {
        $('#approval-select-all').prop('checked', false);
        updateApprovalSelection();
    });

    $(document).on('click', '#approval-bulk-approve, #approval-bulk-revision, #approval-bulk-reject', function() {
        const action = this.id.replace('approval-bulk-', '');
        const ids = $('#tableApproval').DataTable()
            .$('.approval-row-checkbox:checked')
            .map(function() { return this.value; })
            .get();

        if (!ids.length) {
            return;
        }

        const needsNote = action !== 'approve';
        Swal.fire({
            title: action === 'approve' ? 'Approve semua yang dipilih?' :
                (action === 'revision' ? 'Kembalikan semua yang dipilih untuk revisi?' : 'Reject semua yang dipilih?'),
            input: needsNote ? 'textarea' : undefined,
            inputLabel: needsNote ? 'Catatan wajib diisi' : undefined,
            inputPlaceholder: needsNote ? 'Tulis catatan...' : undefined,
            inputValidator: value => needsNote && !value ? 'Catatan wajib diisi.' : undefined,
            showCancelButton: true,
            confirmButtonText: action === 'approve' ? 'Approve' :
                (action === 'revision' ? 'Revision' : 'Reject'),
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '{{ route('workflowapproval.bulk-action') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids,
                    action: action,
                    note: result.value || ''
                },
                success: function(response) {
                    $('#tableApproval').DataTable().ajax.reload();
                    const failures = (response.results || []).filter(function(item) {
                        return !item.success;
                    });
                    const detail = failures.map(function(item) {
                        return $('<div>').text(item.message).html();
                    }).join('<br>');
                    Swal.fire({
                        icon: failures.length ? 'warning' : 'success',
                        title: failures.length ? 'Sebagian approval gagal' : 'Berhasil',
                        html: response.message + (detail ? '<br><br>' + detail : '')
                    });
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal memproses approval terpilih.');
                }
            });
        });
    });
</script>

<script>
    $(document).on('click', '.btn-approve, .btn-revision, .btn-reject', function() {
        const button = $(this);
        const action = button.hasClass('btn-approve') ? 'approve' :
            (button.hasClass('btn-revision') ? 'revision' : 'reject');
        const needsNote = action !== 'approve';

        Swal.fire({
            title: action === 'approve' ? 'Setujui RKBU?' :
                (action === 'revision' ? 'Kembalikan untuk revisi?' : 'Tolak RKBU?'),
            input: 'textarea',
            inputLabel: needsNote ? 'Catatan wajib diisi' : 'Catatan (opsional)',
            inputPlaceholder: 'Tulis catatan...',
            showCancelButton: true,
            confirmButtonText: action === 'approve' ? 'Approve' :
                (action === 'revision' ? 'Revision' : 'Reject'),
            cancelButtonText: 'Batal',
            inputValidator: value => needsNote && !value ? 'Catatan wajib diisi.' : undefined
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: button.data('url'),
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    note: result.value || ''
                },
                success: function(response) {
                    $('#tableApproval').DataTable().ajax.reload();
                    toastr.success(response.message || 'Status approval berhasil diperbarui.');
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal memproses approval.');
                }
            });
        });
    });
</script>

<script>
    
    
    let approvalId = null;

    $(document).on('click', '.btn-detail', function() {

        approvalId = $(this).data('id');

       $.get($(this).data('url'), function(res) {

    console.log(res);

    // ==========================
    // Header RKBU
    // ==========================
    $('#detail_nomor_rkbu').text(res.rkbu?.nomor_rkbu ?? '-');

    $('#detail_unit').text(res.rkbu?.unit?.nama_unit ?? '-');

    $('#detail_tahun').text(res.rkbu?.tahun_anggaran?.nama_tahun_anggaran ?? '-');

    $('#detail_validator').text(res.approval?.validatorRole?.nama_validator ?? '-');

    $('#detail_step').text(res.approval?.workflowStep?.level_jabatan ?? '-');

    $('#detail_status').html(
        `<span class="badge badge-warning">
            ${res.approval?.workflowInstance?.status ?? '-'}
        </span>`
    );

    $('#detail_tanggal').text(res.approval?.created_at ?? '-');
    $('#approval_note').val('');
    $('#btnApprove, #btnRevision, #btnReject').toggle(Boolean(res.canApprove));

    $('#detail_module').text(res.module || '');
    const columns = res.detailColumns || [];
    const header = $('<tr>');
    $('<th>').text('No').appendTo(header);
    columns.forEach(function(column) {
        $('<th>').text(column).appendTo(header);
    });
    $('#theadDetailApproval').empty().append(header);

    const body = $('#tbodyBarang').empty();
    (res.details || []).forEach(function(item, index) {
        const row = $('<tr>');
        $('<td>').text(index + 1).appendTo(row);
        columns.forEach(function(column) {
            const value = item[column];
            const isAmount = /Anggaran|Harga|Gaji|Remunerasi/i.test(column);
            const displayValue = value === null || value === undefined || value === ''
                ? '-'
                : (isAmount && !Number.isNaN(Number(value))
                    ? Number(value).toLocaleString('id-ID')
                    : value);
            $('<td>').text(displayValue).appendTo(row);
        });
        body.append(row);
    });

    $('#btnEditRkbu').toggle(Boolean(res.canEdit));
    $('#rkbu-edit-panel').hide();
    $('#btnCancelEditRkbu, #btnSaveRkbu').hide();
    $('#btnEditRkbu').show();
    $('#rkbu-edit-form').data('url', res.updateUrl);
    $('#approval-next-official-wrapper').toggle(Boolean(res.requiresNextOfficial));

    const sourceSelect = $('#edit_sumber_dana_id').empty()
        .append($('<option>').val('').text('Pilih sumber dana'));
    (res.sumberDanas || []).forEach(function(source) {
        sourceSelect.append($('<option>').val(source.id_sumber_dana).text(source.nama_sumber_dana));
    });
    sourceSelect.val(res.rkbu?.sumber_dana_id || '');

    const subActivities = res.subKegiatans || [];
    window.populateApprovalSubActivities = function(selectedId) {
        const sourceId = sourceSelect.val();
        const subActivitySelect = $('#edit_sub_kegiatan_id').empty()
            .append($('<option>').val('').text('Pilih sub kegiatan'));
        subActivities.filter(function(item) {
            return !sourceId || item.id_sumber_dana === sourceId;
        }).forEach(function(item) {
            const label = [item.kode_sub_kegiatan, item.nama_sub_kegiatan].filter(Boolean).join(' - ');
            subActivitySelect.append($('<option>').val(item.id_sub_kegiatan).text(label));
        });
        subActivitySelect.val(selectedId || '');
    };
    window.populateApprovalSubActivities(res.rkbu?.sub_kegiatan_id || '');

    const editableDetails = $('#rkbu-edit-details').empty();
    const numericDetailFields = [
        'jumlah_volume', 'bulan_tmt', 'tahun_tmt', 'volume_1', 'volume_2',
        'harga_satuan', 'ppn', 'total_anggaran', 'rating', 'sisa_vol_rkbu',
        'sisa_anggaran_rkbu', 'stok', 'rata_rata_pemakaian', 'kebutuhan_per_bulan',
        'buffer', 'pengadaan_sebelumnya', 'proyeksi_sisa_stok', 'kebutuhan_plus_buffer',
        'kebutuhan_tahun_x1', 'rencana_pengadaan_tahun_x1', 'gaji_pokok', 'remunerasi',
        'koefisien_remunerasi', 'koefisien_gaji', 'bpjs_kesehatan', 'bpjs_tk',
        'bpjs_jht', 'total_gaji_pokok', 'total_remunerasi', 'eksisting',
        'kondisi_baik', 'kondisi_rusak_berat'
    ];
    (res.editableDetails || []).forEach(function(detail, index) {
        const card = $('<div class="card card-bordered rkbu-edit-detail">').attr('data-id', detail.id);
        const cardBody = $('<div class="card-body">').appendTo(card);
        $('<h6 class="mb-4">').text('Komponen ' + (index + 1)).appendTo(cardBody);
        const fieldsRow = $('<div class="row g-3">').appendTo(cardBody);
        Object.keys(detail.fields || {}).forEach(function(field) {
            const value = detail.fields[field];
            const fieldGroup = $('<div class="col-md-6">').appendTo(fieldsRow);
            const label = field.replace(/_/g, ' ').replace(/\b\w/g, function(letter) {
                return letter.toUpperCase();
            });
            $('<label class="form-label">').text(label).appendTo(fieldGroup);
            $('<input class="form-control rkbu-edit-value">')
                .attr({
                    'data-field': field,
                    type: numericDetailFields.includes(field) ? 'number' : 'text',
                    step: numericDetailFields.includes(field) ? 'any' : undefined
                })
                .val(value === null || value === undefined ? '' : value)
                .appendTo(fieldGroup);
        });
        editableDetails.append(card);
    });

    const nextOfficialSelect = $('#approval_next_official').empty()
        .append($('<option>').val('').text('Pilih pejabat aktif'));
    (res.nextOfficials || []).forEach(function(official) {
        nextOfficialSelect.append($('<option>').val(official.id).text(official.label));
    });

    const attachments = $('#approval_attachments').empty();
    if ((res.attachments || []).length) {
        $('<h6>').text('Lampiran KAP').appendTo(attachments);
        const list = $('<div class="d-flex flex-wrap gap-3">').appendTo(attachments);
        res.attachments.forEach(function(attachment) {
            $('<a>')
                .attr({
                    href: attachment.url,
                    target: '_blank',
                    rel: 'noopener'
                })
                .text(attachment.label)
                .appendTo(list);
        });
    }

    const modal = new bootstrap.Modal(
        document.getElementById('modalDetailApproval')
    );

    modal.show();

});

    });
</script>

<script>
    $('#btnEditRkbu').on('click', function() {
        $('#rkbu-edit-panel').show();
        $('#btnEditRkbu').hide();
        $('#btnCancelEditRkbu, #btnSaveRkbu').show();
    });

    $('#btnCancelEditRkbu').on('click', function() {
        $('#rkbu-edit-panel').hide();
        $('#btnEditRkbu').show();
        $('#btnCancelEditRkbu, #btnSaveRkbu').hide();
    });

    $('#edit_sumber_dana_id').on('change', function() {
        if (typeof window.populateApprovalSubActivities === 'function') {
            window.populateApprovalSubActivities('');
        }
    });

    $('#btnSaveRkbu').on('click', function() {
        const details = $('#rkbu-edit-details .rkbu-edit-detail').map(function() {
            const fields = {};
            $(this).find('.rkbu-edit-value').each(function() {
                fields[$(this).data('field')] = $(this).val();
            });
            return {
                id: $(this).data('id'),
                fields: fields
            };
        }).get();

        $.ajax({
            url: $('#rkbu-edit-form').data('url'),
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sumber_dana_id: $('#edit_sumber_dana_id').val(),
                sub_kegiatan_id: $('#edit_sub_kegiatan_id').val(),
                details: details
            },
            success: function(response) {
                $('#modalDetailApproval').modal('hide');
                $('#tableApproval').DataTable().ajax.reload();
                toastr.success(response.message || 'Data RKBU berhasil diperbarui.');
            },
            error: function(xhr) {
                const validationMessage = Object.values(xhr.responseJSON?.errors || {})
                    .flat()
                    .join(' ');
                toastr.error(validationMessage || xhr.responseJSON?.message || 'Gagal memperbarui data RKBU.');
            }
        });
    });
</script>

<script>
    
    $('#btnApprove').click(function() {

        const nextOfficial = $('#approval_next_official').val();
        if ($('#approval-next-official-wrapper').is(':visible') && !nextOfficial) {
            toastr.error('Pilih pejabat pada hirarki berikutnya sebelum melakukan approval.');
            return;
        }

        $.ajax({

            url: '/workflow/approval/' + approvalId + '/approve',

            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                note: $('#approval_note').val(),
                next_assignment_id: nextOfficial
            },

            success: function() {

                $('#modalDetailApproval').modal('hide');

                $('#tableApproval').DataTable().ajax.reload();

                toastr.success('Approval berhasil');

            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Gagal memproses approval.');
            }

        });

    });
</script>

<script>
    $('#btnReject').click(function() {

        if ($('#approval_note').val() == '') {
            toastr.error('Catatan reject wajib diisi');
            return;
        }

        $.ajax({

            url: '/workflow/approval/' + approvalId + '/reject',

            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                note: $('#approval_note').val()
            },

            success: function() {

                $('#modalDetailApproval').modal('hide');

                $('#tableApproval').DataTable().ajax.reload();

                toastr.success('Dokumen berhasil direject');

            }

        });

    });
</script>

<script>
   $('#btnRevision').click(function() {

    if ($('#approval_note').val() == '') {
        toastr.error('Catatan revisi wajib diisi');
        return;
    }

    $.ajax({

        url: '/workflow/approval/' + approvalId + '/revision',

        type: 'POST',

        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            note: $('#approval_note').val()
        },

        success: function(res) {

            $('#modalDetailApproval').modal('hide');

            $('#tableApproval')
                .DataTable()
                .ajax.reload();

            toastr.success(res.message);
        },

        error: function(xhr) {

            console.log(xhr);

            toastr.error(
                xhr.responseJSON?.message ??
                'Terjadi kesalahan'
            );
        }
    });
});
</script>

<script>
    $('#validator_type').change(function(){

    let type = $(this).val();

    $('#hierarchy_area').hide();

    $('#position_area').hide();

    $('#role_area').hide();

    if(type=='hierarchy'){

        $('#hierarchy_area').show();

    }

    if(type=='position'){

        $('#position_area').show();

    }

    if(type=='role'){

        $('#role_area').show();

    }

});

$('#validator_type').trigger('change');
</script>

{{-- <script>
    
  $(document).on('click', '.btn-detail', function() {

    console.log('DETAIL CLICK');

    let url = $(this).data('url');

    console.log(url);

    $.ajax({
        url: url,
        type: 'GET',

        success: function(res) {

            console.log(res);

            $('#nomor_rkbu').text(res.rkbu.nomor_rkbu ?? '-');
            $('#unit').text(res.rkbu.unit?.nama_unit ?? '-');
            $('#tahun').text(res.rkbu.tahun_anggaran?.tahun ?? '-');

            let html = '';

            $.each(res.rkbu.detail_barjas ?? [], function(i, item) {

                html += `
                    <tr>
                        <td>${item.nama_barang ?? ''}</td>
                        <td>${item.spesifikasi ?? ''}</td>
                        <td>${item.volume_1 ?? ''}</td>
                        <td>${item.harga_satuan ?? ''}</td>
                    </tr>
                `;
            });

            $('#tableDetailBarang tbody').html(html);

            let modal = new bootstrap.Modal(
                document.getElementById('modalDetailApproval')
            );

            modal.show();
        },

        error: function(xhr) {

            console.log(xhr);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message ??
                    xhr.responseText
            });
        }
    });

});
</script> --}}
