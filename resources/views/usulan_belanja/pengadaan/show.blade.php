 

 <div class="card card-bordered">
     <div class="card-header">
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
             <div class="card card-bordered mb-6">
                 <div class="card-header">
                     <h4 class="card-title">1. Input Surat Pesanan</h4>
                 </div>
                 <div class="card-body">
                     <form method="POST" action="{{ route('usulan-belanja.pengadaan.pesanan', $spj->id) }}">@csrf
                         <div class="row">
                             <div class="col-md-4 mb-4"><label class="form-label">Nomor Surat Pesanan</label><input
                                     name="no_surat_pesanan" class="form-control"
                                     value="{{ old('no_surat_pesanan', $spj->no_surat_pesanan) }}" required></div>
                             <div class="col-md-4 mb-4"><label class="form-label">Tanggal Surat Pesanan</label><input
                                     type="date" name="tgl_surat_pesanan" class="form-control"
                                     value="{{ old('tgl_surat_pesanan', $spj->tgl_surat_pesanan) }}"></div>
                             <div class="col-md-4 mb-4"><label class="form-label">ID Paket</label><input name="idpaket"
                                     class="form-control" value="{{ old('idpaket', $spj->idpaket) }}" required></div>
                         </div>
                         <button class="btn btn-primary">Simpan dan teruskan</button>
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
                 </div>
             </div>
         @endif

         @if ($canFoto && $step === 'upload_foto')
             @if ($spj->status_verifikasi_pengurus_barang === 'Revisi')
                 <div class="alert alert-warning"><strong>Catatan Pengurus Barang:</strong>
                     {{ $spj->keterangan_verif_pengurus_barang }}</div>
             @endif
             <div class="card card-bordered mb-6">
                 <div class="card-header">
                     <h4 class="card-title">2. Unggah Foto Barang Datang</h4>
                 </div>
                 <div class="card-body">
                     <form method="POST" enctype="multipart/form-data"
                         action="{{ route('usulan-belanja.pengadaan.foto', $spj->id) }}">@csrf
                         <div class="row">
                             <div class="col-md-4 mb-4"><label class="form-label">Foto barang datang
                                     (wajib)</label><input type="file" name="foto_barang_datang" class="form-control"
                                     accept="image/jpeg,image/png" required></div>
                             <div class="col-md-4 mb-4"><label class="form-label">Foto tambahan 2</label><input
                                     type="file" name="foto_barang_datang_2" class="form-control"
                                     accept="image/jpeg,image/png"></div>
                             <div class="col-md-4 mb-4"><label class="form-label">Foto tambahan 3</label><input
                                     type="file" name="foto_barang_datang_3" class="form-control"
                                     accept="image/jpeg,image/png"></div>
                             <div class="col-md-4 mb-4"><label class="form-label">Tanggal barang datang</label><input
                                     type="date" name="tgl_barang_datang" class="form-control"
                                     value="{{ old('tgl_barang_datang', $spj->tgl_barang_datang) }}" required></div>
                             <div class="col-md-8 mb-4"><label class="form-label">Keterangan</label>
                                 <textarea name="keterangan_barang_datang" class="form-control" rows="2">{{ old('keterangan_barang_datang', $spj->keterangan_barang_datang) }}</textarea>
                             </div>
                         </div>
                         <button class="btn btn-primary">Kirim untuk validasi</button>
                     </form>
                 </div>
             </div>
         @elseif($spj->foto_barang_datang)
             <div class="card card-bordered mb-6">
                 <div class="card-header">
                     <h4 class="card-title">Foto Barang Datang</h4>
                 </div>
                 <div class="card-body d-flex gap-4 flex-wrap">
                     @foreach ([$spj->foto_barang_datang, $spj->foto_barang_datang_2, $spj->foto_barang_datang_3] as $foto)
                         @if ($foto)
                             <a href="{{ asset('storage/' . $foto) }}" target="_blank"><img
                                     src="{{ asset('storage/' . $foto) }}" alt="Foto barang datang"
                                     style="max-height:180px;max-width:240px" class="rounded border"></a>
                         @endif
                     @endforeach
                     <div>
                         <div>Tanggal datang: {{ $spj->tgl_barang_datang ?? '-' }}</div>
                         <div>{{ $spj->keterangan_barang_datang }}</div>
                     </div>
                 </div>
             </div>
         @endif

         @if ($canValidasi && $step === 'validasi_barang')
             <div class="card card-bordered mb-6">
                 <div class="card-header">
                     <h4 class="card-title">3. Validasi Pengurus Barang</h4>
                 </div>
                 <div class="card-body">
                     <form method="POST" action="{{ route('usulan-belanja.pengadaan.validasi', $spj->id) }}">@csrf
                         <div class="mb-4"><label class="form-label">Keputusan</label><select name="keputusan"
                                 class="form-select" required>
                                 <option value="">Pilih keputusan</option>
                                 <option value="setuju">Setujui</option>
                                 <option value="revisi">Kembalikan untuk perbaikan</option>
                             </select></div>
                         <div class="row">
                             <div class="col-md-6 mb-4"><label class="form-label">Nomor BAST serah terima unit</label>
                                 <input type="text" name="no_bast_serah_terima_unit" class="form-control"
                                     value="{{ old('no_bast_serah_terima_unit', $spj->no_bast_serah_terima_unit) }}" maxlength="255">
                                 <div class="form-text">Wajib diisi saat barang disetujui.</div>
                             </div>
                             <div class="col-md-6 mb-4"><label class="form-label">Nama penerima barang</label>
                                 <input type="text" name="nama_penerima_barang" class="form-control"
                                     value="{{ old('nama_penerima_barang', $spj->nama_penerima_barang) }}" maxlength="255">
                                 <div class="form-text">Wajib diisi saat barang disetujui.</div>
                             </div>
                         </div>
                         <div class="mb-4"><label class="form-label">Catatan validasi</label>
                             <textarea name="keterangan_verif_pengurus_barang" class="form-control" rows="3" required>{{ old('keterangan_verif_pengurus_barang') }}</textarea>
                         </div>
                         <button class="btn btn-primary">Simpan validasi</button>
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
             <div class="card card-bordered mb-6">
                 <div class="card-header">
                     <h4 class="card-title">4. Input Data BAST</h4>
                 </div>
                 <div class="card-body">
                     <form method="POST" action="{{ route('usulan-belanja.pengadaan.bast', $spj->id) }}">@csrf
                         <div class="row">
                             @foreach (['tgl_bast' => 'Tanggal BAST', 'no_ba' => 'Nomor BA', 'no_ba_hp' => 'Nomor BA HP', 'no_ba_bp' => 'Nomor BA BP', 'no_dpa' => 'Nomor DPA'] as $field => $label)
                                 <div class="col-md-4 mb-4"><label
                                         class="form-label">{{ $label }}</label><input
                                         name="{{ $field }}"
                                         type="{{ $field === 'tgl_bast' ? 'date' : 'text' }}" class="form-control"
                                         value="{{ old($field, $spj->{$field}) }}" required></div>
                             @endforeach
                        </div><button class="btn btn-primary">Simpan BAST dan teruskan ke PPK</button>
                     </form>
                 </div>
             </div>
         @elseif(!in_array($step, ['input_pesanan', 'upload_foto', 'validasi_barang', 'input_bast'], true))
             <div class="alert alert-success">
                 <h4>BAST selesai</h4>
                 <p class="mb-1">Tanggal BAST: {{ $spj->tgl_bast }} | BA: {{ $spj->no_ba }} | BA HP:
                     {{ $spj->no_ba_hp }} | BA BP: {{ $spj->no_ba_bp }} | DPA: {{ $spj->no_dpa }}</p>
             </div>
         @endif

         @if ($step === 'workflow' && $workflowStep)
             <div class="card card-bordered mb-6">
                 <div class="card-header"><h4 class="card-title">{{ $workflowStepName }} <span class="text-muted fs-7">(Urutan {{ $workflowStep->step_order }})</span></h4></div>
                 <div class="card-body">
                     @if ($canWorkflowStep)
                         <form method="POST" action="{{ route('usulan-belanja.pengadaan.lanjutan', $spj->id) }}">@csrf
                             <div class="mb-4"><label class="form-label">Keputusan</label>
                                 <select name="keputusan" class="form-select" required>
                                     <option value="">Pilih keputusan</option>
                                     <option value="setuju">Setujui / teruskan</option>
                                     <option value="revisi">Kembalikan untuk revisi</option>
                                 </select>
                             </div>
                             <div class="mb-4"><label class="form-label">Catatan validasi</label><textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea></div>
                             <button class="btn btn-primary">Simpan dan teruskan sesuai workflow</button>
                         </form>
                     @else
                         <div class="text-muted">Menunggu validasi dari {{ $workflowStepName }} sesuai penugasan workflow.</div>
                     @endif
                 </div>
             </div>
         @endif

         @if ($canLanjutan)
             @php
                 $formLabels = [
                     'validasi_ppk' => 'Validasi PPK', 'validasi_ppbj' => 'Validasi PPBJ',
                     'validasi_pptk' => 'Validasi PPTK', 'validasi_verifikator' => 'Validasi Verifikator',
                     'validasi_ppk_keuangan' => 'Validasi PPK Keuangan', 'validasi_direktur' => 'Validasi Direktur',
                     'serah_terima_bendahara' => 'Serah Terima SPJ ke Bendahara',
                     'verifikasi_bendahara' => 'Verifikasi Bendahara', 'pembayaran' => 'Pembayaran Bendahara',
                 ];
             @endphp
             <div class="card card-bordered mb-6">
                 <div class="card-header"><h4 class="card-title">{{ $formLabels[$step] ?? 'Proses SPJ' }}</h4></div>
                 <div class="card-body">
                     <form method="POST" enctype="multipart/form-data" action="{{ route('usulan-belanja.pengadaan.lanjutan', $spj->id) }}">@csrf
                         @if ($step !== 'pembayaran')
                             <div class="mb-4">
                                 <label class="form-label">Keputusan</label>
                                 <select name="keputusan" class="form-select" required>
                                     <option value="">Pilih keputusan</option>
                                     <option value="setuju">Setujui / teruskan</option>
                                     <option value="revisi">Kembalikan untuk revisi</option>
                                 </select>
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
                                 <div class="col-md-6 mb-4"><label class="form-label">Jumlah pembayaran (Rp)</label><input type="number" min="0" step="1" name="pembayaran" class="form-control" required></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Tanggal pembayaran</label><input type="date" name="tanggal_pembayaran" class="form-control" value="{{ now()->toDateString() }}" required></div>
                                 <div class="col-md-6 mb-4"><label class="form-label">Bukti bayar (PDF/JPG/PNG)</label><input type="file" name="bukti_bayar" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                             </div>
                         @endif
                         <div class="mb-4"><label class="form-label">Catatan</label><textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea></div>
                         <button class="btn btn-primary">{{ $step === 'pembayaran' ? 'Simpan pembayaran dan selesaikan SPJ' : 'Simpan dan teruskan' }}</button>
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
