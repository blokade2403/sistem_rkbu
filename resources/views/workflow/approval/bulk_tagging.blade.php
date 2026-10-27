<div class="modal fade" id="bulkTaggingModal" tabindex="-1" aria-labelledby="bulkTaggingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="bulkTaggingModalLabel">Tagging Sumber Dana dan Sub Kegiatan Massal</h3>
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="ki-duotone ki-cross fs-2"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light-info">
                    Sistem menerapkan pilihan ke semua RKBU tahun aktif yang cocok dengan filter halaman Approval. Untuk memisahkan kelompok dengan tagging berbeda, pilih subkegiatan saat ini (dan filter lain bila perlu), hitung cakupannya, lalu terapkan pilihan tujuan untuk kelompok tersebut. Tidak ada checkbox atau pemilihan satu per satu.
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="bulk_target_sumber_dana" class="form-label required">Sumber Dana Baru</label>
                        <select id="bulk_target_sumber_dana" class="form-select">
                            <option value="">Pilih sumber dana</option>
                            @foreach ($sumberDanas as $sumberDana)
                                <option value="{{ $sumberDana->id_sumber_dana }}">{{ $sumberDana->nama_sumber_dana }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bulk_target_sub_kegiatan" class="form-label required">Sub Kegiatan Baru</label>
                        <select id="bulk_target_sub_kegiatan" class="form-select" disabled>
                            <option value="">Pilih sumber dana terlebih dahulu</option>
                        </select>
                    </div>
                </div>
                <div id="bulk-tagging-preview" class="mt-5" aria-live="polite"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-light-primary" id="bulk-tagging-preview-button">Hitung RKBU yang Cocok</button>
                <button type="button" class="btn btn-primary" id="bulk-tagging-apply-button" disabled>Terapkan ke RKBU</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function($) {
        const subActivities = {{ $subKegiatanOptions }};
        let previewScope = null;
        let previewCount = 0;

        function populateTargetSubActivities() {
            const sourceId = $('#bulk_target_sumber_dana').val();
            const select = $('#bulk_target_sub_kegiatan').empty();
            if (!sourceId) {
                select.append($('<option>').val('').text('Pilih sumber dana terlebih dahulu')).prop('disabled', true);
                return;
            }

            select.prop('disabled', false).append($('<option>').val('').text('Pilih sub kegiatan'));
            subActivities.filter(item => item.sourceId === sourceId).forEach(item => {
                select.append($('<option>').val(item.id).text([item.code, item.name].filter(Boolean).join(' - ')));
            });
        }

        function currentScope() {
            return {
                module: $('#approval-module-filter').val() || 'all',
                filter_sumber_dana_id: $('#approval-sumber-dana-filter').val() || '',
                filter_sub_kegiatan_id: $('#approval-sub-kegiatan-filter').val() || '',
                sub_kategori_rkbu_id: $('#approval-subkategori-filter').val() || '',
                approval_status: $('#approval-status-filter').val() || '',
            };
        }

        function requestData() {
            return Object.assign({}, previewScope || currentScope(), {
                target_sumber_dana_id: $('#bulk_target_sumber_dana').val(),
                target_sub_kegiatan_id: $('#bulk_target_sub_kegiatan').val(),
                _token: $('meta[name="csrf-token"]').attr('content'),
            });
        }

        $('#bulk_target_sumber_dana').on('change', function() {
            populateTargetSubActivities();
            previewScope = null;
            previewCount = 0;
            $('#bulk-tagging-preview').empty();
            $('#bulk-tagging-apply-button').prop('disabled', true);
        });

        $('#bulk_target_sub_kegiatan').on('change', function() {
            previewScope = null;
            previewCount = 0;
            $('#bulk-tagging-preview').empty();
            $('#bulk-tagging-apply-button').prop('disabled', true);
        });

        $('#bulk-tagging-preview-button').on('click', function() {
            if (!$('#bulk_target_sumber_dana').val() || !$('#bulk_target_sub_kegiatan').val()) {
                toastr.warning('Pilih sumber dana dan sub kegiatan tujuan terlebih dahulu.');
                return;
            }

            previewScope = currentScope();
            const button = $(this).prop('disabled', true).text('Menghitung...');
            $.ajax({
                url: '{{ route('workflowapproval.bulk-tagging.preview') }}',
                type: 'POST',
                data: requestData(),
                success: function(response) {
                    previewCount = Number(response.count || 0);
                    $('#bulk-tagging-preview').html(previewCount
                        ? `<div class="alert alert-warning mb-0"><strong>${previewCount.toLocaleString('id-ID')} RKBU</strong> akan diperbarui. Nilai sumber dana dan sub kegiatan pada seluruh RKBU tersebut akan diganti.</div>`
                        : '<div class="alert alert-light-warning mb-0">Tidak ada RKBU yang cocok dengan filter halaman saat ini.</div>');
                    $('#bulk-tagging-apply-button').prop('disabled', previewCount === 0);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal menghitung RKBU yang cocok.');
                },
                complete: function() {
                    button.prop('disabled', false).text('Hitung RKBU yang Cocok');
                }
            });
        });

        $('#bulk-tagging-apply-button').on('click', function() {
            if (!previewCount || !previewScope) return;
            Swal.fire({
                title: `Terapkan tagging ke ${previewCount.toLocaleString('id-ID')} RKBU?`,
                text: 'Sumber dana dan sub kegiatan saat ini pada RKBU yang cocok akan diganti.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, terapkan',
                cancelButtonText: 'Batal',
            }).then(function(result) {
                if (!result.isConfirmed) return;
                const button = $('#bulk-tagging-apply-button').prop('disabled', true).text('Menerapkan...');
                $.ajax({
                    url: '{{ route('workflowapproval.bulk-tagging') }}',
                    type: 'POST',
                    data: requestData(),
                    success: function(response) {
                        $('#bulkTaggingModal').modal('hide');
                        $('#tableApproval').DataTable().ajax.reload();
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Gagal memperbarui tagging RKBU.');
                    },
                    complete: function() {
                        button.prop('disabled', false).text('Terapkan ke RKBU');
                    }
                });
            });
        });
    })(jQuery);
</script>
@endpush
