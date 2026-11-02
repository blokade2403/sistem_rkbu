@php
    $step = $step ?? ($spj->pengadaan_step ?: 'input_pesanan');
    $steps = $steps ?? [];
    $logs = $logs ?? collect();
    $workflowStep = $workflowStep ?? null;
    $workflowStepName = $workflowStepName ?? null;
    $canPesanan = $canPesanan ?? false;
    $canFoto = $canFoto ?? false;
    $canValidasi = $canValidasi ?? false;
    $canBast = $canBast ?? false;
    $canTukarFaktur = $canTukarFaktur ?? false;
    $canPenyerahanSpjAdmin = $canPenyerahanSpjAdmin ?? false;
    $canLanjutan = $canLanjutan ?? false;
    $canWorkflowStep = $canWorkflowStep ?? false;
    $masterInfo = $masterInfo ?? null;
    $latestPaymentInfo = $latestPaymentInfo ?? null;
    $statusPembayaranText = strtolower(trim((string) ($masterInfo?->status_pembayaran ?? '')));
    $pembayaranSelesai = in_array($statusPembayaranText, ['selesai', 'sudah di bayar', 'sudah dibayar'], true)
        || ($step === 'selesai' && filled($masterInfo?->tanggal_pembayaran) && (float) ($masterInfo?->sisa_pembayaran ?? 0) <= 0);
    $netto = $netto ?? (float) $spj->details->sum(fn ($detail) => (float) ($detail->item?->harga_satuan ?? 0) * (float) $detail->volume);
    $nilaiPpn = $nilaiPpn ?? (float) $spj->details->sum(fn ($detail) => (float) ($detail->item?->harga_satuan ?? 0) * (float) $detail->volume * (float) ($detail->item?->ppn ?? 0) / 100);
    $totalAnggaran = $totalAnggaran ?? ($netto + $nilaiPpn);
    $storagePublicUrl = static function ($path) {
        $path = str_replace('\\', '/', trim((string) $path));
        if (preg_match('/^(https?:)?\\/\\//i', $path)) return $path;
        $path = ltrim($path, '/');
        $path = preg_replace('#^(public/|storage/)#', '', $path);
        return asset('storage/' . $path);
    };
@endphp

<style>
    .spj-stage-form-card { overflow: hidden; border: 1px solid #e7edf5 !important; border-radius: 16px; box-shadow: 0 8px 24px rgba(31,45,61,.045); }
    .spj-stage-form-card > .card-header { min-height: 72px; padding: 1.2rem 1.7rem; border-bottom: 1px solid #edf1f7; background: linear-gradient(110deg, #f2f7ff 0%, #fff 75%); }
    .spj-stage-form-card > .card-header .card-title { color: #1e293b; font-weight: 700; }
    .spj-stage-form-card > .card-body { padding: clamp(1.25rem, 2.2vw, 2rem); }
    .spj-stage-form-card .form-label { color: #344054; font-weight: 600; }
    .spj-stage-form-card .form-control, .spj-stage-form-card .form-select { min-height: 46px; border-radius: 10px; border-color: #e1e7f0; transition: border-color .15s ease, box-shadow .15s ease; }
    .spj-stage-form-card textarea.form-control { min-height: 110px; }
    .spj-stage-form-card .form-control:focus, .spj-stage-form-card .form-select:focus { border-color: #93b7ff; box-shadow: 0 0 0 .2rem rgba(54, 110, 255, .1); }
    .spj-stage-form-card form > .btn-primary, .spj-stage-form-card form .border-top > .btn-primary { padding: .75rem 1.25rem; border-radius: 10px; font-weight: 650; box-shadow: 0 5px 12px rgba(0, 123, 255, .14); }
    @media (max-width: 575.98px) { .spj-stage-form-card > .card-header { padding: 1rem 1.2rem; } }
</style>

 <div class="card card-bordered shadow-sm rounded-3 spj-workflow-process-card">
     <div class="card-header bg-light-primary border-0">
         <h4 class="card-title">Alur Pengadaan — {{ $step === 'workflow' ? ($workflowStepName ?? 'Validasi Workflow Pengadaan') : ($steps[$step] ?? $step) }}</h4>
     </div>
     <div class="card-body">
         @if (session('success'))
             <div class="alert alert-success">{{ session('success') }}</div>
         @endif
         @if ($errors->any())
             <div class="alert alert-danger">
                 <ul class="mb-0">
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div>
         @endif
         <div class="alert alert-light-primary"><strong>Tahap:</strong> {{ $step === 'workflow' ? ($workflowStepName ?? 'Validasi Workflow Pengadaan') : ($steps[$step] ?? $step) }} <span
                 class="mx-2">|</span><strong>Subkategori:</strong>
             {{ $spj->usulanBelanja?->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }} <span
                 class="mx-2">|</span><strong>Pengusul:</strong> {{ $spj->usulanBelanja?->nama_pengusul ?? '-' }}
         </div>
         <h5 class="mb-3">Item SPJ</h5>
         <ul class="mb-6">
             @forelse($spj->details as $detail)
                 <li>{{ $detail->rkbu?->nama_barang ?? ($detail->rkbu?->nomor_rkbu ?? $detail->rkbu_id) }} — volume
                 {{ $detail->volume }}</li>@empty<li>Belum ada detail.</li>
             @endforelse
         </ul>
         @if ($canPesanan && $step === 'input_pesanan')
             <div class="card card-bordered spj-stage-form-card mb-6">
                 <div class="card-header">
                     <h4 class="card-title">1. Input Surat Pesanan</h4>
                 </div>
                 <div class="card-body">
                     <form method="POST" action="{{ route('usulan-belanja.pengadaan.pesanan', $spj->id) }}">@csrf
                         <div class="row g-4">
                             <div class="col-md-4 mb-4"><label class="form-label">Nomor Surat Pesanan</label><input
                                     name="no_surat_pesanan" class="form-control form-control-solid"
                                     value="{{ old('no_surat_pesanan', $spj->no_surat_pesanan) }}" required></div>
                             <div class="col-md-4 mb-4"><label class="form-label">Tanggal Surat Pesanan</label><input
                                     type="date" name="tgl_surat_pesanan" class="form-control form-control-solid"
                                     value="{{ old('tgl_surat_pesanan', $spj->tgl_surat_pesanan) }}"></div>
                             <div class="col-md-4 mb-4"><label class="form-label">Batas Waktu Pekerjaan</label><input
                                     type="date" name="jangka_waktu_pekerjaan" class="form-control form-control-solid"
                                     value="{{ old('jangka_waktu_pekerjaan', $masterInfo->jangka_waktu_pekerjaan ?? '') }}"><div class="form-text">Digunakan pada surat pesanan.</div></div>
                             <div class="col-md-4 mb-4"><label class="form-label">ID Paket</label><input name="idpaket"
                                     class="form-control form-control-solid" value="{{ old('idpaket', $spj->idpaket) }}" required></div>
                         </div>
                         <button class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Simpan dan teruskan</button>
                     </form>
                 </div>
             </div>
         @endif

         @if ($step !== 'input_pesanan')
             <div class="card card-bordered mb-6">
                 <div class="card-header">
                     <h4 class="card-title">Data Pesanan</h4>
                 </div>
                 <div class="card-body">
                     <dl class="row mb-0">
                         <dt class="col-sm-3">Nomor</dt>
                         <dd class="col-sm-9">{{ $spj->no_surat_pesanan ?? '-' }}</dd>
                         <dt class="col-sm-3">Tanggal</dt>
                         <dd class="col-sm-9">{{ $spj->tgl_surat_pesanan ?? '-' }}</dd>
                         <dt class="col-sm-3">ID Paket</dt>
                         <dd class="col-sm-9">{{ $spj->idpaket ?? '-' }}</dd>
                     </dl>
                     <div class="d-flex flex-wrap gap-2 mt-4"><a class="btn btn-light-primary" target="_blank" rel="noopener" href="{{ route('usulan-belanja.pengadaan.document', ['spj' => $spj->id, 'document' => 'surat-pesanan']) }}"><i class="fas fa-print me-2"></i>Print Surat Pesanan</a><a class="btn btn-primary" href="{{ route('usulan-belanja.pengadaan.document', ['spj' => $spj->id, 'document' => 'surat-pesanan', 'download' => 1]) }}"><i class="fas fa-download me-2"></i>Download .doc</a></div>
                 </div>
             </div>
         @endif

         @if (($canFoto && $step === 'upload_foto') || $spj->foto_barang_datang)
             @include('usulan_belanja.pengadaan._foto_barang_datang', [
                 'spj' => $spj,
                 'canFoto' => $canFoto,
                 'step' => $step,
                 'logs' => $logs,
                 'masterInfo' => $masterInfo,
                 'totalAnggaran' => $totalAnggaran,
             ])
         @endif

         @if ($canValidasi && $step === 'validasi_barang')
             <div class="card card-bordered spj-stage-form-card border-primary border-opacity-25 shadow-sm rounded-4 mb-6 overflow-hidden">
                 <div class="card-header bg-light-primary border-0 py-5">
                     <div class="d-flex align-items-center gap-4">
                         <span class="symbol symbol-45px symbol-circle flex-shrink-0"><span class="symbol-label bg-primary text-white"><i class="fas fa-boxes text-white fs-4"></i></span></span>
                         <div><div class="text-primary text-uppercase fw-bold fs-8 mb-1">Pemeriksaan Barang</div><h4 class="card-title mb-0">3. Validasi Pengurus Barang</h4></div>
                     </div>
                 </div>
                 <div class="card-body p-6 p-lg-8">
                     <form method="POST" action="{{ route('usulan-belanja.pengadaan.validasi', $spj->id) }}">@csrf
                         <div class="mb-6">
                             <label class="form-label fw-bold fs-6 mb-3">Pilih keputusan</label>
                             <div class="row g-3">
                                 <div class="col-md-6">
                                     <input class="btn-check" type="radio" name="keputusan" id="barang-decision-approve" value="setuju" @checked(old('keputusan') === 'setuju') required>
                                     <label class="btn btn-outline btn-outline-dashed btn-outline-success d-flex align-items-center text-start p-4 h-100 w-100" for="barang-decision-approve">
                                         <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-success"><i class="fas fa-check text-success"></i></span></span>
                                         <span><span class="d-block fw-bold text-gray-900">Barang sesuai dan disetujui</span><span class="text-muted fs-7">Lanjutkan proses penerimaan barang.</span></span>
                                     </label>
                                 </div>
                                 <div class="col-md-6">
                                     <input class="btn-check" type="radio" name="keputusan" id="barang-decision-revision" value="revisi" @checked(old('keputusan') === 'revisi') required>
                                     <label class="btn btn-outline btn-outline-dashed btn-outline-warning d-flex align-items-center text-start p-4 h-100 w-100" for="barang-decision-revision">
                                         <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-warning"><i class="fas fa-undo text-warning"></i></span></span>
                                         <span><span class="d-block fw-bold text-gray-900">Kembalikan untuk perbaikan</span><span class="text-muted fs-7">Minta tindak lanjut atas barang atau dokumen.</span></span>
                                     </label>
                                 </div>
                             </div>
                         </div>
                         <div class="row g-4 mb-2">
                             <div class="col-md-6"><label class="form-label fw-semibold">Nomor BAST serah terima unit</label>
                                 <input type="text" name="no_bast_serah_terima_unit" class="form-control form-control-solid"
                                     value="{{ old('no_bast_serah_terima_unit', $spj->no_bast_serah_terima_unit) }}" maxlength="255">
                                 <div class="form-text">Wajib diisi saat barang disetujui.</div>
                             </div>
                             <div class="col-md-6"><label class="form-label fw-semibold">Nama penerima barang</label>
                                 <input type="text" name="nama_penerima_barang" class="form-control form-control-solid"
                                     value="{{ old('nama_penerima_barang', $spj->nama_penerima_barang) }}" maxlength="255">
                                 <div class="form-text">Wajib diisi saat barang disetujui.</div>
                             </div>
                         </div>
                         <div class="mb-6 mt-4"><label for="barang-validation-note" class="form-label fw-semibold">Catatan validasi</label>
                             <textarea id="barang-validation-note" name="keterangan_verif_pengurus_barang" class="form-control form-control-solid" rows="4" placeholder="Tuliskan hasil pemeriksaan atau hal yang perlu diperbaiki..." required>{{ old('keterangan_verif_pengurus_barang') }}</textarea>
                         </div>
                         <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-top pt-5">
                             <span class="text-muted fs-7"><i class="fas fa-info-circle me-1"></i>Hasil validasi akan tercatat di riwayat pengadaan.</span>
                             <button class="btn btn-primary px-6"><i class="fas fa-clipboard-check me-2"></i>Simpan validasi</button>
                         </div>
                     </form>
                 </div>
             </div>
         @elseif($spj->status_verifikasi_pengurus_barang)
             <div class="alert alert-light-info"><strong>Hasil validasi:</strong>
                 {{ $spj->status_verifikasi_pengurus_barang }} — {{ $spj->keterangan_verif_pengurus_barang }} <span
                     class="d-block mt-1">Tanggal: {{ $spj->tgl_verif_pengurus_barang }}; validator:
                     {{ $spj->validator_pengurus_barang_id }}</span>
                 <div class="mt-2">No. BAST serah terima unit: {{ $spj->no_bast_serah_terima_unit ?? '-' }}</div>
                 <div>Nama penerima barang: {{ $spj->nama_penerima_barang ?? '-' }}</div>
             </div>
         @endif

         @if ($canBast && $step === 'input_bast')
             <div class="card card-bordered spj-stage-form-card mb-6">
                 <div class="card-header">
                     <h4 class="card-title">4. Input Data BAST</h4>
                 </div>
                 <div class="card-body">
                     <form method="POST" action="{{ route('usulan-belanja.pengadaan.bast', $spj->id) }}">@csrf
                         <div class="row g-4">
                             @foreach (['tgl_bast' => 'Tanggal BAST', 'no_ba' => 'Nomor BA', 'no_ba_hp' => 'Nomor BA HP', 'no_ba_bp' => 'Nomor BA BP', 'no_dpa' => 'Nomor DPA'] as $field => $label)
                                 <div class="col-md-4"><label
                                         class="form-label">{{ $label }}</label><input
                                         name="{{ $field }}"
                                         type="{{ $field === 'tgl_bast' ? 'date' : 'text' }}" class="form-control form-control-solid"
                                         value="{{ old($field, $spj->{$field}) }}" required></div>
                             @endforeach
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-top pt-5 mt-5">
                            <span class="text-muted fs-7"><i class="fas fa-info-circle me-1"></i>Data BAST akan digunakan untuk dokumen serah terima.</span>
                            <button class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Simpan BAST dan teruskan ke PPK</button>
                        </div>
                     </form>
                 </div>
             </div>
         @elseif(!in_array($step, ['input_pesanan', 'upload_foto', 'validasi_barang', 'input_bast'], true))
             <div class="alert alert-success">
                 <h4>BAST selesai</h4>
                 <p class="mb-1">Tanggal BAST: {{ $spj->tgl_bast }} | BA: {{ $spj->no_ba }} | BA HP:
                     {{ $spj->no_ba_hp }} | BA BP: {{ $spj->no_ba_bp }} | DPA: {{ $spj->no_dpa }}</p>
                 <div class="d-flex flex-wrap gap-2 mt-3">
                     @foreach (['pemeriksaan-pekerjaan' => 'BA Pemeriksaan Pekerjaan', 'serah-terima-barang' => 'BA Serah Terima Barang/Pekerjaan', 'serah-terima-hasil' => 'BA Serah Terima Hasil Pekerjaan'] as $document => $label)
                         <a class="btn btn-sm btn-light-primary" href="{{ route('usulan-belanja.pengadaan.document', ['spj' => $spj->id, 'document' => $document]) }}" target="_blank" rel="noopener"><i class="fas fa-print me-1"></i>{{ $label }}</a>
                         <a class="btn btn-sm btn-primary" href="{{ route('usulan-belanja.pengadaan.document', ['spj' => $spj->id, 'document' => $document, 'download' => 1]) }}"><i class="fas fa-download me-1"></i>Download .doc</a>
                     @endforeach
                 </div>
             </div>
         @endif

         @if ($step === 'input_tukar_faktur')
             <div class="card card-bordered spj-stage-form-card mb-6">
                 <div class="card-header bg-light-warning">
                     <h4 class="card-title">5. Update Tukar Faktur</h4>
                 </div>
                 <div class="card-body">
                     @if ($canTukarFaktur)
                         <form method="POST" action="{{ route('usulan-belanja.pengadaan.tukar-faktur', $spj->id) }}">@csrf
                             <div class="row">
                                 <div class="col-md-4 mb-4">
                                     <label class="form-label">Tanggal Tukar Faktur</label>
                                     <input type="date" name="tanggal_faktur" class="form-control" value="{{ old('tanggal_faktur', $masterInfo->tanggal_faktur ?? '') }}" required>
                                 </div>
                                 <div class="col-md-4 mb-4">
                                     <label class="form-label">Tanggal Kwitansi Faktur</label>
                                     <input type="date" name="tgl_kwitansi" class="form-control" value="{{ old('tgl_kwitansi', $masterInfo->tgl_kwitansi ?? '') }}" required>
                                 </div>
                                 <div class="col-md-4 mb-4">
                                     <label class="form-label">Nomor Faktur</label>
                                     <input type="text" name="no_faktur" class="form-control" value="{{ old('no_faktur', $masterInfo->no_faktur ?? '') }}" maxlength="255" required>
                                 </div>
                             </div>
                             <button class="btn btn-primary">Simpan Tukar Faktur dan Teruskan</button>
                         </form>
                     @else
                         <div class="text-muted">Menunggu Admin Pengadaan mengisi tanggal tukar faktur, tanggal kwitansi, dan nomor faktur.</div>
                     @endif
                 </div>
             </div>
         @elseif(!empty($masterInfo?->status_proses_tukar_faktur))
             <div class="alert alert-light-success">
                 <strong>Tukar Faktur:</strong> {{ $masterInfo->status_proses_tukar_faktur }}
                 <span class="d-block mt-2">Tanggal Tukar Faktur: {{ $masterInfo->tanggal_faktur ?? '-' }} | Tanggal Kwitansi Faktur: {{ $masterInfo->tgl_kwitansi ?? '-' }} | Nomor Faktur: {{ $masterInfo->no_faktur ?? '-' }}</span>
             </div>
         @endif

         @if ($step === 'workflow' && $workflowStep)
            <div class="card card-bordered spj-stage-form-card border-primary border-opacity-25 shadow-sm rounded-4 mb-6 overflow-hidden">
                <div class="card-header bg-light-primary border-0 py-5">
                    <div class="d-flex align-items-center gap-4">
                        <span class="symbol symbol-45px symbol-circle flex-shrink-0">
                            <span class="symbol-label bg-primary text-white"><i class="fas fa-clipboard-check text-white fs-4"></i></span>
                        </span>
                        <div class="flex-grow-1">
                            <div class="text-primary text-uppercase fw-bold fs-8 mb-1">Persetujuan Pengadaan</div>
                            <h4 class="card-title mb-0">{{ $workflowStepName }}</h4>
                        </div>
                        <span class="badge badge-light-primary fs-7 px-3 py-2">Tahap {{ $workflowStep->step_order }}</span>
                    </div>
                </div>
                <div class="card-body p-6 p-lg-8">
                     @if ($canWorkflowStep)
                         <form method="POST" action="{{ route('usulan-belanja.pengadaan.lanjutan', $spj->id) }}">@csrf
                            <div class="mb-6">
                                <label class="form-label fw-bold fs-6 mb-3">Pilih keputusan</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input class="btn-check" type="radio" name="keputusan" id="workflow-decision-approve" value="setuju" @checked(old('keputusan') === 'setuju') required>
                                        <label class="btn btn-outline btn-outline-dashed btn-outline-success d-flex align-items-center text-start p-4 h-100 w-100" for="workflow-decision-approve">
                                            <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-success"><i class="fas fa-check text-success"></i></span></span>
                                            <span><span class="d-block fw-bold text-gray-900">Setujui dan teruskan</span><span class="text-muted fs-7">SPJ diteruskan ke tahap berikutnya.</span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="btn-check" type="radio" name="keputusan" id="workflow-decision-revision" value="revisi" @checked(old('keputusan') === 'revisi') required>
                                        <label class="btn btn-outline btn-outline-dashed btn-outline-warning d-flex align-items-center text-start p-4 h-100 w-100" for="workflow-decision-revision">
                                            <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-warning"><i class="fas fa-undo text-warning"></i></span></span>
                                            <span><span class="d-block fw-bold text-gray-900">Kembalikan untuk revisi</span><span class="text-muted fs-7">Pemohon perlu memperbaiki dokumen atau data.</span></span>
                                        </label>
                                    </div>
                                </div>
                             </div>
                            <div class="mb-6">
                                <label for="workflow-approval-note" class="form-label fw-bold">Catatan validasi <span class="text-muted fw-normal">(opsional)</span></label>
                                <textarea id="workflow-approval-note" name="catatan" class="form-control form-control-solid" rows="4" placeholder="Tuliskan catatan untuk pemohon atau tahap berikutnya...">{{ old('catatan') }}</textarea>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-top pt-5">
                                <span class="text-muted fs-7"><i class="fas fa-info-circle me-1"></i>Keputusan akan tercatat pada riwayat workflow.</span>
                                <button class="btn btn-primary px-6"><i class="fas fa-paper-plane me-2"></i>Simpan keputusan</button>
                            </div>
                         </form>
                     @else
                        <div class="d-flex align-items-center gap-3 p-4 rounded bg-light-warning">
                            <i class="fas fa-hourglass-half text-warning fs-2"></i>
                            <div><div class="fw-bold text-gray-800">Menunggu validasi</div><div class="text-muted">Menunggu persetujuan dari {{ $workflowStepName }} sesuai penugasan workflow.</div></div>
                        </div>
                     @endif
                 </div>
             </div>
         @endif

         @if ($step === 'input_penyerahan_spj_admin')
             <div class="card card-bordered spj-stage-form-card border-primary border-opacity-25 shadow-sm rounded-4 mb-6 overflow-hidden">
                 <div class="card-header bg-light-primary border-0 py-5">
                     <div class="d-flex align-items-center gap-4">
                         <span class="symbol symbol-45px symbol-circle flex-shrink-0"><span class="symbol-label bg-primary text-white"><i class="fas fa-file-alt text-white fs-4"></i></span></span>
                         <div><div class="text-primary text-uppercase fw-bold fs-8 mb-1">Pemeriksaan Dokumen</div><h4 class="card-title mb-0">Penyerahan SPJ oleh Admin Pengadaan</h4></div>
                     </div>
                 </div>
                 <div class="card-body p-6 p-lg-8">
                     @if ($canPenyerahanSpjAdmin)
                         <form method="POST" enctype="multipart/form-data" action="{{ route('usulan-belanja.pengadaan.penyerahan-dokumen', $spj->id) }}">@csrf
                             <div class="row g-4">
                                 <div class="col-md-6 mb-4">
                                     <label class="form-label fw-semibold">Tanggal Penyerahan SPJ</label>
                                     <input type="date" name="tanggal_penyerahan_spj" class="form-control form-control-solid" value="{{ old('tanggal_penyerahan_spj', $masterInfo->tanggal_penyerahan_spj ?? '') }}" required>
                                 </div>
                                 <div class="col-12">
                                     <label class="form-label fw-bold fs-6 mb-3">Status dokumen SPJ</label>
                                     <div class="row g-3">
                                         <div class="col-md-6">
                                             <input class="btn-check" type="radio" name="status_serah_terima_bendahara" id="handover-status-complete" value="Selesai" @checked(old('status_serah_terima_bendahara', $masterInfo->status_serah_terima_bendahara ?? '') === 'Selesai') required>
                                             <label class="btn btn-outline btn-outline-dashed btn-outline-success d-flex align-items-center text-start p-4 h-100 w-100" for="handover-status-complete">
                                                 <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-success"><i class="fas fa-check text-success"></i></span></span>
                                                 <span><span class="d-block fw-bold text-gray-900">Dokumen lengkap</span><span class="text-muted fs-7">Teruskan SPJ ke proses berikutnya.</span></span>
                                             </label>
                                         </div>
                                         <div class="col-md-6">
                                             <input class="btn-check" type="radio" name="status_serah_terima_bendahara" id="handover-status-revision" value="Revisi" @checked(old('status_serah_terima_bendahara', $masterInfo->status_serah_terima_bendahara ?? '') === 'Revisi') required>
                                             <label class="btn btn-outline btn-outline-dashed btn-outline-warning d-flex align-items-center text-start p-4 h-100 w-100" for="handover-status-revision">
                                                 <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-warning"><i class="fas fa-undo text-warning"></i></span></span>
                                                 <span><span class="d-block fw-bold text-gray-900">Perlu revisi</span><span class="text-muted fs-7">Kembalikan dokumen untuk diperbaiki.</span></span>
                                             </label>
                                         </div>
                                     </div>
                                 </div>
                                 @foreach (['upload_spj_1' => 'Dokumen SPJ 1', 'upload_spj_2' => 'Dokumen SPJ 2'] as $field => $label)
                                     <div class="col-md-6 mb-4">
                                         <label class="form-label fw-semibold">{{ $label }} (PDF, maks. 20 MB)</label>
                                         <input type="file" name="{{ $field }}" class="form-control form-control-solid" accept=".pdf,application/pdf">
                                         @if (!empty($masterInfo->{$field}))
                                             <a class="form-text d-inline-block" href="{{ $storagePublicUrl($masterInfo->{$field}) }}" target="_blank" rel="noopener">Lihat dokumen yang sudah diunggah</a>
                                         @endif
                                     </div>
                                 @endforeach
                             </div>
                             <div class="mb-6 mt-2"><label for="handover-note" class="form-label fw-semibold">Catatan <span class="text-muted fw-normal">(opsional)</span></label><textarea id="handover-note" name="catatan" class="form-control form-control-solid" rows="4" placeholder="Tambahkan catatan untuk bendahara atau admin...">{{ old('catatan') }}</textarea></div>
                             <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-top pt-5">
                                 <span class="text-muted fs-7"><i class="fas fa-info-circle me-1"></i>Dokumen PDF dapat diunggah atau diperbarui pada tahap ini.</span>
                                 <button class="btn btn-primary px-6"><i class="fas fa-paper-plane me-2"></i>Simpan dan proses</button>
                             </div>
                         </form>
                     @else
                         <div class="text-muted">Menunggu Admin Pengadaan mengisi tanggal penyerahan, status, dan dokumen SPJ.</div>
                     @endif
                 </div>
             </div>
         @endif

         @if (!empty($masterInfo?->upload_spj_1) || !empty($masterInfo?->upload_spj_2))
             <div class="card card-bordered mb-6">
                 <div class="card-header"><h4 class="card-title">Dokumen Penyerahan SPJ</h4></div>
                 <div class="card-body d-flex flex-wrap gap-4">
                     @foreach (['upload_spj_1' => 'Dokumen SPJ 1', 'upload_spj_2' => 'Dokumen SPJ 2'] as $field => $label)
                         @if (!empty($masterInfo->{$field}))
                             <a class="btn btn-light-primary" href="{{ $storagePublicUrl($masterInfo->{$field}) }}" target="_blank" rel="noopener">{{ $label }} <i class="ki-duotone ki-exit-up fs-5 ms-1"></i></a>
                         @endif
                     @endforeach
                 </div>
             </div>
         @endif

         @if ($pembayaranSelesai)
             @php
                 $tanggalLunas = $latestPaymentInfo?->tanggal_pembayaran ?? $masterInfo?->tanggal_pembayaran;
                 $bulanLunas = $latestPaymentInfo?->bulan_pembayaran
                     ?? ($tanggalLunas ? \Illuminate\Support\Carbon::parse($tanggalLunas)->translatedFormat('F') : '-');
                 $buktiLunas = $latestPaymentInfo?->bukti_bayar ?? $masterInfo?->bukti_bayar;
                 $namaBendahara = $latestPaymentInfo?->nama_bendahara ?? $masterInfo?->nama_validasi_keuangan ?? '-';
             @endphp
             <div class="card card-bordered mb-6">
                 <div class="card-header"><h4 class="card-title">Pembayaran Bendahara</h4></div>
                 <div class="card-body row g-4">
                     <div class="col-lg-5">
                         <div class="alert alert-success mb-0">
                             <div class="fw-semibold">Status Pembayaran: Selesai</div>
                             <div class="fs-2 fw-bold">Rp {{ number_format((float) ($masterInfo?->pembayaran ?? 0), 0, ',', '.') }}</div>
                             <div>Tanggal pembayaran: {{ $tanggalLunas ? \Illuminate\Support\Carbon::parse($tanggalLunas)->translatedFormat('d F Y') : '-' }}</div>
                         </div>
                     </div>
                     <div class="col-lg-7">
                         <div class="border rounded p-4 h-100">
                             <div class="fw-semibold mb-2">Bukti Pembayaran</div>
                             @if ($buktiLunas)
                                 <a href="{{ $storagePublicUrl($buktiLunas) }}" target="_blank" rel="noopener">Lihat bukti pembayaran</a>
                             @else
                                 <span class="text-muted">Tidak ada bukti pembayaran.</span>
                             @endif
                             <div class="mt-3">Bendahara: {{ $namaBendahara }}</div>
                             <div class="text-muted">Bulan pembayaran: {{ $bulanLunas }}</div>
                         </div>
                     </div>
                 </div>
             </div>
         @endif

         @if ($canLanjutan && ! $pembayaranSelesai)
             @php
                 $formLabels = [
                     'validasi_ppk' => 'Validasi PPK', 'validasi_ppbj' => 'Validasi PPBJ',
                     'validasi_pptk' => 'Validasi PPTK', 'validasi_verifikator' => 'Validasi Verifikator',
                     'validasi_ppk_keuangan' => 'Validasi PPK Keuangan', 'validasi_direktur' => 'Validasi Direktur',
                     'serah_terima_bendahara' => 'Serah Terima SPJ ke Bendahara',
                     'verifikasi_bendahara' => 'Konfirmasi Terima SPJ', 'pembayaran' => 'Pembayaran Bendahara',
                 ];
             @endphp
             <div class="card card-bordered spj-stage-form-card border-primary border-opacity-25 shadow-sm rounded-4 mb-6 overflow-hidden">
                 <div class="card-header bg-light-primary border-0 py-5">
                     <div class="d-flex align-items-center gap-4">
                         <span class="symbol symbol-45px symbol-circle flex-shrink-0"><span class="symbol-label bg-primary text-white"><i class="fas fa-clipboard-check text-white fs-4"></i></span></span>
                         <div><div class="text-primary text-uppercase fw-bold fs-8 mb-1">Persetujuan Pengadaan</div><h4 class="card-title mb-0">{{ $formLabels[$step] ?? 'Proses SPJ' }}</h4></div>
                     </div>
                 </div>
                 <div class="card-body p-6 p-lg-8">
                     <form method="POST" enctype="multipart/form-data" action="{{ route('usulan-belanja.pengadaan.lanjutan', $spj->id) }}">@csrf
                         @if ($step !== 'pembayaran')
                             <div class="mb-6">
                                 <label class="form-label fw-bold fs-6 mb-3">Pilih keputusan</label>
                                 <div class="row g-3">
                                     <div class="col-md-6">
                                         <input class="btn-check" type="radio" name="keputusan" id="legacy-workflow-approve" value="setuju" @checked(old('keputusan') === 'setuju') required>
                                         <label class="btn btn-outline btn-outline-dashed btn-outline-success d-flex align-items-center text-start p-4 h-100 w-100" for="legacy-workflow-approve">
                                             <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-success"><i class="fas fa-check text-success"></i></span></span>
                                             <span><span class="d-block fw-bold text-gray-900">Setujui dan teruskan</span><span class="text-muted fs-7">SPJ diteruskan ke tahap berikutnya.</span></span>
                                         </label>
                                     </div>
                                     <div class="col-md-6">
                                         <input class="btn-check" type="radio" name="keputusan" id="legacy-workflow-revision" value="revisi" @checked(old('keputusan') === 'revisi') required>
                                         <label class="btn btn-outline btn-outline-dashed btn-outline-warning d-flex align-items-center text-start p-4 h-100 w-100" for="legacy-workflow-revision">
                                             <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-warning"><i class="fas fa-undo text-warning"></i></span></span>
                                             <span><span class="d-block fw-bold text-gray-900">Kembalikan untuk revisi</span><span class="text-muted fs-7">Pemohon perlu memperbaiki dokumen atau data.</span></span>
                                         </label>
                                     </div>
                                 </div>
                             </div>
                         @endif
                         @if ($step === 'serah_terima_bendahara')
                             <div class="row">
                                 <div class="col-md-4 mb-4"><label class="form-label">Bruto (Rp)</label><input type="number" min="0" step="1" name="bruto" class="form-control" required></div>
                                 <div class="col-md-4 mb-4"><label class="form-label">Harga dasar (Rp)</label><input type="number" min="0" step="1" name="harga_dasar" class="form-control" required></div>
                                 <div class="col-md-4 mb-4"><label class="form-label">Tanggal penyerahan SPJ</label><input type="date" name="tanggal_penyerahan_spj" class="form-control" required></div>
                             </div>
                         @elseif ($step === 'pembayaran')
                             <div class="row">
                                 @php
                                     $hargaDasarUsulan = (float) ($netto ?? 0);
                                     $totalPpnUsulan = (float) ($nilaiPpn ?? 0);
                                     $totalTagihanUsulan = $hargaDasarUsulan + $totalPpnUsulan;
                                     $sisaTersimpan = max(0, $totalTagihanUsulan - (float) ($masterInfo->pembayaran ?? 0));
                                 @endphp
                                 <div class="col-md-6 mb-4"><label class="form-label">Harga dasar (Total usulan non-PPN)</label><input type="text" class="form-control" value="{{ number_format($hargaDasarUsulan, 0, ',', '.') }}" readonly></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Total usulan + PPN (Rp)</label><input type="text" class="form-control" value="{{ number_format($totalTagihanUsulan, 0, ',', '.') }}" readonly></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">PPN (%)</label><input type="number" min="0" max="100" step="0.01" name="ppn" class="form-control tax-rate" value="{{ old('ppn', $masterInfo->ppn ?? 0) }}"></div>
                                 <div class="col-md-3 mb-4"><label class="form-label">PPh21 (%)</label><input type="number" min="0" max="100" step="0.01" name="pph21" class="form-control tax-rate" value="{{ old('pph21', $masterInfo->pph21 ?? 0) }}"></div>
                                 <div class="col-md-3 mb-4"><label class="form-label">PPh23 (%)</label><input type="number" min="0" max="100" step="0.01" name="pph23" class="form-control tax-rate" value="{{ old('pph23', $masterInfo->pph23 ?? 0) }}"></div>
                                 <div class="col-md-3 mb-4"><label class="form-label">PPh22 (%)</label><input type="number" min="0" max="100" step="0.01" name="pph22" class="form-control tax-rate" value="{{ old('pph22', $masterInfo->pph22 ?? 0) }}"></div>
                                 <div class="col-md-3 mb-4"><label class="form-label">PP 0,5 (%)</label><input type="number" min="0" max="100" step="0.01" name="pp05" class="form-control tax-rate" value="{{ old('pp05', $masterInfo->pp05 ?? 0) }}"></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Total pajak (Rp)</label><input type="text" id="total-pajak" class="form-control" value="{{ number_format((float) ($masterInfo->jumlah_pajak ?? 0), 0, ',', '.') }}" readonly></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Tanggal pembayaran</label><input type="date" name="tanggal_pembayaran" class="form-control" value="{{ old('tanggal_pembayaran', now()->toDateString()) }}" required></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Jumlah yang dibayarkan (Harga dasar + total pajak)</label><input type="number" min="0" step="1" name="pembayaran" id="jumlah-pembayaran" class="form-control" value="{{ old('pembayaran') }}" readonly></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Sisa pembayaran (Rp)</label><input type="text" id="sisa-pembayaran" class="form-control" value="{{ number_format(max(0, $sisaTersimpan), 0, ',', '.') }}" readonly data-due="{{ max(0, $sisaTersimpan) }}"></div>
                                 <div class="col-12 mb-4">
                                     <label class="form-label fw-bold fs-6 mb-3">Validasi pembayaran</label>
                                     <div class="row g-3">
                                         <div class="col-md-6">
                                             <input class="btn-check" type="radio" name="status_pembayaran" id="payment-status-complete" value="Selesai" @checked(old('status_pembayaran', $masterInfo->status_pembayaran ?? '') === 'Selesai') required>
                                             <label class="btn btn-outline btn-outline-dashed btn-outline-success d-flex align-items-center text-start p-4 h-100 w-100" for="payment-status-complete">
                                                 <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-success"><i class="fas fa-check text-success"></i></span></span>
                                                 <span><span class="d-block fw-bold text-gray-900">Pembayaran selesai</span><span class="text-muted fs-7">Simpan pembayaran dan selesaikan SPJ.</span></span>
                                             </label>
                                         </div>
                                         <div class="col-md-6">
                                             <input class="btn-check" type="radio" name="status_pembayaran" id="payment-status-revision" value="Revisi" @checked(old('status_pembayaran', $masterInfo->status_pembayaran ?? '') === 'Revisi') required>
                                             <label class="btn btn-outline btn-outline-dashed btn-outline-warning d-flex align-items-center text-start p-4 h-100 w-100" for="payment-status-revision">
                                                 <span class="symbol symbol-40px symbol-circle me-3"><span class="symbol-label bg-light-warning"><i class="fas fa-undo text-warning"></i></span></span>
                                                 <span><span class="d-block fw-bold text-gray-900">Perlu revisi</span><span class="text-muted fs-7">Koreksi data pembayaran sebelum disimpan.</span></span>
                                             </label>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Kode Billing PPN</label><input type="text" name="kode_billingppn" class="form-control" value="{{ old('kode_billingppn', $masterInfo->kode_billingppn ?? '') }}"></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Kode Billing PPh22</label><input type="text" name="kode_billingpph22" class="form-control" value="{{ old('kode_billingpph22', $masterInfo->kode_billingpph22 ?? '') }}"></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Bukti bayar (PDF/JPG/PNG, maks. 5 MB; wajib jika status Selesai)</label><input type="file" name="bukti_bayar" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                                 @if (!empty($masterInfo->bukti_bayar))
                                     <div class="col-12 mb-4"><a href="{{ $storagePublicUrl($masterInfo->bukti_bayar) }}" target="_blank" rel="noopener">Lihat bukti pembayaran terakhir</a></div>
                                 @endif
                             </div>
                             <script>
                                 (() => {
                                     const amount = document.getElementById('jumlah-pembayaran');
                                     const remaining = document.getElementById('sisa-pembayaran');
                                     const taxTotal = document.getElementById('total-pajak');
                                     const taxRates = Array.from(document.querySelectorAll('.tax-rate'));
                                     const statusOptions = Array.from(document.querySelectorAll('[name="status_pembayaran"]'));
                                     const proof = document.querySelector('[name="bukti_bayar"]');
                                     const base = {{ $hargaDasarUsulan }};
                                     if (!amount || !remaining) return;
                                     const due = Number(remaining.dataset.due || 0);
                                     const updateTax = () => {
                                         const totalRate = taxRates.reduce((sum, input) => sum + Number(input.value || 0), 0);
                                         const taxAmount = Math.round(base * totalRate / 100);
                                         if (taxTotal) taxTotal.value = taxAmount.toLocaleString('id-ID', { maximumFractionDigits: 0 });
                                         amount.value = String(Math.round(base + taxAmount));
                                         remaining.value = Math.max(0, due - Number(amount.value || 0)).toLocaleString('id-ID');
                                     };
                                     const updateStatusFields = () => {
                                         const selectedStatus = statusOptions.find(option => option.checked)?.value;
                                         const completing = !statusOptions.length || selectedStatus === 'Selesai';
                                         amount.required = completing;
                                         amount.disabled = !completing;
                                         if (!completing) amount.value = '';
                                         if (proof) proof.required = completing;
                                         if (completing) updateTax();
                                     };
                                     taxRates.forEach(input => input.addEventListener('input', updateTax));
                                     statusOptions.forEach(option => option.addEventListener('change', updateStatusFields));
                                     updateTax();
                                     updateStatusFields();
                                 })();
                             </script>
                         @endif
                         <div class="mb-6 mt-4"><label for="stage-approval-note" class="form-label fw-semibold">Catatan <span class="text-muted fw-normal">(opsional)</span></label><textarea id="stage-approval-note" name="catatan" class="form-control form-control-solid" rows="4" placeholder="Tambahkan catatan untuk tahap berikutnya...">{{ old('catatan') }}</textarea></div>
                         <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-top pt-5">
                             <span class="text-muted fs-7"><i class="fas fa-info-circle me-1"></i>Keputusan dan catatan tersimpan dalam riwayat alur.</span>
                             <button class="btn btn-primary px-6"><i class="fas fa-paper-plane me-2"></i>{{ $step === 'pembayaran' ? 'Simpan pembayaran dan selesaikan SPJ' : 'Simpan dan teruskan' }}</button>
                         </div>
                     </form>
                 </div>
             </div>
         @endif

         <div class="card card-bordered mt-7">
             <div class="card-header">
                 <h4 class="card-title">Riwayat Alur</h4>
             </div>
             <div class="card-body">
                 @forelse($logs as $log)
                     <div class="border-bottom pb-3 mb-3"><strong>{{ $log->aksi }}</strong> <span
                             class="text-muted">oleh {{ $log->nama_user ?? 'user dihapus' }} —
                             {{ $log->created_at }}</span>
                         <div class="text-muted">{{ $steps[$log->step_dari] ?? ($log->step_dari ?? '—') }} →
                             {{ $steps[$log->step_ke] ?? ($log->step_ke ?? '—') }}</div>
                         @if ($log->catatan)
                             <div>{{ $log->catatan }}</div>
                         @endif
                     </div>
                 @empty
                     <span class="text-muted">Belum ada tindakan dalam alur pengadaan.</span>
                 @endforelse
             </div>
         </div>
     </div>
 </div>
