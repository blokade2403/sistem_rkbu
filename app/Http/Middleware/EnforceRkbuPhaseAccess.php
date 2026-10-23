<?php

namespace App\Http\Middleware;

use App\Models\Rkbu\Rkbu;
use App\Services\Workflow\PaguAnggaranService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnforceRkbuPhaseAccess
{
    private const DETAIL_TABLES = [
        'rkbu_detail_barjass',
        'rkbu_detail_modals',
        'rkbu_detail_modal_alkes',
        'rkbu_detail_persediaans',
        'rkbu_detail_pegawais',
    ];

    public function handle(Request $request, Closure $next)
    {
        $paguService = app(PaguAnggaranService::class);
        $action = $request->route()?->getActionMethod();
        if (! in_array($action, ['create', 'store', 'edit', 'update', 'destroy'], true)) {
            return $next($request);
        }

        if (in_array($action, ['create', 'store'], true)) {
            $paguService->assertCreateAllowedByCurrentUser(session('tahun_anggaran_id'));

            if ($action === 'store' && $paguService->phaseOrder(session('tahun_anggaran_id')) >= 2) {
                $existingIds = Rkbu::query()
                    ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
                    ->where('created_by', auth()->id())
                    ->pluck('id');

                return DB::transaction(function () use ($next, $request, $existingIds, $paguService) {
                    $response = $next($request);
                    Rkbu::query()
                        ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
                        ->where('created_by', auth()->id())
                        ->whereNotIn('id', $existingIds)
                        ->get()
                        ->each(fn (Rkbu $rkbu) => $paguService->assertRkbuWithinPagu($rkbu));

                    return $response;
                });
            }

            return $next($request);
        }

        $detailId = $request->route('id') ?? $request->route('barja') ?? $request->route('persediaan')
            ?? $request->route('modal_kantor') ?? $request->route('modal_alkes');
        if (! $detailId) {
            return $next($request);
        }

        $rkbuId = null;
        foreach (self::DETAIL_TABLES as $table) {
            $rkbuId = DB::table($table)->where('id', $detailId)->value('rkbu_id');
            if ($rkbuId) {
                break;
            }
        }

        if ($rkbuId && ($rkbu = Rkbu::find($rkbuId))) {
            $paguService->assertRkbuEditableByCurrentUser($rkbu);
            if ($action === 'update' && $paguService->phaseOrder($rkbu->tahun_anggaran_id) >= 2) {
                return DB::transaction(function () use ($next, $request, $rkbu, $paguService) {
                    $response = $next($request);
                    $paguService->assertRkbuWithinPagu($rkbu->fresh());

                    return $response;
                });
            }
        }

        return $next($request);
    }
}
