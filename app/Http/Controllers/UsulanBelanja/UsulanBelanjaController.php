<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsulanBelanja\StoreUsulanBelanjaRequest;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Services\Workflow\UsulanBelanjaWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UsulanBelanjaController extends Controller
{
    public function __construct(
        private UsulanBelanjaWorkflowService $workflow
    ) {}

    public function index(Request $request)
    {
        $query = UsulanBelanja::with([
            'pengusul',
            'rkbu',
            'tahunAnggaran',
        ]);

        if (!$request->user()->roles()
            ->where('nama_role', 'Administrator')
            ->exists()) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->filled('status')) {
            $query->where(
                'status_usulan',
                $request->string('status')->toString()
            );
        }

        if ($request->filled('tahun_anggaran_id')) {
            $query->where(
                'tahun_anggaran_id',
                $request->string('tahun_anggaran_id')->toString()
            );
        }

        $usulans = $query->latest()->paginate(15)
            ->withQueryString();

        $tahunAnggarans = DB::table('tahun_anggarans')
            ->orderByDesc('nama_tahun_anggaran')
            ->get();

        return view('usulan_belanja.index', compact(
            'usulans',
            'tahunAnggarans'
        ));
    }

    public function create()
    {
        $rkbus = $this->workflow->getEligibleRkbus(auth()->user());

        return view('usulan_belanja.create', compact('rkbus'));
    }

    public function store(StoreUsulanBelanjaRequest $request)
    {
        $usulan = $this->workflow->buatUsulan(
            $request->validated('rkbu_id'),
            $request->user()
        );

        return redirect()
            ->route('usulan-belanja.show', $usulan->id)
            ->with('success', 'Usulan Belanja berhasil dibuat.');
    }

    public function show(UsulanBelanja $usulanBelanja)
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
            $isAdmin ||
                $usulanBelanja->user_id === $user->id ||
                $isValidator,
            403
        );

        $usulanBelanja->load([
            'pengusul',
            'rkbu',
            'tahunAnggaran',
            'validator',
        ]);

        $canManageItems = $usulanBelanja->user_id === $user->id
            && $usulanBelanja->status_usulan === 'draft'
            && ! $usulanBelanja->workflow_instance_id;
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
            ->where(
                'approvals.workflow_instance_id',
                $usulanBelanja->workflow_instance_id
            )
            ->orderBy('workflow_steps.step_order')
            ->select([
                'workflow_steps.step_order',
                'workflow_steps.validator_type',
                'approvals.status',
                'approvals.catatan',
                'approvals.received_at',
                'approvals.approved_at',
                'users.nama as nama_validator',
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
            'canManageItems',
            'availableItems',
            'selectedItems'
        ));
    }

    public function addItem(Request $request, UsulanBelanja $usulanBelanja)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'string', 'distinct', 'regex:/^(barjas|persediaan|modal|pegawai):[0-9a-fA-F-]{36}$/'],
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

    public function ajukan(UsulanBelanja $usulanBelanja)
    {
        $this->workflow->ajukan(
            $usulanBelanja,
            auth()->user()
        );

        return back()->with(
            'success',
            'Usulan berhasil diajukan untuk validasi.'
        );
    }
}
