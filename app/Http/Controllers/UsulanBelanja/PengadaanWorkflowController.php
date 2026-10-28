<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\UsulanBelanja\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class PengadaanWorkflowController extends Controller
{
    private const STEPS = [
        'input_pesanan' => 'Input surat pesanan',
        'upload_foto' => 'Unggah foto barang datang',
        'validasi_barang' => 'Validasi Pengurus Barang',
        'input_bast' => 'Input data BAST',
        'input_tukar_faktur' => 'Update Tukar Faktur',
        'input_penyerahan_spj_admin' => 'Penyerahan Dokumen SPJ oleh Admin Pengadaan',
        'workflow' => 'Validasi sesuai Workflow Pengadaan',
        'validasi_ppk' => 'Validasi PPK',
        'validasi_ppbj' => 'Validasi PPBJ',
        'validasi_pptk' => 'Validasi PPTK',
        'validasi_verifikator' => 'Validasi Verifikator',
        'validasi_ppk_keuangan' => 'Validasi PPK Keuangan',
        'validasi_direktur' => 'Validasi Direktur',
        'serah_terima_bendahara' => 'Serah terima ke Bendahara',
        'verifikasi_bendahara' => 'Konfirmasi Terima SPJ',
        'pembayaran' => 'Pembayaran Bendahara',
        'selesai' => 'Selesai',
    ];

    public function index()
    {
        $user = auth()->user();
        $query = Spj::query()
            ->with(['usulanBelanja.rkbu.subKategoriRkbu', 'details'])
            ->whereHas('usulanBelanja', fn($q) => $q->where('status_usulan', 'approved'))
            ->whereHas('usulanBelanja.workflowInstance', fn($q) => $q->where('status', 'approved'));

        $workflowStepIds = $this->workflowStepIdsForUser($user);
        $staleWorkflowSpjIds = $this->staleWorkflowSpjIdsForUser($user);
        if ($user->hasRole('Pendukung PPK')) {
            $subkategori = DB::table('sub_kategori_pendukung_ppk')->where('user_id', $user->id)->pluck('sub_kategori_rkbu_id');
            $query->where('pengadaan_step', 'upload_foto')
                ->whereHas('usulanBelanja', fn($q) => $q->whereIn('sub_kategori_rkbu_id', $subkategori));
        } elseif ($user->hasRole('Pengurus Barang') || $this->userHasWorkflowIdentity($user, 'pengurus barang')) {
            $query->where('pengadaan_step', 'validasi_barang');
        } elseif ($user->hasRole('Admin Pengadaan')) {
            $query->where(function ($q) use ($workflowStepIds, $staleWorkflowSpjIds) {
                $q->whereIn('pengadaan_step', ['input_pesanan', 'input_bast', 'input_penyerahan_spj_admin']);
                if ($workflowStepIds) $q->orWhere(fn($workflowQuery) => $workflowQuery->where('pengadaan_step', 'workflow')->whereIn('pengadaan_workflow_step_id', $workflowStepIds));
                if ($staleWorkflowSpjIds) $q->orWhereIn('spjs.id', $staleWorkflowSpjIds);
            });
        } elseif ($user->position?->level_jabatan || $workflowStepIds) {
            $roleSteps = [
                'PPK' => ['validasi_ppk'],
                'PPBJ' => ['validasi_ppbj'],
                'PPTK' => ['validasi_pptk'],
                'Verifikator' => ['validasi_verifikator'],
                'PPK Keuangan' => ['validasi_ppk_keuangan'],
                'Direktur' => ['validasi_direktur'],
                'Bendahara' => ['serah_terima_bendahara', 'verifikasi_bendahara', 'pembayaran'],
            ];
            $allowedSteps = $roleSteps[$user->position?->level_jabatan] ?? [];
            abort_unless($allowedSteps || $workflowStepIds, 403);
            $query->where(function ($q) use ($allowedSteps, $workflowStepIds, $staleWorkflowSpjIds) {
                if ($allowedSteps) $q->whereIn('pengadaan_step', $allowedSteps);
                if ($workflowStepIds) {
                    $method = $allowedSteps ? 'orWhere' : 'where';
                    $q->{$method}(fn($workflowQuery) => $workflowQuery
                        ->whereIn('pengadaan_step', ['workflow', 'serah_terima_bendahara', 'verifikasi_bendahara', 'pembayaran'])
                        ->whereIn('pengadaan_workflow_step_id', $workflowStepIds));
                    $legacyFirstStep = $this->workflowStepAfterIdentity('pengurus barang');
                    if ($legacyFirstStep && in_array($legacyFirstStep->id, $workflowStepIds, true)) {
                        $q->orWhere(fn($legacyQuery) => $legacyQuery->where('pengadaan_step', 'input_bast')->whereNull('pengadaan_workflow_step_id'));
                    }
                }
                if ($staleWorkflowSpjIds) $q->orWhereIn('spjs.id', $staleWorkflowSpjIds);
            });
        } elseif (! $user->hasRole('Administrator') && ! $user->hasRole('Super Admin')) {
            abort(403);
        }

        $spjs = $query->latest()->paginate(20);
        return view('usulan_belanja.pengadaan.index', ['spjs' => $spjs, 'steps' => self::STEPS]);
    }

    public function show(Spj $spj)
    {
        $spj->load([
            'usulanBelanja.pengusul', 'usulanBelanja.unit', 'usulanBelanja.tahunAnggaran',
            'usulanBelanja.rkbu.subKategoriRkbu', 'details.item', 'details.rkbu.rekeningBelanja',
            'details.rkbu.subKategoriRkbu.sub_kategori_rekening', 'details.rkbu.subKategoriRkbu.kategori',
            'details.rkbu.jenisKategoriRkbu', 'details.rkbu.jenisBelanja', 'details.rkbu.sumberDana',
            'details.rkbu.subKegiatan',
        ]);
        $this->authorizeView($spj);
        $step = $spj->pengadaan_step ?: 'input_pesanan';
        $workflowStep = $step === 'validasi_barang' && ! $this->hasCustomWorkflowForSpj($spj)
            ? null
            : $this->currentWorkflowStep($spj);
        if (! $workflowStep && $step === 'input_bast') $workflowStep = $this->workflowStepAfterIdentity('pengurus barang', $spj);
        if (! $workflowStep && str_starts_with($step, 'validasi_') && $step !== 'validasi_barang') $workflowStep = $this->legacyWorkflowStep($step, $spj);
        $workflowStep = $this->skipCompletedBastStep($spj, $workflowStep);
        $workflowStepName = $workflowStep ? $this->workflowStepName($workflowStep) : null;
        if ($workflowStep && ($spj->pengadaan_step === 'workflow'
            || ($this->hasCustomWorkflowForSpj($spj) && in_array($spj->pengadaan_step, ['input_pesanan', 'upload_foto', 'validasi_barang', 'input_bast'], true))
            || $spj->pengadaan_step === 'input_bast'
            || ($spj->pengadaan_step === 'input_penyerahan_spj_admin' && strtolower(trim($workflowStepName)) !== 'admin pengadaan')
            || (str_starts_with($spj->pengadaan_step, 'validasi_') && $spj->pengadaan_step !== 'validasi_barang'))) {
            $step = $this->isBastWorkflowStep($workflowStep, $spj)
                ? 'input_bast'
                : ($this->isBendaharaReceiptStep($workflowStepName) ? 'verifikasi_bendahara' : 'workflow');
        }
        $isAdmin = auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin');
        $logs = DB::table('pengadaan_workflow_logs')->leftJoin('users', 'users.id', '=', 'pengadaan_workflow_logs.user_id')
            ->where('spj_id', $spj->id)->orderBy('pengadaan_workflow_logs.created_at')->get([
                'pengadaan_workflow_logs.*',
                'users.nama as nama_user',
            ]);
        return view('usulan_belanja.pengadaan.show', [
            'spj' => $spj,
            'step' => $step,
            'steps' => self::STEPS,
            'logs' => $logs,
            'canPesanan' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canFoto' => $isAdmin || $this->isAssignedPendukung($spj),
            'canValidasi' => $isAdmin || $this->userCanApproveIdentity('pengurus barang', $spj) || auth()->user()->hasRole('Pengurus Barang'),
            'canBast' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canTukarFaktur' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canPenyerahanSpjAdmin' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canLanjutan' => $this->canProcessStep($step),
            'workflowStep' => $workflowStep,
            'workflowStepName' => $workflowStepName,
            'canWorkflowStep' => $workflowStep && $this->userCanApproveWorkflowStep($workflowStep, $spj),
        ] + $this->barangDatangViewData($spj));
    }

    public function panel(Spj $spj)
    {
        return view('usulan_belanja.pengadaan.show', $this->panelData($spj));
    }

    public function panelData(Spj $spj): array
    {
        $spj->load([
            'usulanBelanja.pengusul', 'usulanBelanja.unit', 'usulanBelanja.tahunAnggaran',
            'usulanBelanja.rkbu.subKategoriRkbu', 'details.item', 'details.rkbu.rekeningBelanja',
            'details.rkbu.subKategoriRkbu.sub_kategori_rekening', 'details.rkbu.subKategoriRkbu.kategori',
            'details.rkbu.jenisKategoriRkbu', 'details.rkbu.jenisBelanja', 'details.rkbu.sumberDana',
            'details.rkbu.subKegiatan',
        ]);
        $step = $spj->pengadaan_step ?: 'input_pesanan';
        $workflowStep = $step === 'validasi_barang' && ! $this->hasCustomWorkflowForSpj($spj)
            ? null
            : $this->currentWorkflowStep($spj);
        if (! $workflowStep && $step === 'input_bast') $workflowStep = $this->workflowStepAfterIdentity('pengurus barang', $spj);
        if (! $workflowStep && str_starts_with($step, 'validasi_') && $step !== 'validasi_barang') $workflowStep = $this->legacyWorkflowStep($step, $spj);
        $workflowStep = $this->skipCompletedBastStep($spj, $workflowStep);
        $workflowStepName = $workflowStep ? $this->workflowStepName($workflowStep) : null;
        if ($workflowStep && ($spj->pengadaan_step === 'workflow'
            || ($this->hasCustomWorkflowForSpj($spj) && in_array($spj->pengadaan_step, ['input_pesanan', 'upload_foto', 'validasi_barang', 'input_bast'], true))
            || $spj->pengadaan_step === 'input_bast'
            || ($spj->pengadaan_step === 'input_penyerahan_spj_admin' && strtolower(trim($workflowStepName)) !== 'admin pengadaan')
            || (str_starts_with($spj->pengadaan_step, 'validasi_') && $spj->pengadaan_step !== 'validasi_barang'))) {
            $step = $this->isBastWorkflowStep($workflowStep, $spj)
                ? 'input_bast'
                : ($this->isBendaharaReceiptStep($workflowStepName) ? 'verifikasi_bendahara' : 'workflow');
        }
        $isAdmin = auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin');
        $logs = DB::table('pengadaan_workflow_logs')->leftJoin('users', 'users.id', '=', 'pengadaan_workflow_logs.user_id')
            ->where('spj_id', $spj->id)->orderBy('pengadaan_workflow_logs.created_at')->get([
                'pengadaan_workflow_logs.*',
                'users.nama as nama_user',
            ]);
        return [
            'spj' => $spj,
            'step' => $step,
            'steps' => self::STEPS,
            'logs' => $logs,
            'canPesanan' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canFoto' => $isAdmin || $this->isAssignedPendukung($spj),
            'canValidasi' => $isAdmin || $this->userCanApproveIdentity('pengurus barang', $spj) || auth()->user()->hasRole('Pengurus Barang'),
            'canBast' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canTukarFaktur' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canPenyerahanSpjAdmin' => $isAdmin || auth()->user()->hasRole('Admin Pengadaan'),
            'canLanjutan' => $this->canProcessStep($step),
            'workflowStep' => $workflowStep,
            'workflowStepName' => $workflowStepName,
            'canWorkflowStep' => $workflowStep && $this->userCanApproveWorkflowStep($workflowStep, $spj),
        ] + $this->barangDatangViewData($spj);
    }

    private function barangDatangViewData(Spj $spj): array
    {
        $masterInfo = DB::table('master_spjs as master')
            ->leftJoin('perusahaans as perusahaan', 'perusahaan.id_perusahaan', '=', 'master.perusahaan_id')
            ->where('master.spj_id', $spj->id)
            ->first(['master.*', 'perusahaan.nama_perusahaan']);

        $netto = (float) $spj->details->sum(fn ($detail) =>
            (float) ($detail->item?->harga_satuan ?? 0) * (float) $detail->volume
        );
        $nilaiPpn = (float) $spj->details->sum(fn ($detail) =>
            (float) ($detail->item?->harga_satuan ?? 0) * (float) $detail->volume * (float) ($detail->item?->ppn ?? 0) / 100
        );
        $latestPaymentInfo = null;
        if ($masterInfo && Schema::hasTable('master_spj_payment_transactions')) {
            $latestPaymentInfo = DB::table('master_spj_payment_transactions as payment')
                ->leftJoin('users', 'users.id', '=', 'payment.user_id')
                ->where('payment.master_spj_id', $masterInfo->id)
                ->orderByDesc('payment.tanggal_pembayaran')
                ->orderByDesc('payment.created_at')
                ->first([
                    'payment.amount',
                    'payment.tanggal_pembayaran',
                    'payment.bulan_pembayaran',
                    'payment.bukti_bayar',
                    'users.nama as nama_bendahara',
                ]);
        }

        return [
            'masterInfo' => $masterInfo,
            'latestPaymentInfo' => $latestPaymentInfo,
            'netto' => $netto,
            'nilaiPpn' => $nilaiPpn,
            'totalAnggaran' => $netto + $nilaiPpn,
        ];
    }

    public function userCanAccessCurrentWorkflowStep(Spj $spj): bool
    {
        $workflowStep = $this->currentWorkflowStep($spj);
        if (! $workflowStep && $spj->pengadaan_step === 'input_bast') {
            $workflowStep = $this->workflowStepAfterIdentity('pengurus barang', $spj);
        }
        if (! $workflowStep && str_starts_with($spj->pengadaan_step, 'validasi_') && $spj->pengadaan_step !== 'validasi_barang') {
            $workflowStep = $this->legacyWorkflowStep($spj->pengadaan_step, $spj);
        }
        return $workflowStep ? $this->userCanApproveWorkflowStep($workflowStep, $spj) : false;
    }

    public function storePesanan(Request $request, Spj $spj)
    {
        $this->authorizeRole('Admin Pengadaan');
        abort_unless(! $this->hasCustomWorkflowForSpj($spj), 403, 'SPJ ini mengikuti langkah workflow khusus subkategori dan tidak melalui tahap default input surat pesanan.');
        abort_unless(in_array($spj->pengadaan_step ?: 'input_pesanan', ['input_pesanan'], true), 403, 'Tahap pesanan sudah ditutup.');
        $data = $request->validate([
            'no_surat_pesanan' => ['required', 'string', 'max:255'],
            'tgl_surat_pesanan' => ['nullable', 'date'],
            'idpaket' => ['required', 'string', 'max:255'],
        ]);
        $subkategoriId = $spj->usulanBelanja()->value('sub_kategori_rkbu_id');
        $assignee = DB::table('sub_kategori_pendukung_ppk')
            ->join('role_user', 'role_user.user_id', '=', 'sub_kategori_pendukung_ppk.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('sub_kategori_rkbu_id', $subkategoriId)
            ->where('roles.nama_role', 'Pendukung PPK')->exists();
        if (! $assignee) {
            throw ValidationException::withMessages([
                'idpaket' => 'Belum ada user ber-role Pendukung PPK yang ditugaskan pada subkategori RKBU SPJ ini. Atur penugasan terlebih dahulu.',
            ]);
        }
        $from = $spj->pengadaan_step ?: 'input_pesanan';
        $spj->update($data + ['pengadaan_step' => 'upload_foto']);
        $this->log($spj, 'Input surat pesanan', $from, 'upload_foto', 'Nomor: ' . $data['no_surat_pesanan'] . '; ID paket: ' . $data['idpaket']);
        return back()->with('success', 'Data surat pesanan tersimpan. Tugas diteruskan kepada Pendukung PPK sesuai subkategori.');
    }

    public function storeFoto(Request $request, Spj $spj)
    {
        abort_unless($this->isAssignedPendukung($spj) || auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'), 403);
        abort_unless($spj->pengadaan_step === 'upload_foto', 403, 'SPJ belum berada pada tahap unggah foto.');
        $data = $request->validate([
            'foto_barang_datang' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'foto_barang_datang_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'foto_barang_datang_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'tgl_barang_datang' => ['required', 'date'],
            'keterangan_barang_datang' => ['nullable', 'string', 'max:5000'],
        ]);
        foreach (['foto_barang_datang', 'foto_barang_datang_2', 'foto_barang_datang_3'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('pengadaan/barang-datang', 'public');
            } else {
                unset($data[$field]);
            }
        }
        $from = $spj->pengadaan_step;
        $spj->update($data + [
            'pengadaan_step' => 'validasi_barang',
            'status_verifikasi_pengurus_barang' => null,
            'keterangan_verif_pengurus_barang' => null,
        ]);
        $this->log($spj, 'Unggah foto barang datang', $from, 'validasi_barang', $data['keterangan_barang_datang'] ?? null);
        return back()->with('success', 'Foto barang datang tersimpan dan menunggu validasi Pengurus Barang.');
    }

    public function validasiBarang(Request $request, Spj $spj)
    {
        abort_unless(auth()->user()->hasRole('Pengurus Barang') || $this->userCanApproveIdentity('pengurus barang', $spj) || auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'), 403);
        abort_unless($spj->pengadaan_step === 'validasi_barang', 403, 'SPJ belum berada pada tahap validasi.');
        $data = $request->validate([
            'keputusan' => ['required', 'in:setuju,revisi'],
            'keterangan_verif_pengurus_barang' => ['required', 'string', 'max:5000'],
            'no_bast_serah_terima_unit' => ['required_if:keputusan,setuju', 'nullable', 'string', 'max:255'],
            'nama_penerima_barang' => ['required_if:keputusan,setuju', 'nullable', 'string', 'max:255'],
        ]);
        $approved = $data['keputusan'] === 'setuju';
        $from = $spj->pengadaan_step;
        $workflowNext = $approved ? $this->workflowStepAfterIdentity('pengurus barang', $spj) : null;
        if ($approved && ! $workflowNext) {
            throw ValidationException::withMessages(['keputusan' => 'Workflow Pengadaan belum memiliki tahap setelah Pengurus Barang. Periksa pengaturan workflow.']);
        }
        $to = $approved ? 'workflow' : 'upload_foto';
        $spj->update([
            'status_verifikasi_pengurus_barang' => $approved ? 'Selesai' : 'Revisi',
            'keterangan_verif_pengurus_barang' => $data['keterangan_verif_pengurus_barang'],
            'no_bast_serah_terima_unit' => $data['no_bast_serah_terima_unit'] ?? $spj->no_bast_serah_terima_unit,
            'nama_penerima_barang' => $data['nama_penerima_barang'] ?? $spj->nama_penerima_barang,
            'validator_pengurus_barang_id' => auth()->id(),
            'tgl_verif_pengurus_barang' => now(),
            'pengadaan_step' => $to,
            'pengadaan_workflow_step_id' => $approved ? $workflowNext->id : null,
        ]);
        $this->log($spj, $approved ? 'Setujui barang datang' : 'Kembalikan untuk perbaikan', $from, $to, $data['keterangan_verif_pengurus_barang']);
        return back()->with('success', $approved ? 'Barang disetujui. SPJ diteruskan ke ' . $this->workflowStepName($workflowNext) . ' sesuai Workflow Pengadaan.' : 'Barang dikembalikan kepada Pendukung PPK untuk diperbaiki.');
    }

    public function storeBast(Request $request, Spj $spj)
    {
        $this->authorizeRole('Admin Pengadaan');
        $workflowStep = $this->currentWorkflowStep($spj);
        if ($spj->pengadaan_step === 'input_bast' && ! $workflowStep) {
            $workflowStep = $this->workflowStepAfterIdentity('pengurus barang', $spj);
            abort_unless($workflowStep, 422, 'Workflow Pengadaan belum memiliki tahap setelah Pengurus Barang.');
            $spj->update(['pengadaan_step' => 'workflow', 'pengadaan_workflow_step_id' => $workflowStep->id]);
            $this->log($spj, 'Sesuaikan SPJ ke Workflow Pengadaan', 'input_bast', 'workflow', 'Validasi lanjutan harus diselesaikan sebelum BAST.');
            return back()->with('success', 'Urutan SPJ disesuaikan dengan Workflow Pengadaan. Tahap berikutnya: ' . $this->workflowStepName($workflowStep) . '. BAST dapat diisi setelah tahap workflow selesai.');
        }
        $workflowBast = $spj->pengadaan_step === 'workflow'
            && $workflowStep
            && $this->isBastWorkflowStep($workflowStep, $spj);
        $legacyBast = $spj->pengadaan_step === 'input_bast'
            && $workflowStep
            && $this->isBastWorkflowStep($workflowStep, $spj);
        abort_unless($workflowBast || $legacyBast, 403, 'BAST tidak termasuk tahap workflow subkategori ini atau belum dapat diisi.');
        $data = $request->validate([
            'tgl_bast' => ['required', 'date'],
            'no_ba' => ['required', 'string', 'max:255'],
            'no_ba_hp' => ['required', 'string', 'max:255'],
            'no_ba_bp' => ['required', 'string', 'max:255'],
            'no_dpa' => ['required', 'string', 'max:255'],
        ]);
        $from = $spj->pengadaan_step;
        $master = DB::table('master_spjs')->where('spj_id', $spj->id)->first();
        abort_unless($master, 422, 'Data master SPJ untuk penyedia ini tidak ditemukan.');
        $workflowStep = $workflowStep ?: $this->bastWorkflowStep($spj);
        $nextStep = $workflowStep ? $this->nextWorkflowStep($workflowStep, $spj) : null;
        $spj->update($data + [
            'pengadaan_step' => 'input_tukar_faktur',
            'pengadaan_workflow_step_id' => $nextStep?->id,
        ]);
        DB::table('master_spjs')->where('id', $master->id)->update([
            'no_ba' => $data['no_ba'],
            'no_ba_hp' => $data['no_ba_hp'],
            'no_ba_bp' => $data['no_ba_bp'],
            'no_dpa' => $data['no_dpa'],
            'tgl_bast' => $data['tgl_bast'],
            'updated_at' => now(),
        ]);
        $this->log($spj, 'Input data BAST', $from, 'input_tukar_faktur', 'BAST lengkap. Tahap berikutnya: Update Tukar Faktur.');
        return back()->with('success', 'Data BAST tersimpan. Silakan lengkapi data Tukar Faktur.');
    }

    public function storeTukarFaktur(Request $request, Spj $spj)
    {
        $this->authorizeRole('Admin Pengadaan');
        abort_unless($spj->pengadaan_step === 'input_tukar_faktur', 403, 'SPJ belum berada pada tahap update Tukar Faktur.');
        $data = $request->validate([
            'tanggal_faktur' => ['required', 'date'],
            'tgl_kwitansi' => ['required', 'date'],
            'no_faktur' => ['required', 'string', 'max:255'],
        ]);
        $master = DB::table('master_spjs')->where('spj_id', $spj->id)->first();
        abort_unless($master, 422, 'Data master SPJ untuk penyedia ini tidak ditemukan.');

        DB::table('master_spjs')->where('id', $master->id)->update([
            'tanggal_faktur' => $data['tanggal_faktur'],
            'tgl_kwitansi' => $data['tgl_kwitansi'],
            'no_faktur' => $data['no_faktur'],
            'status_proses_tukar_faktur' => 'Selesai',
            'tgl_proses_faktur' => now()->toDateString(),
            'updated_at' => now(),
        ]);

        $workflowCursor = $spj->pengadaan_workflow_step_id
            ? $this->currentWorkflowStep($spj)
            : $this->bastWorkflowStep($spj);
        $nextStep = $spj->pengadaan_workflow_step_id
            ? $workflowCursor
            : ($workflowCursor ? $this->nextWorkflowStep($workflowCursor, $spj) : null);
        $spj->update([
            'pengadaan_step' => $nextStep ? 'workflow' : 'selesai',
            'pengadaan_workflow_step_id' => $nextStep?->id,
        ]);
        $nextName = $nextStep ? $this->workflowStepName($nextStep) : 'Selesai';
        $this->log($spj, 'Update Tukar Faktur', 'input_tukar_faktur', $nextStep ? 'workflow' : 'selesai', 'Data faktur lengkap. Tahap berikutnya: ' . $nextName . '.');

        return back()->with('success', 'Data Tukar Faktur tersimpan. SPJ diteruskan ke ' . $nextName . '.');
    }

    public function storePenyerahanSpjAdmin(Request $request, Spj $spj)
    {
        $this->authorizeRole('Admin Pengadaan');
        abort_unless($spj->pengadaan_step === 'input_penyerahan_spj_admin', 403, 'SPJ belum berada pada tahap penyerahan dokumen oleh Admin Pengadaan.');
        $data = $request->validate([
            'tanggal_penyerahan_spj' => ['required', 'date'],
            'status_serah_terima_bendahara' => ['required', 'in:Selesai,Revisi'],
            'upload_spj_1' => ['required_if:status_serah_terima_bendahara,Selesai', 'nullable', 'file', 'mimes:pdf', 'max:20480'],
            'upload_spj_2' => ['required_if:status_serah_terima_bendahara,Selesai', 'nullable', 'file', 'mimes:pdf', 'max:20480'],
            'catatan' => ['nullable', 'string', 'max:5000'],
        ]);
        $master = DB::table('master_spjs')->where('spj_id', $spj->id)->first();
        abort_unless($master, 422, 'Data master SPJ untuk penyedia ini tidak ditemukan.');

        $updates = [
            'tanggal_penyerahan_spj' => $data['tanggal_penyerahan_spj'],
            'bulan_penyerahan_spj' => \Illuminate\Support\Carbon::parse($data['tanggal_penyerahan_spj'])->translatedFormat('F'),
            'status_serah_terima_bendahara' => $data['status_serah_terima_bendahara'],
            'updated_at' => now(),
        ];
        foreach (['upload_spj_1', 'upload_spj_2'] as $field) {
            if (! $request->hasFile($field)) continue;
            if ($master->{$field}) Storage::disk('public')->delete($master->{$field});
            $updates[$field] = $request->file($field)->store('spjs/dokumen_spj', 'public');
        }

        if ($data['status_serah_terima_bendahara'] === 'Revisi') {
            $updates['tanggal_revisi_spj'] = now()->toDateString();
            DB::table('master_spjs')->where('id', $master->id)->update($updates);
            $this->log($spj, 'Revisi dokumen penyerahan SPJ', 'input_penyerahan_spj_admin', 'input_penyerahan_spj_admin', $data['catatan'] ?? 'Dokumen SPJ perlu direvisi.');
            return back()->with('success', 'Status penyerahan SPJ disimpan sebagai Revisi. Admin Pengadaan dapat memperbarui dokumen dan mengirim ulang.');
        }

        DB::table('master_spjs')->where('id', $master->id)->update($updates);
        $nextStep = $spj->pengadaan_workflow_step_id ? $this->currentWorkflowStep($spj) : null;
        $nextName = $nextStep ? $this->workflowStepName($nextStep) : 'Selesai';
        $nextLegacyStep = $nextStep && $nextName === 'Bendahara' ? 'serah_terima_bendahara' : ($nextStep ? 'workflow' : 'selesai');
        $spj->update([
            'pengadaan_step' => $nextLegacyStep,
            'pengadaan_workflow_step_id' => $nextStep?->id,
        ]);
        $this->log($spj, 'Penyerahan dokumen SPJ', 'input_penyerahan_spj_admin', $nextLegacyStep, 'Dokumen lengkap. Tahap berikutnya: ' . $nextName . '.');

        return back()->with('success', 'Dokumen SPJ tersimpan. SPJ diteruskan ke ' . $nextName . '.');
    }

    public function storeLanjutan(Request $request, Spj $spj)
    {
        $step = $spj->pengadaan_step;
        if ($step !== 'workflow'
            && $this->hasCustomWorkflowForSpj($spj)
            && in_array($step, ['input_pesanan', 'upload_foto', 'validasi_barang', 'input_bast'], true)) {
            $mappedStep = $this->currentWorkflowStep($spj);
            abort_unless($mappedStep, 422, 'Tahap awal workflow subkategori tidak ditemukan. Periksa mapping workflow-step-sub-kategori-rkbu.');
            abort_unless($this->userCanApproveWorkflowStep($mappedStep, $spj), 403, 'Akun Anda tidak memiliki kewenangan untuk tahap workflow ini.');
            $spj->update(['pengadaan_step' => 'workflow', 'pengadaan_workflow_step_id' => $mappedStep->id]);
            $step = 'workflow';
        }
        if ($step === 'input_penyerahan_spj_admin' && $spj->pengadaan_workflow_step_id) {
            $pendingStep = $this->currentWorkflowStep($spj);
            if ($pendingStep && strtolower(trim($this->workflowStepName($pendingStep))) !== 'admin pengadaan') {
                $spj->update(['pengadaan_step' => 'workflow']);
                $this->log(
                    $spj,
                    'Lewati penyerahan Admin',
                    'input_penyerahan_spj_admin',
                    'workflow',
                    'Tahap berikutnya mengikuti Workflow Pengadaan: ' . $this->workflowStepName($pendingStep) . '.'
                );
                $step = 'workflow';
            }
        }
        if ($step !== 'workflow' && ! $spj->pengadaan_workflow_step_id && ($step === 'input_bast' || str_starts_with($step, 'validasi_'))) {
            $mappedStep = $step === 'input_bast' ? $this->workflowStepAfterIdentity('pengurus barang', $spj) : $this->legacyWorkflowStep($step, $spj);
            abort_unless($mappedStep, 422, 'Tahap SPJ lama tidak terdapat pada Workflow Pengadaan aktif. Periksa workflow atau sesuaikan status SPJ.');
            $spj->update(['pengadaan_step' => 'workflow', 'pengadaan_workflow_step_id' => $mappedStep->id]);
            $step = 'workflow';
        }
        if ($step === 'workflow') {
            $workflowStep = $this->skipCompletedBastStep($spj, $this->currentWorkflowStep($spj));
            abort_unless($workflowStep, 422, 'Tahap workflow tidak ditemukan. Periksa pengaturan workflow Pengadaan.');
            abort_unless($this->userCanApproveWorkflowStep($workflowStep, $spj), 403, 'Akun Anda tidak memiliki kewenangan untuk tahap workflow ini.');
            abort_unless($this->workflowStepName($workflowStep) !== 'Admin Pengadaan', 422, 'Tahap ini memerlukan pengisian BAST.');

            if ($spj->pengadaan_workflow_step_id !== $workflowStep->id) {
                $spj->update(['pengadaan_workflow_step_id' => $workflowStep->id]);
            }

            $data = $request->validate(['keputusan' => ['required', 'in:setuju,revisi'], 'catatan' => ['nullable', 'string', 'max:5000']]);
            $approved = $data['keputusan'] === 'setuju';
            $status = $approved ? 'Selesai' : 'Revisi';
            DB::table('pengadaan_workflow_approvals')->updateOrInsert(
                ['spj_id' => $spj->id, 'workflow_step_id' => $workflowStep->id],
                ['id' => (string) Str::uuid(), 'user_id' => auth()->id(), 'status' => $status, 'catatan' => $data['catatan'] ?? null, 'acted_at' => now(), 'updated_at' => now(), 'created_at' => now()]
            );

            $stageName = $this->workflowStepName($workflowStep);
            $master = DB::table('master_spjs')->where('spj_id', $spj->id)->first();
            if ($master) {
                $this->syncWorkflowStatusToMaster($master->id, $stageName, $status, $data['catatan'] ?? null);
            }

            $next = $approved ? $this->nextWorkflowStep($workflowStep, $spj) : $workflowStep;
            $nextName = $approved && $next ? $this->workflowStepName($next) : ($approved ? 'Selesai' : $stageName);
            if ($approved && strtolower(trim($stageName)) === 'direktur' && strtolower(trim($nextName)) === 'admin pengadaan') {
                $spj->update([
                    'pengadaan_step' => 'input_penyerahan_spj_admin',
                    'pengadaan_workflow_step_id' => $next?->id,
                ]);
                $this->log(
                    $spj,
                    'Validasi Direktur',
                    'workflow',
                    'input_penyerahan_spj_admin',
                    'Disetujui. Tahap berikutnya: penyerahan dokumen SPJ oleh Admin Pengadaan.'
                );
                return back()->with('success', 'Validasi Direktur tersimpan. SPJ diteruskan ke Admin Pengadaan untuk penyerahan dokumen.');
            }
            if ($this->isBendaharaReceiptStep($stageName)) {
                $spj->update([
                    'pengadaan_step' => $approved ? 'pembayaran' : 'workflow',
                    'pengadaan_workflow_step_id' => $approved ? null : $workflowStep->id,
                ]);
                $this->log(
                    $spj,
                    $approved ? 'Konfirmasi penerimaan SPJ oleh Bendahara' : 'Revisi Konfirmasi Terima SPJ',
                    'workflow',
                    $approved ? 'pembayaran' : 'workflow',
                    $data['catatan'] ?? null
                );

                return back()->with(
                    'success',
                    $approved
                        ? 'Penerimaan SPJ dikonfirmasi. Silakan lanjutkan ke form pembayaran.'
                        : 'SPJ dikembalikan untuk diperbaiki pada tahap konfirmasi penerimaan.'
                );
            }
            $nextLegacyStep = $approved && $next && $nextName === 'Bendahara' ? 'serah_terima_bendahara' : 'workflow';
            $spj->update([
                'pengadaan_step' => $approved ? ($next ? $nextLegacyStep : 'selesai') : 'workflow',
                'pengadaan_workflow_step_id' => $next?->id ?? ($approved ? null : $workflowStep->id),
            ]);
            $this->log($spj, $approved ? 'Validasi ' . $stageName : 'Revisi ' . $stageName, 'workflow', $next ? $nextLegacyStep : 'selesai', $data['catatan'] ?? null);
            return back()->with('success', $approved ? 'Validasi tersimpan. SPJ diteruskan ke ' . ($next ? $nextName : 'selesai') . '.' : 'SPJ dikembalikan untuk revisi pada tahap ini.');
        }

        $this->authorizeStep($step);
        $master = DB::table('master_spjs')->where('spj_id', $spj->id)->first();
        abort_unless($master, 422, 'Data master SPJ untuk penyedia ini tidak ditemukan.');

        $definitions = [
            'validasi_ppk' => ['status_verifikasi_ppk', 'ket_verif_ppk', 'tgl_verif'],
            'validasi_ppbj' => ['status_verifikasi_ppbj', 'ket_verif_ppbj', 'tgl_verif_ppbj'],
            'validasi_pptk' => ['status_verifikasi_pptk', 'ket_verif_pptk', 'tgl_verif_pptk'],
            'validasi_verifikator' => ['status_verifikasi_verifikator', 'ket_verif_verifikator', 'tgl_verif_verifikator'],
            'validasi_ppk_keuangan' => ['status_verifikasi_ppk_keuangan', 'ket_verif_ppk_keuangan', 'tgl_verif_ppk_keuangan'],
            'validasi_direktur' => ['status_verifikasi_direktur', 'ket_verif_direktur', 'tgl_verif_direktur'],
        ];
        $order = ['validasi_ppk', 'validasi_ppbj', 'validasi_pptk', 'validasi_verifikator', 'validasi_ppk_keuangan', 'validasi_direktur', 'serah_terima_bendahara', 'verifikasi_bendahara', 'pembayaran'];
        $nextIndex = array_search($step, $order, true) + 1;

        if (isset($definitions[$step])) {
            [$statusField, $noteField, $dateField] = $definitions[$step];
            $data = $request->validate(['keputusan' => ['required', 'in:setuju,revisi'], 'catatan' => ['nullable', 'string', 'max:5000']]);
            $approved = $data['keputusan'] === 'setuju';
            $updates = [$statusField => $approved ? 'Selesai' : 'Revisi', $noteField => $data['catatan'] ?? null, $dateField => now()->toDateString(), 'updated_at' => now()];
            if (! $approved && $step === 'validasi_ppk_keuangan') $updates['tgl_revisi_spj_ppk_keuangan'] = now()->toDateString();
            if (! $approved && $step === 'validasi_direktur') $updates['tgl_revisi_spj_direktur'] = now()->toDateString();
            DB::table('master_spjs')->where('id', $master->id)->update($updates);
            $next = $approved ? $order[$nextIndex] : $step;
            $spj->update(['pengadaan_step' => $next]);
            $this->log($spj, $approved ? 'Validasi ' . self::STEPS[$step] : 'Revisi ' . self::STEPS[$step], $step, $next, $data['catatan'] ?? null);
            return back()->with('success', $approved ? 'Validasi tersimpan. SPJ diteruskan ke ' . self::STEPS[$next] . '.' : 'SPJ dikembalikan untuk revisi pada tahap ini.');
        }

        if ($step === 'serah_terima_bendahara') {
            $data = $request->validate(['keputusan' => ['required', 'in:setuju,revisi'], 'catatan' => ['nullable', 'string', 'max:5000'], 'bruto' => ['required', 'numeric', 'min:0'], 'harga_dasar' => ['required', 'numeric', 'min:0'], 'tanggal_penyerahan_spj' => ['required', 'date']]);
            $approved = $data['keputusan'] === 'setuju';
            DB::table('master_spjs')->where('id', $master->id)->update([
                'status_serah_terima_bendahara' => $approved ? 'Selesai' : 'Revisi',
                'ket_bendahara' => $data['catatan'] ?? null,
                'bruto' => $data['bruto'],
                'harga_dasar' => $data['harga_dasar'],
                'tanggal_penyerahan_spj' => $data['tanggal_penyerahan_spj'],
                'bulan_penyerahan_spj' => \Illuminate\Support\Carbon::parse($data['tanggal_penyerahan_spj'])->translatedFormat('F'),
                'tanggal_revisi_spj' => $approved ? $master->tanggal_revisi_spj : now()->toDateString(),
                'updated_at' => now(),
            ]);
            // Receiving and validating the handover completes the Bendahara
            // acceptance stage; the next action is payment, without a duplicate
            // verification step in between.
            $next = $approved ? 'pembayaran' : $step;
        } elseif ($step === 'verifikasi_bendahara') {
            $data = $request->validate(['keputusan' => ['required', 'in:setuju,revisi'], 'catatan' => ['nullable', 'string', 'max:5000']]);
            $approved = $data['keputusan'] === 'setuju';
            DB::table('master_spjs')->where('id', $master->id)->update(['keterangan' => $approved ? 'Selesai' : 'Revisi', 'ket_verif_bendahara' => $data['catatan'] ?? null, 'tgl_verif_bendahara' => now()->toDateString(), 'updated_at' => now()]);
            $next = $approved ? 'pembayaran' : $step;
        } elseif ($step === 'pembayaran') {
            $data = $request->validate([
                'pembayaran' => ['nullable', 'required_if:status_pembayaran,Selesai', 'numeric', 'min:0'],
                'tanggal_pembayaran' => ['required', 'date'],
                'status_pembayaran' => ['required', 'in:Selesai,Revisi'],
                'ppn' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'pph21' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'pph22' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'pph23' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'pp05' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'bpjs_kes' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
                'bpjs_tk' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
                'kode_billingppn' => ['nullable', 'string', 'max:255'],
                'kode_billingpph22' => ['nullable', 'string', 'max:255'],
                'catatan' => ['nullable', 'string', 'max:5000'],
                'bukti_bayar' => ['nullable', 'required_if:status_pembayaran,Selesai', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            ]);
            $spj->loadMissing('details.item');
            $hargaDasar = (float) $spj->details->sum(fn ($detail) => (float) ($detail->item?->harga_satuan ?? 0) * (float) $detail->volume);
            $totalPpnUsulan = (float) $spj->details->sum(fn ($detail) => (float) ($detail->item?->harga_satuan ?? 0) * (float) $detail->volume * (float) ($detail->item?->ppn ?? 0) / 100);
            $nilaiTagihan = $hargaDasar + $totalPpnUsulan;
            $totalPajak = round($hargaDasar * (
                (float) ($data['ppn'] ?? $master->ppn ?? 0)
                + (float) ($data['pph21'] ?? $master->pph21 ?? 0)
                + (float) ($data['pph22'] ?? $master->pph22 ?? 0)
                + (float) ($data['pph23'] ?? $master->pph23 ?? 0)
                + (float) ($data['pp05'] ?? $master->pp05 ?? 0)
            ) / 100);
            $nilaiPembayaran = $data['status_pembayaran'] === 'Selesai' ? $hargaDasar + $totalPajak : 0;
            $totalTerbayar = (float) ($master->pembayaran ?? 0) + $nilaiPembayaran;
            if ($data['status_pembayaran'] === 'Selesai' && $totalTerbayar > $nilaiTagihan) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'pembayaran' => 'Jumlah pembayaran melebihi sisa tagihan SPJ.',
                ]);
            }
            $sisaPembayaran = max(0, $nilaiTagihan - $totalTerbayar);
            if ($data['status_pembayaran'] === 'Selesai' && $sisaPembayaran > 0) {
                throw ValidationException::withMessages([
                    'status_pembayaran' => 'Status Selesai hanya dapat dipilih jika seluruh total usulan belanja sudah dibayarkan.',
                ]);
            }
            $path = $request->file('bukti_bayar')?->store('spjs/bukti-bayar', 'public');
            DB::table('master_spjs')->where('id', $master->id)->update([
                'pembayaran' => $totalTerbayar,
                'sisa_pembayaran' => $sisaPembayaran,
                'tanggal_pembayaran' => $data['tanggal_pembayaran'],
                'bulan_pembayaran' => Carbon::parse($data['tanggal_pembayaran'])->translatedFormat('F'),
                'status_pembayaran' => $data['status_pembayaran'],
                'bruto' => $nilaiTagihan,
                'harga_dasar' => $hargaDasar,
                'jumlah_pajak' => $totalPajak,
                'harga_bersih' => max(0, $nilaiTagihan - $totalPajak),
                'ppn' => $data['ppn'] ?? $master->ppn,
                'pph21' => $data['pph21'] ?? $master->pph21,
                'pph22' => $data['pph22'] ?? $master->pph22,
                'pph23' => $data['pph23'] ?? $master->pph23,
                'pp05' => $data['pp05'] ?? $master->pp05,
                'bpjs_kes' => $data['bpjs_kes'] ?? $master->bpjs_kes,
                'bpjs_tk' => $data['bpjs_tk'] ?? $master->bpjs_tk,
                'kode_billingppn' => $data['kode_billingppn'] ?? $master->kode_billingppn,
                'kode_billingpph22' => $data['kode_billingpph22'] ?? $master->kode_billingpph22,
                'bukti_bayar' => $path ?? $master->bukti_bayar,
                'ket_verif_bendahara' => $data['catatan'] ?? $master->ket_verif_bendahara,
                'tgl_verif_bendahara' => now()->toDateString(),
                'updated_at' => now(),
            ]);
            if ($data['status_pembayaran'] === 'Selesai' && $nilaiPembayaran > 0 && Schema::hasTable('master_spj_payment_transactions')) {
                DB::table('master_spj_payment_transactions')->insert([
                    'id' => (string) Str::uuid(),
                    'master_spj_id' => $master->id,
                    'amount' => $nilaiPembayaran,
                    'tanggal_pembayaran' => $data['tanggal_pembayaran'],
                    'bulan_pembayaran' => Carbon::parse($data['tanggal_pembayaran'])->translatedFormat('F'),
                    'bukti_bayar' => $path,
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $next = $data['status_pembayaran'] === 'Revisi'
                ? 'pembayaran'
                : ($sisaPembayaran > 0 ? 'pembayaran' : 'selesai');
            $approved = $data['status_pembayaran'] === 'Selesai';
        } else {
            abort(403, 'Tahap SPJ tidak valid.');
        }

        $spjUpdates = [
            'pengadaan_step' => $next,
            'pengadaan_workflow_step_id' => $next === 'selesai' ? null : $spj->pengadaan_workflow_step_id,
        ];
        if ($next === 'selesai') {
            $spjUpdates['status_spj'] = 'Sudah di Bayar';
        }
        $spj->update($spjUpdates);
        $this->log($spj, $approved ? 'Selesaikan ' . self::STEPS[$step] : 'Revisi ' . self::STEPS[$step], $step, $next, $data['catatan'] ?? null);
        return back()->with('success', $approved ? 'Data tersimpan. SPJ diteruskan ke ' . self::STEPS[$next] . '.' : 'SPJ dikembalikan untuk revisi pada tahap ini.');
    }

    public function assignments()
    {
        $this->authorizeAdmin();
        $subKategoris = SubKategoriRkbu::query()->with('pendukungPpk')->orderBy('nama_sub_kategori_rkbu')->get();
        $users = User::query()->whereHas('roles', fn($q) => $q->where('nama_role', 'Pendukung PPK'))
            ->orderBy('nama')->get(['id', 'nama', 'username']);
        return view('usulan_belanja.pengadaan.assignments', compact('subKategoris', 'users'));
    }

    public function updateAssignment(Request $request, SubKategoriRkbu $subKategori)
    {
        $this->authorizeAdmin();
        $data = $request->validate(['user_id' => ['nullable', 'uuid', 'exists:users,id']]);
        if (empty($data['user_id'])) {
            DB::table('sub_kategori_pendukung_ppk')->where('sub_kategori_rkbu_id', $subKategori->id_sub_kategori_rkbu)->delete();
            return back()->with('success', 'Penugasan Pendukung PPK dihapus.');
        }
        abort_unless(User::query()->whereKey($data['user_id'])->whereHas('roles', fn($q) => $q->where('nama_role', 'Pendukung PPK'))->exists(), 422, 'User harus memiliki role Pendukung PPK.');
        DB::table('sub_kategori_pendukung_ppk')->updateOrInsert(
            ['sub_kategori_rkbu_id' => $subKategori->id_sub_kategori_rkbu],
            ['id' => (string) Str::uuid(), 'user_id' => $data['user_id'], 'updated_at' => now(), 'created_at' => now()]
        );
        return back()->with('success', 'Pendukung PPK berhasil ditugaskan pada subkategori tersebut.');
    }

    private function authorizeView(Spj $spj): void
    {
        $user = auth()->user();
        $workflowStep = $this->currentWorkflowStep($spj);
        if (! $workflowStep && $spj->pengadaan_step === 'input_bast') {
            $workflowStep = $this->workflowStepAfterIdentity('pengurus barang', $spj);
        }
        if (! $workflowStep && str_starts_with($spj->pengadaan_step, 'validasi_') && $spj->pengadaan_step !== 'validasi_barang') {
            $workflowStep = $this->legacyWorkflowStep($spj->pengadaan_step, $spj);
        }
        $workflowAllowed = $workflowStep && $this->userCanApproveWorkflowStep($workflowStep, $spj);
        $allowed = $user->hasRole('Administrator') || $user->hasRole('Super Admin')
            || $user->hasRole('Admin Pengadaan') || $user->hasRole('Pengurus Barang')
            || $this->userCanApproveIdentity('pengurus barang', $spj) || $this->isAssignedPendukung($spj)
            || $this->userCanHandleStep($spj->pengadaan_step) || $workflowAllowed;
        abort_unless($allowed, 403);
    }

    private function isAssignedPendukung(Spj $spj): bool
    {
        if (! auth()->user()->hasRole('Pendukung PPK')) {
            return false;
        }
        $subcategoryId = $spj->usulanBelanja()->value('sub_kategori_rkbu_id');
        return DB::table('sub_kategori_pendukung_ppk')
            ->where('sub_kategori_rkbu_id', $subcategoryId)
            ->where('user_id', auth()->id())->exists();
    }

    private function authorizeRole(string $role): void
    {
        abort_unless(auth()->user()->hasRole($role) || auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'), 403);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'), 403);
    }

    private function pengadaanWorkflowId(): ?string
    {
        $workflow = DB::table('workflows')->whereRaw('LOWER(nama_workflow) = ?', ['workflow pengadaan'])->first(['id']);
        if ($workflow) return $workflow->id;

        return DB::table('workflows')->whereRaw('LOWER(modul) = ?', ['pengadaan'])->orderByDesc('updated_at')->value('id');
    }

    private function workflowStepsForSpj(Spj $spj, ?string $workflowId = null)
    {
        $workflowId = $workflowId ?: $this->pengadaanWorkflowId();
        if (! $workflowId) {
            return collect();
        }

        $query = $this->workflowStepQuery()->where('ws.workflow_id', $workflowId);
        if (Schema::hasTable('workflow_step_sub_kategori_rkbu')) {
            $subcategoryId = $spj->usulanBelanja()->value('sub_kategori_rkbu_id');
            $mappedStepIds = $subcategoryId
                ? DB::table('workflow_step_sub_kategori_rkbu')
                    ->where('sub_kategori_rkbu_id', $subcategoryId)
                    ->pluck('workflow_step_id')
                : collect();

            if ($mappedStepIds->isNotEmpty()) {
                // Once a subcategory has an explicit route, only its mapped steps apply.
                $query->whereIn('ws.id', $mappedStepIds);
            }
        }

        return $query->orderBy('ws.step_order')->get();
    }

    private function hasCustomWorkflowForSpj(Spj $spj, ?string $workflowId = null): bool
    {
        if (! Schema::hasTable('workflow_step_sub_kategori_rkbu')) {
            return false;
        }

        $workflowId = $workflowId ?: $this->pengadaanWorkflowId();
        $subcategoryId = $spj->usulanBelanja()->value('sub_kategori_rkbu_id');
        if (! $workflowId || ! $subcategoryId) {
            return false;
        }

        return DB::table('workflow_step_sub_kategori_rkbu as mapping')
            ->join('workflow_steps as ws', 'ws.id', '=', 'mapping.workflow_step_id')
            ->where('ws.workflow_id', $workflowId)
            ->where('mapping.sub_kategori_rkbu_id', $subcategoryId)
            ->exists();
    }

    private function workflowStepAfterIdentity(string $identity, ?Spj $spj = null): ?object
    {
        $workflowId = $this->pengadaanWorkflowId();
        if (! $workflowId) return null;
        $allSteps = $this->workflowStepQuery()->where('ws.workflow_id', $workflowId)->orderBy('ws.step_order')->get();
        $current = $allSteps->first(fn($step) => $this->matchesWorkflowIdentity($step, $identity));
        if (! $current) return null;
        $steps = $spj ? $this->workflowStepsForSpj($spj, $workflowId) : $allSteps;
        return $steps->first(fn($step) => $step->step_order > $current->step_order);
    }

    private function workflowStepByIdentity(string $identity, ?Spj $spj = null): ?object
    {
        $workflowId = $this->pengadaanWorkflowId();
        if (! $workflowId) return null;
        $steps = $spj
            ? $this->workflowStepsForSpj($spj, $workflowId)
            : $this->workflowStepQuery()->where('ws.workflow_id', $workflowId)->orderBy('ws.step_order')->get();
        return $steps
            ->first(fn($step) => $this->matchesWorkflowIdentity($step, $identity));
    }

    private function matchesWorkflowIdentity(object $step, string $identity): bool
    {
        $identity = strtolower(trim($identity));
        return collect([
            $this->workflowStepName($step),
            $step->system_role_name ?? null,
            $step->nama_validator ?? null,
            $step->level_jabatan ?? null,
        ])->contains(fn($name) => strtolower(trim((string) $name)) === $identity);
    }

    private function userHasWorkflowIdentity($user, string $identity): bool
    {
        $step = $this->workflowStepByIdentity($identity);
        return $step ? $this->userMatchesWorkflowStep($user, $step) : false;
    }

    private function userCanApproveIdentity(string $identity, Spj $spj): bool
    {
        $step = $this->workflowStepByIdentity($identity, $spj);
        return $step ? $this->userCanApproveWorkflowStep($step, $spj) : false;
    }

    private function legacyWorkflowStep(string $legacyStep, ?Spj $spj = null): ?object
    {
        $workflowId = $this->pengadaanWorkflowId();
        if (! $workflowId) return null;
        $steps = $spj
            ? $this->workflowStepsForSpj($spj, $workflowId)
            : $this->workflowStepQuery()->where('ws.workflow_id', $workflowId)->orderBy('ws.step_order')->get();
        $identity = [
            'validasi_ppk' => ['ppk'],
            'validasi_ppbj' => ['ppbj'],
            'validasi_pptk' => ['pptk'],
            'validasi_verifikator' => ['verifikator pengadaan', 'verifikator'],
            'validasi_ppk_keuangan' => ['ppk keuangan'],
        ][$legacyStep] ?? null;
        if ($legacyStep === 'validasi_direktur') return $steps->first(fn($step) => $step->validator_type === 'hierarchy' || strtolower(trim($this->workflowStepName($step))) === 'direktur');
        if (! $identity) return null;
        return $steps->first(fn($step) => in_array(strtolower(trim($this->workflowStepName($step))), $identity, true));
    }

    private function bastWorkflowStep(?Spj $spj = null): ?object
    {
        $workflowId = $this->pengadaanWorkflowId();
        if (! $workflowId) return null;
        $steps = $spj
            ? $this->workflowStepsForSpj($spj, $workflowId)
            : $this->workflowStepQuery()->where('ws.workflow_id', $workflowId)->orderBy('ws.step_order')->get();
        return $steps->first(fn($step) => $this->isBastWorkflowStep($step, $spj));
    }

    private function skipCompletedBastStep(Spj $spj, ?object $workflowStep): ?object
    {
        if (! $workflowStep || ! $this->isBastWorkflowStep($workflowStep, $spj)) {
            return $workflowStep;
        }

        $bastComplete = filled($spj->tgl_bast)
            && filled($spj->no_ba)
            && filled($spj->no_ba_hp)
            && filled($spj->no_ba_bp)
            && filled($spj->no_dpa);
        if (! $bastComplete) {
            return $workflowStep;
        }

        $master = DB::table('master_spjs')->where('spj_id', $spj->id)->first();
        $fakturComplete = $master && (
            $master->status_proses_tukar_faktur === 'Selesai'
            || (filled($master->tanggal_faktur ?? null)
                && filled($master->tgl_kwitansi ?? null)
                && filled($master->no_faktur ?? null))
        );
        if (! $fakturComplete) {
            return $workflowStep;
        }

        $steps = $this->workflowStepsForSpj($spj, $workflowStep->workflow_id)
            ->filter(fn ($candidate) => $candidate->step_order > $workflowStep->step_order)
            ->values();
        $approvedIds = Schema::hasTable('pengadaan_workflow_approvals')
            ? DB::table('pengadaan_workflow_approvals')
                ->where('spj_id', $spj->id)
                ->whereIn('status', ['Selesai', 'approved', 'setuju'])
                ->pluck('workflow_step_id')
                ->all()
            : [];

        return $steps->first(function ($candidate) use ($approvedIds) {
            if (strtolower(trim($this->workflowStepName($candidate))) === 'admin pengadaan') {
                return false;
            }

            return ! in_array($candidate->id, $approvedIds, true);
        }) ?? $workflowStep;
    }

    private function isBastWorkflowStep(object $step, ?Spj $spj = null): bool
    {
        if (strtolower(trim($this->workflowStepName($step))) !== 'admin pengadaan') return false;
        $steps = $this->workflowStepQuery()->where('ws.workflow_id', $step->workflow_id)->orderBy('ws.step_order')->get();
        $pengurusStep = $steps->first(fn ($candidate) => $this->matchesWorkflowIdentity($candidate, 'pengurus barang'));
        return $pengurusStep && $step->step_order > $pengurusStep->step_order;
    }

    private function isBendaharaReceiptStep(?string $stepName): bool
    {
        return in_array(strtolower(trim((string) $stepName)), ['bendahara', 'verifikasi bendahara'], true);
    }

    private function currentWorkflowStep(Spj $spj): ?object
    {
        $workflowId = $this->pengadaanWorkflowId();
        if (! $workflowId) return null;
        $steps = $this->workflowStepsForSpj($spj, $workflowId);
        if ($spj->pengadaan_workflow_step_id) {
            $current = $steps->first(fn ($candidate) => $candidate->id === $spj->pengadaan_workflow_step_id);
            if ($current) return $current;
        }

        if (Schema::hasTable('pengadaan_workflow_approvals') && $steps->isNotEmpty()) {
            $lastApprovedOrder = DB::table('pengadaan_workflow_approvals as approval')
                ->join('workflow_steps as ws', 'ws.id', '=', 'approval.workflow_step_id')
                ->where('approval.spj_id', $spj->id)
                ->whereIn('ws.id', $steps->pluck('id'))
                ->whereIn('approval.status', ['Selesai', 'approved', 'setuju'])
                ->orderByDesc('approval.acted_at')
                ->value('ws.step_order');
            if ($lastApprovedOrder !== null) {
                return $steps->first(fn ($candidate) => $candidate->step_order > $lastApprovedOrder);
            }
        }

        if ($this->hasCustomWorkflowForSpj($spj, $workflowId)) {
            return $steps->first();
        }

        return $this->workflowStepAfterIdentity('pengurus barang', $spj);
    }

    private function nextWorkflowStep(object $step, ?Spj $spj = null): ?object
    {
        $steps = $spj
            ? $this->workflowStepsForSpj($spj, $step->workflow_id)
            : $this->workflowStepQuery()->where('ws.workflow_id', $step->workflow_id)->orderBy('ws.step_order')->get();
        return $steps->first(fn ($candidate) => $candidate->step_order > $step->step_order);
    }

    private function workflowStepQuery()
    {
        return DB::table('workflow_steps as ws')
            ->join('workflows as w', 'w.id', '=', 'ws.workflow_id')
            ->leftJoin('positions as p', 'p.id', '=', 'ws.position_id')
            ->leftJoin('roles as r', 'r.id', '=', 'ws.role_id')
            ->leftJoin('validator_roles as vr', 'vr.id', '=', 'ws.validator_role_id')
            ->select('ws.*', 'w.nama_workflow', 'w.modul', 'p.level_jabatan', 'r.nama_role as system_role_name', 'vr.nama_validator');
    }

    private function workflowStepName(object $step): string
    {
        if ($step->is_pptk_step) return 'PPTK';
        if ($step->validator_type === 'hierarchy') return 'Validasi Atasan (Hirarki ' . max(1, (int) ($step->hierarchy_level ?? 1)) . ')';
        return $step->system_role_name ?: ($step->nama_validator ?: ($step->level_jabatan ?: 'Validasi Workflow'));
    }

    private function userCanApproveWorkflowStep(object $step, Spj $spj): bool
    {
        $user = auth()->user();
        if ($user->hasRole('Administrator') || $user->hasRole('Super Admin')) return true;

        if ($step->is_pptk_step) {
            $subcategoryId = $spj->usulanBelanja()->value('sub_kategori_rkbu_id');
            return $subcategoryId && DB::table('position_sub_kategori_rkbus as mapping')
                ->join('position_assignments as assignment', 'assignment.position_id', '=', 'mapping.position_id')
                ->join('users as assigned_user', 'assigned_user.id', '=', 'assignment.user_id')
                ->where('mapping.id_sub_kategori_rkbu', $subcategoryId)
                ->where('assignment.user_id', $user->id)
                ->where('assignment.is_active', true)
                ->whereDate('assignment.tanggal_mulai', '<=', today())
                ->where(fn($query) => $query->whereNull('assignment.tanggal_selesai')->orWhereDate('assignment.tanggal_selesai', '>=', today()))
                ->where('assigned_user.status_user', 'aktif')->exists();
        }

        if ($this->userMatchesWorkflowStep($user, $step)) return true;
        if ($step->validator_type === 'hierarchy') {
            $requester = $spj->usulanBelanja()->with('pengusul.position')->first()?->pengusul?->position;
            $levels = max(1, (int) ($step->hierarchy_level ?? 1));
            for ($i = 0; $i < $levels && $requester; $i++) {
                $requester = $requester->parent;
            }
            return $requester && $user->position_id === $requester->id;
        }
        return false;
    }

    private function workflowStepIdsForUser($user): array
    {
        $workflowId = $this->pengadaanWorkflowId();
        if (! $workflowId) return [];
        return $this->workflowStepQuery()->where('ws.workflow_id', $workflowId)->get()
            ->filter(fn($step) => $this->userMatchesWorkflowStep($user, $step) || $step->validator_type === 'hierarchy')
            ->pluck('id')->all();
    }

    private function staleWorkflowSpjIdsForUser($user): array
    {
        $workflowId = $this->pengadaanWorkflowId();
        if (! $workflowId) return [];
        return Spj::query()->where('pengadaan_step', 'workflow')->whereNotNull('pengadaan_workflow_step_id')
            ->whereNotExists(function ($query) use ($workflowId) {
                $query->selectRaw('1')->from('workflow_steps as current_step')
                    ->whereColumn('current_step.id', 'spjs.pengadaan_workflow_step_id')
                    ->where('current_step.workflow_id', $workflowId);
            })->get()->filter(function (Spj $spj) use ($user) {
                $step = $this->currentWorkflowStep($spj);
                return $step && $this->userCanApproveWorkflowStep($step, $spj);
            })->pluck('id')->all();
    }

    private function userMatchesWorkflowStep($user, object $step): bool
    {
        if ($step->is_pptk_step) {
            return DB::table('position_sub_kategori_rkbus as mapping')
                ->join('position_assignments as assignment', 'assignment.position_id', '=', 'mapping.position_id')
                ->where('assignment.user_id', $user->id)
                ->where('assignment.is_active', true)
                ->whereDate('assignment.tanggal_mulai', '<=', today())
                ->where(fn($query) => $query->whereNull('assignment.tanggal_selesai')->orWhereDate('assignment.tanggal_selesai', '>=', today()))
                ->exists();
        }
        if ($step->validator_type === 'position') return $user->position_id === $step->position_id;
        if ($step->validator_type === 'user') {
            return $step->validator_role_id
                && $user->validatorRoles()->where('validator_roles.id', $step->validator_role_id)->exists();
        }
        if ($step->validator_type === 'role') {
            if ($step->role_id && $user->roles()->where('roles.id', $step->role_id)->exists()) return true;
            if ($step->validator_role_id && $user->validatorRoles()->where('validator_roles.id', $step->validator_role_id)->exists()) return true;
            $roleName = strtolower(trim((string) $step->system_role_name));
            return $roleName !== '' && strtolower(trim((string) $user->position?->level_jabatan)) === $roleName;
        }
        if ($step->validator_type === 'hierarchy' && $step->validator_role_id) {
            return $user->validatorRoles()->where('validator_roles.id', $step->validator_role_id)->exists();
        }
        return false;
    }

    private function syncWorkflowStatusToMaster(string $masterId, string $name, string $status, ?string $note): void
    {
        $key = strtolower(trim($name));
        $fields = [
            'ppk' => ['status_verifikasi_ppk', 'ket_verif_ppk', 'tgl_verif'],
            'ppbj' => ['status_verifikasi_ppbj', 'ket_verif_ppbj', 'tgl_verif_ppbj'],
            'pptk' => ['status_verifikasi_pptk', 'ket_verif_pptk', 'tgl_verif_pptk'],
            'verifikator' => ['status_verifikasi_verifikator', 'ket_verif_verifikator', 'tgl_verif_verifikator'],
            'verifikator pengadaan' => ['status_verifikasi_verifikator', 'ket_verif_verifikator', 'tgl_verif_verifikator'],
            'ppk keuangan' => ['status_verifikasi_ppk_keuangan', 'ket_verif_ppk_keuangan', 'tgl_verif_ppk_keuangan'],
            'direktur' => ['status_verifikasi_direktur', 'ket_verif_direktur', 'tgl_verif_direktur'],
        ];
        if (! isset($fields[$key])) return;
        [$statusField, $noteField, $dateField] = $fields[$key];
        DB::table('master_spjs')->where('id', $masterId)->update([$statusField => $status, $noteField => $note, $dateField => now()->toDateString(), 'updated_at' => now()]);
    }

    private function canProcessStep(string $step): bool
    {
        if (in_array($step, ['validasi_ppk', 'validasi_ppbj', 'validasi_pptk', 'validasi_verifikator', 'validasi_ppk_keuangan', 'validasi_direktur', 'serah_terima_bendahara', 'verifikasi_bendahara', 'pembayaran'], true)) {
            return $this->userCanHandleStep($step);
        }
        return false;
    }

    private function authorizeStep(string $step): void
    {
        abort_unless($this->canProcessStep($step), 403, 'Akun Anda tidak memiliki kewenangan untuk tahap SPJ ini.');
    }

    private function userCanHandleStep(string $step): bool
    {
        $user = auth()->user();
        if ($user->hasRole('Administrator') || $user->hasRole('Super Admin')) {
            return true;
        }
        $levels = [
            'validasi_ppk' => ['PPK'],
            'validasi_ppbj' => ['PPBJ'],
            'validasi_pptk' => ['PPTK'],
            'validasi_verifikator' => ['Verifikator'],
            'validasi_ppk_keuangan' => ['PPK Keuangan'],
            'validasi_direktur' => ['Direktur'],
            'serah_terima_bendahara' => ['Admin Pengadaan', 'Bendahara'],
            'verifikasi_bendahara' => ['Bendahara'],
            'pembayaran' => ['Bendahara'],
        ];
        $required = $levels[$step] ?? [];
        $positionLevel = $user->position?->level_jabatan;
        return in_array($positionLevel, $required, true)
            || ($step === 'validasi_verifikator' && $user->hasRole('Validator'))
            || (in_array($step, ['serah_terima_bendahara', 'verifikasi_bendahara', 'pembayaran'], true) && $user->hasRole('Bendahara'));
    }

    private function log(Spj $spj, string $aksi, ?string $from, ?string $to, ?string $catatan): void
    {
        DB::table('pengadaan_workflow_logs')->insert([
            'id' => (string) Str::uuid(),
            'spj_id' => $spj->id,
            'user_id' => auth()->id(),
            'aksi' => Str::limit($aksi, 40, ''),
            'step_dari' => $from,
            'step_ke' => $to,
            'catatan' => $catatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
