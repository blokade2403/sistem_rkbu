<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\DataTables\UsulanBelanjaDataTable;
use App\Exports\UsulanBelanjaExport;
use App\Http\Requests\UsulanBelanja\StoreUsulanBelanjaRequest;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Services\Workflow\UsulanBelanjaWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class UsulanBelanjaController extends Controller
{
    public function __construct(
        private UsulanBelanjaWorkflowService $workflow
    ) {}

    public function index(Request $request)
    {
        $tahunAnggarans = DB::table('tahun_anggarans')
            ->where('id', session('tahun_anggaran_id'))
            ->orderByDesc('nama_tahun_anggaran')
            ->get();

        $jenisKategoriRkbus = DB::table('jenis_kategori_rkbus')
            ->join('kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('usulan_belanjas', 'usulan_belanjas.sub_kategori_rkbu_id', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->select('jenis_kategori_rkbus.id_jenis_kategori_rkbu', 'jenis_kategori_rkbus.nama_jenis_kategori_rkbu')
            ->distinct()
            ->orderBy('jenis_kategori_rkbus.nama_jenis_kategori_rkbu')
            ->get();
        $subKategoriRkbus = DB::table('sub_kategori_rkbus')
            ->join('kategori_rkbus', 'kategori_rkbus.id_kategori_rkbu', '=', 'sub_kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('usulan_belanjas', 'usulan_belanjas.sub_kategori_rkbu_id', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->select('sub_kategori_rkbus.id_sub_kategori_rkbu', 'sub_kategori_rkbus.nama_sub_kategori_rkbu', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->distinct()
            ->orderBy('sub_kategori_rkbus.nama_sub_kategori_rkbu')
            ->get();

        return view('usulan_belanja.index', compact('tahunAnggarans', 'jenisKategoriRkbus', 'subKategoriRkbus'));
    }

    public function rekap(Request $request)
    {
        $submitted = UsulanBelanja::query()
            ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
            ->whereNotNull('workflow_instance_id');

        $jenisKategoriRkbus = DB::table('usulan_belanjas as usulan')
            ->join('jenis_kategori_rkbus as jenis', 'jenis.id_jenis_kategori_rkbu', '=', 'usulan.jenis_kategori_rkbu_id')
            ->where('usulan.tahun_anggaran_id', session('tahun_anggaran_id'))
            ->whereNotNull('usulan.workflow_instance_id')
            ->select('jenis.id_jenis_kategori_rkbu', 'jenis.nama_jenis_kategori_rkbu')
            ->distinct()->orderBy('jenis.nama_jenis_kategori_rkbu')->get();
        $subKategoriRkbus = DB::table('usulan_belanjas as usulan')
            ->join('sub_kategori_rkbus as subkategori', 'subkategori.id_sub_kategori_rkbu', '=', 'usulan.sub_kategori_rkbu_id')
            ->where('usulan.tahun_anggaran_id', session('tahun_anggaran_id'))
            ->whereNotNull('usulan.workflow_instance_id')
            ->select('subkategori.id_sub_kategori_rkbu', 'subkategori.nama_sub_kategori_rkbu', 'usulan.jenis_kategori_rkbu_id')
            ->distinct()->orderBy('subkategori.nama_sub_kategori_rkbu')->get();
        $pengusuls = (clone $submitted)
            ->select('user_id', 'nama_pengusul')->distinct()->orderBy('nama_pengusul')->get();

        if ($request->filled('jenis_kategori_rkbu_id')) {
            $submitted->where('jenis_kategori_rkbu_id', $request->input('jenis_kategori_rkbu_id'));
        }
        if ($request->filled('sub_kategori_rkbu_id')) {
            $submitted->where('sub_kategori_rkbu_id', $request->input('sub_kategori_rkbu_id'));
        }
        if ($request->filled('pengusul_id')) {
            $submitted->where('user_id', $request->input('pengusul_id'));
        }
        $allowedStatuses = ['proses', 'revision', 'approved', 'rejected'];
        if (in_array($request->input('status'), $allowedStatuses, true)) {
            $submitted->where('status_usulan', $request->input('status'));
        }

        $usulans = $submitted->with([
            'pengusul',
            'unit',
            'rkbu.subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'tahunAnggaran',
        ])->addSelect([
            'total_nominal' => DB::table('usulan_belanja_items')
                ->selectRaw('COALESCE(SUM(harga_satuan * volume * (1 + COALESCE(ppn, 0) / 100)), 0)')
                ->whereColumn('usulan_belanja_items.usulan_belanja_id', 'usulan_belanjas.id')
                ->whereNotNull('detail_type')
                ->whereNotNull('detail_id'),
        ])->latest()->paginate(20)->withQueryString();

        return view('usulan_belanja.rekap', compact('usulans', 'jenisKategoriRkbus', 'subKategoriRkbus', 'pengusuls'));
    }

    public function data(Request $request, UsulanBelanjaDataTable $dataTable)
    {
        return $dataTable->render($request);
    }

    public function export(Request $request)
    {
        return Excel::download(
            new UsulanBelanjaExport($request),
            'data-usulan-belanja-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function print(UsulanBelanja $usulanBelanja)
    {
        $user = auth()->user();
        $isAdministrator = $user->roles()
            ->where('nama_role', 'Administrator')
            ->exists();
        $isValidator = $usulanBelanja->workflow_instance_id
            && DB::table('approvals')
            ->where('workflow_instance_id', $usulanBelanja->workflow_instance_id)
            ->where('validator_user_id', $user->id)
            ->exists();

        abort_unless(
            $usulanBelanja->workflow_instance_id
                && ($isAdministrator || $usulanBelanja->user_id === $user->id || $isValidator),
            403
        );

        $usulanBelanja->load([
            'pengusul',
            'unit',
            'tahunAnggaran',
            'sumberDana',
            'rkbu.unit',
            'rkbu.sumberDana',
            'rkbu.subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'rkbu.subKategoriRkbu.sub_kategori_rekening.kategori_rekening',
            'rkbu.subKegiatan.kegiatan.program',
            'rkbu.subKegiatan.aktivitas.program',
            'workflowInstance.approvals.validatorUser.position',
            'workflowInstance.approvals.position',
            'workflowInstance.approvals.workflowStep.position',
            'workflowInstance.approvals.workflowStep.systemRole',
            'workflowInstance.approvals.workflowStep.validatorRole',
            'workflowInstance.workflow.steps.position',
            'workflowInstance.workflow.steps.systemRole',
            'workflowInstance.workflow.steps.validatorRole',
            'workflowInstance.workflow.steps.subKategoriRkbus',
        ]);

        $items = $this->workflow->getSelectedItems($usulanBelanja);
        $rkbuRecords = \App\Models\Rkbu\Rkbu::query()
            ->with([
                'subKategoriRkbu.kategori.jenis_kategori_rkbu',
                'subKategoriRkbu.sub_kategori_rekening.kategori_rekening',
                'subKegiatan.kegiatan.program',
                'subKegiatan.aktivitas.program',
            ])
            ->whereIn('id', $items->pluck('rkbu_id')->filter()->unique())
            ->get();
        $metadataRkbus = $rkbuRecords->isNotEmpty()
            ? $rkbuRecords
            : collect([$usulanBelanja->rkbu])->filter();
        $metadataLabel = fn ($values) => $values->filter()->unique()->implode(' / ') ?: '-';
        $programLabel = $metadataLabel($metadataRkbus->map(fn ($rkbu) => $rkbu->subKegiatan?->aktivitas?->program ?? $rkbu->subKegiatan?->kegiatan?->program)
            ->map(fn ($program) => $program ? trim(($program->kode_program ?? '') . ' ' . ($program->nama_program ?? ''), ' ') : null));
        $kegiatanLabel = $metadataLabel($metadataRkbus->map(fn ($rkbu) => $rkbu->subKegiatan?->kegiatan)
            ->map(fn ($kegiatan) => $kegiatan ? trim(($kegiatan->kode_kegiatan ?? '') . ' ' . ($kegiatan->nama_kegiatan ?? ''), ' ') : null));
        $subKegiatanLabel = $metadataLabel($metadataRkbus->map(fn ($rkbu) => $rkbu->subKegiatan)
            ->map(fn ($subKegiatan) => $subKegiatan ? trim(($subKegiatan->kode_sub_kegiatan ?? '') . ' ' . ($subKegiatan->nama_sub_kegiatan ?? ''), ' ') : null));
        $aktivitasLabel = $metadataLabel($metadataRkbus->map(fn ($rkbu) => $rkbu->subKegiatan?->aktivitas)
            ->map(fn ($aktivitas) => $aktivitas ? trim(($aktivitas->kode_aktivitas ?? '') . ' ' . ($aktivitas->nama_aktivitas ?? ''), ' ') : null));
        $subtotal = (float) $items->sum('total_anggaran');
        $totalPpn = (float) $items->sum('total_ppn');
        $totalAnggaran = $subtotal + $totalPpn;
        $anggaranRkbu = (float) $items->sum('anggaran_tersedia');
        $sisaAnggaran = (float) $items->sum('sisa_anggaran_rkbu');
        $approvals = ($usulanBelanja->workflowInstance?->approvals ?? collect())
            ->sortBy(fn($approval) => str_pad((string) $approval->sequence, 5, '0', STR_PAD_LEFT)
                . '-' . ($approval->created_at?->format('YmdHis') ?? ''))
            ->values();
        $workflowSteps = $usulanBelanja->workflowInstance?->workflow?->steps ?? collect();
        $workflowSteps = $workflowSteps->filter(
            fn($step) =>
            $step->subKategoriRkbus->isEmpty()
                || $step->subKategoriRkbus->contains('id_sub_kategori_rkbu', $usulanBelanja->sub_kategori_rkbu_id)
        )->values();
        $signatures = $workflowSteps->flatMap(function ($step) use ($approvals, $usulanBelanja) {
            $stepApprovals = $approvals->where('workflow_step_id', $step->id);
            if ($stepApprovals->isEmpty()) {
                $stepApprovals = collect($this->workflow->resolveApprovers($step, $usulanBelanja))
                    ->map(fn($approver) => (object) [
                        'id' => $step->id . '-' . $approver->user_id,
                        'validator_user_id' => $approver->user_id,
                        'validatorUser' => \App\Models\MasterBackend\UserProfil\User::find($approver->user_id),
                        'position' => $step->position,
                        'workflowStep' => $step,
                        'status' => 'pending',
                        'approved_at' => null,
                        'closed_at' => null,
                    ]);
            }

            if ($stepApprovals->isEmpty()) {
                $stepApprovals = collect([(object) [
                    'id' => $step->id,
                    'validator_user_id' => null,
                    'validatorUser' => null,
                    'position' => $step->position,
                    'workflowStep' => $step,
                    'status' => 'pending',
                    'approved_at' => null,
                    'closed_at' => null,
                ]]);
            }

            return $stepApprovals->map(fn($approval) => collect([$approval]));
        })->map(function ($userApprovals) {
            $userApprovals = $userApprovals->values();
            $lastApproval = $userApprovals->last();
            $titles = $userApprovals->map(
                fn($approval) =>
                $approval->position?->nama_jabatan
                    ?? $approval->workflowStep?->position?->nama_jabatan
                    ?? $approval->workflowStep?->systemRole?->nama_role
                    ?? $approval->workflowStep?->validatorRole?->nama_validator
            )->filter()->unique()->values();
            $allApproved = $userApprovals->every(fn($approval) => $approval->status === 'approved');
            $hasPending = $userApprovals->contains(fn($approval) => $approval->status === 'pending');
            $status = $allApproved ? 'approved' : ($hasPending ? 'pending' : $lastApproval->status);
            $approvalDate = $userApprovals
                ->map(fn($approval) => $approval->approved_at ?? $approval->closed_at)
                ->filter()
                ->sort()
                ->last();

            return [
                'jabatan' => $titles->implode(' / ') ?: 'Validator',
                'nama' => $lastApproval->validatorUser?->nama ?? 'Belum ditentukan',
                'nip' => $lastApproval->validatorUser?->nip,
                'status' => $status,
                'tanggal' => $approvalDate,
            ];
        });
        $judulHeader = JudulHeader::query()->first();

        $logoData = function (?string $path) {
            if (! $path) {
                return null;
            }

            $fullPath = public_path('storage/uploads/' . basename($path));
            if (! is_file($fullPath)) {
                return null;
            }

            $mime = mime_content_type($fullPath) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
        };

        $fileNumber = preg_replace('/[^A-Za-z0-9._-]+/', '-', (string) $usulanBelanja->no_usulan_belanja);

        return Pdf::loadView('usulan_belanja.print', [
            'usulan' => $usulanBelanja,
            'rkbu' => $metadataRkbus->first() ?? $usulanBelanja->rkbu,
            'programLabel' => $programLabel,
            'kegiatanLabel' => $kegiatanLabel,
            'subKegiatanLabel' => $subKegiatanLabel,
            'aktivitasLabel' => $aktivitasLabel,
            'items' => $items,
            'approvals' => $approvals,
            'signatures' => $signatures,
            'judulHeader' => $judulHeader,
            'logoKiri' => $logoData($judulHeader?->gambar2),
            'logoKanan' => $logoData($judulHeader?->gambar1),
            'subtotal' => $subtotal,
            'totalPpn' => $totalPpn,
            'totalAnggaran' => $totalAnggaran,
            'anggaranRkbu' => $anggaranRkbu,
            'sisaAnggaran' => $sisaAnggaran,
            'printedAt' => now(),
        ])->setPaper('a4', 'portrait')
            ->download('usulan-belanja-' . trim($fileNumber, '-._') . '.pdf');
    }

    public function printSuratPerintah(UsulanBelanja $usulanBelanja)
    {
        abort_unless($usulanBelanja->status_usulan === 'approved', 404);

        $user = auth()->user();
        $isAdministrator = $user->roles()->where('nama_role', 'Administrator')->exists();
        $isValidator = DB::table('approvals')
            ->where('workflow_instance_id', $usulanBelanja->workflow_instance_id)
            ->where('validator_user_id', $user->id)
            ->exists();
        abort_unless($isAdministrator || $usulanBelanja->user_id === $user->id || $isValidator, 403);

        $usulanBelanja->load([
            'pengusul',
            'unit',
            'rkbu.subKategoriRkbu.kategori.jenis_kategori_rkbu',
        ]);
        $items = $this->workflow->getSelectedItems($usulanBelanja);
        abort_if($items->isEmpty(), 404, 'Usulan ini belum memiliki item.');

        $subtotal = (float) $items->sum('total_anggaran');
        $totalPpn = (float) $items->sum('total_ppn');
        $total = $subtotal + $totalPpn;
        $judulHeader = JudulHeader::query()->first();
        $logoData = function (?string $path) {
            if (! $path) return null;
            $fullPath = public_path('storage/uploads/' . basename($path));
            if (! is_file($fullPath)) return null;
            $mime = mime_content_type($fullPath) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
        };

        $fileNumber = preg_replace('/[^A-Za-z0-9._-]+/', '-', (string) $usulanBelanja->no_usulan_belanja);

        return Pdf::loadView('usulan_belanja.print_surat_perintah', [
            'usulan' => $usulanBelanja,
            'items' => $items,
            'judulHeader' => $judulHeader,
            'logoKiri' => $logoData($judulHeader?->gambar2),
            'logoKanan' => $logoData($judulHeader?->gambar1),
            'subtotal' => $subtotal,
            'totalPpn' => $totalPpn,
            'total' => $total,
            'printedAt' => now(),
        ])->setPaper('a4', 'portrait')
            ->download('surat-perintah-belanja-' . trim($fileNumber, '-._') . '.pdf');
    }

    public function create()
    {
        $rkbus = $this->workflow->getEligibleRkbus(auth()->user());
        $sources = $rkbus->unique('sumber_dana_id')->map(fn ($rkbu) => [
            'id' => $rkbu->sumber_dana_id,
            'name' => $rkbu->nama_sumber_dana,
        ])->values();
        $jenisKategoris = $rkbus->groupBy('id_jenis_kategori_rkbu')->map(fn ($options, $id) => [
            'id' => $id,
            'name' => $options->first()->nama_jenis_kategori_rkbu,
            'source_ids' => $options->pluck('sumber_dana_id')->unique()->values(),
        ])->values();

        return view('usulan_belanja.create', compact('rkbus', 'sources', 'jenisKategoris'));
    }

    public function store(StoreUsulanBelanjaRequest $request)
    {
        $usulan = $this->workflow->buatUsulan(
            $request->validated('sumber_dana_id'),
            $request->validated('jenis_kategori_rkbu_id'),
            $request->validated('sub_kategori_rkbu_id'),
            $request->user()
        );

        return redirect()
            ->route('usulan-belanja.show', $usulan->id)
            ->with('success', 'Usulan Belanja berhasil dibuat.');
    }

    public function show(UsulanBelanja $usulanBelanja)
    {
        return $this->showDetail($usulanBelanja, false);
    }

    public function showRekap(UsulanBelanja $usulanBelanja)
    {
        return $this->showDetail($usulanBelanja, true);
    }

    private function showDetail(UsulanBelanja $usulanBelanja, bool $fromRekap)
    {
        $user = auth()->user();

        $isAdmin = $user->roles()
            ->where('nama_role', 'Administrator')
            ->exists();

        $isValidator = DB::table('approvals')
            ->where(
                'workflow_instance_id',
                $usulanBelanja->workflow_instance_id
            )
            ->where('validator_user_id', $user->id)
            ->exists();

        abort_unless(
            $fromRekap || $isAdmin ||
                $usulanBelanja->user_id === $user->id ||
                $isValidator,
            403
        );

        $canPrint = $isAdmin || $usulanBelanja->user_id === $user->id || $isValidator;
        $canCreateSpj = app(SpjUsulanBelanjaController::class)->canCreateFor($usulanBelanja);

        $usulanBelanja->load([
            'pengusul',
            'rkbu',
            'tahunAnggaran',
            'validator',
        ]);

        $canManageItems = $usulanBelanja->user_id === $user->id
            && $usulanBelanja->status_usulan === 'draft'
            && ! $usulanBelanja->workflow_instance_id;
        $canEditItemValues = $usulanBelanja->user_id === $user->id
            && ($canManageItems || $usulanBelanja->status_usulan === 'revision');
        $availableItems = $canManageItems
            ? $this->workflow->getAvailableItems($usulanBelanja)
            : collect();
        $selectedItems = $this->workflow->getSelectedItems($usulanBelanja);

        $approvals = DB::table('approvals')
            ->leftJoin(
                'workflow_steps',
                'approvals.workflow_step_id',
                '=',
                'workflow_steps.id'
            )
            ->leftJoin(
                'users',
                'approvals.validator_user_id',
                '=',
                'users.id'
            )
            ->leftJoin('positions as validator_positions', 'users.position_id', '=', 'validator_positions.id')
            ->leftJoin('positions as approval_positions', 'approvals.position_id', '=', 'approval_positions.id')
            ->where(
                'approvals.workflow_instance_id',
                $usulanBelanja->workflow_instance_id
            )
            ->orderBy('workflow_steps.step_order')
            ->orderBy('approvals.created_at')
            ->select([
                'workflow_steps.step_order',
                'workflow_steps.validator_type',
                'approvals.status',
                'approvals.catatan',
                'approvals.received_at',
                'approvals.approved_at',
                'approvals.closed_at',
                'users.nama as nama_validator',
                DB::raw('COALESCE(validator_positions.nama_jabatan, approval_positions.nama_jabatan) as jabatan_validator'),
            ])
            ->get();

        $approvalSaya = DB::table('approvals')
            ->where(
                'workflow_instance_id',
                $usulanBelanja->workflow_instance_id
            )
            ->where('validator_user_id', $user->id)
            ->where('status', 'pending')
            ->where('is_current', true)
            ->exists();

        return view('usulan_belanja.show', compact(
            'usulanBelanja',
            'approvals',
            'approvalSaya',
            'isAdmin',
            'canPrint',
            'canCreateSpj',
            'fromRekap',
            'canManageItems',
            'canEditItemValues',
            'availableItems',
            'selectedItems'
        ));
    }

    public function addItem(Request $request, UsulanBelanja $usulanBelanja)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'string', 'distinct', 'regex:/^(barjas|persediaan|modal|modal_alkes|pegawai):[0-9a-fA-F-]{36}$/'],
        ]);

        $added = $this->workflow->addItems(
            $usulanBelanja,
            $validated['items'],
            $request->user()
        );

        return redirect()
            ->route('usulan-belanja.show', $usulanBelanja->id)
            ->with('success', "{$added} item Usulan Belanja berhasil ditambahkan.");
    }

    public function updateItems(Request $request, UsulanBelanja $usulanBelanja)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            'items.*.harga_satuan' => ['required', 'numeric', 'min:0', 'max:9999999999999999.99'],
            'items.*.volume' => ['required', 'numeric', 'min:0', 'max:999999999999999.999'],
            'items.*.ppn' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.spesifikasi' => ['nullable', 'string', 'max:10000'],
        ]);

        $this->workflow->updateItems(
            $usulanBelanja,
            $validated['items'],
            $request->user()
        );

        return redirect()
            ->route('usulan-belanja.show', $usulanBelanja->id)
            ->with('success', 'Item Usulan Belanja berhasil diperbarui.');
    }

    public function deleteItem(
        Request $request,
        UsulanBelanja $usulanBelanja,
        \App\Models\UsulanBelanja\UsulanBelanjaItem $item
    ) {
        $this->workflow->deleteItem(
            $usulanBelanja,
            $item,
            $request->user()
        );

        return redirect()
            ->route('usulan-belanja.show', $usulanBelanja->id)
            ->with('success', 'Item Usulan Belanja berhasil dihapus.');
    }

    public function delete(Request $request, UsulanBelanja $usulanBelanja)
    {
        $isAdmin = $request->user()->roles()
            ->where('nama_role', 'Administrator')
            ->exists();

        abort_unless(
            $isAdmin || $usulanBelanja->user_id === $request->user()->id,
            403
        );

        $this->workflow->deleteEmptyUsulan($usulanBelanja);

        return redirect()
            ->route('usulan-belanja.index')
            ->with('success', 'Usulan Belanja kosong berhasil dihapus.');
    }

    public function ajukan(UsulanBelanja $usulanBelanja)
    {
        try {
            $this->workflow->ajukan(
                $usulanBelanja,
                auth()->user()
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with(
            'success',
            'Usulan berhasil diajukan untuk validasi.'
        );
    }
}
