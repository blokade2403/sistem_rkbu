<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsulanBelanja\ValidasiUsulanBelanjaRequest;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Services\Workflow\UsulanBelanjaWorkflowService;
use Illuminate\Support\Facades\DB;

class ValidasiUsulanBelanjaController extends Controller
{
    public function __construct(
        private UsulanBelanjaWorkflowService $workflow
    ) {}

    public function index()
    {
        $userId = auth()->id();

        $ids = DB::table('approvals')
            ->where('validator_user_id', $userId)
            ->where('status', 'pending')
            ->where('is_current', true)
            ->pluck('workflow_instance_id')
            ->unique();

        $usulans = UsulanBelanja::with([
            'pengusul',
            'rkbu',
            'tahunAnggaran',
        ])
            ->whereIn('workflow_instance_id', $ids)
            ->where('status_usulan', 'proses')
            ->latest()
            ->paginate(15);

        return view('usulan_belanja.validasi', compact('usulans'));
    }

    public function proses(
        ValidasiUsulanBelanjaRequest $request,
        UsulanBelanja $usulanBelanja
    ) {
        $this->workflow->prosesKeputusan(
            $usulanBelanja->id,
            $request->validated('aksi'),
            $request->validated('catatan'),
            $request->user()
        );

        return redirect()
            ->route('usulan-belanja.show', $usulanBelanja->id)
            ->with('success', 'Keputusan validasi berhasil diproses.');
    }
}
