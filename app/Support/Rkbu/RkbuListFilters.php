<?php

namespace App\Support\Rkbu;

use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RkbuListFilters
{
    public const APPROVAL_STATUSES = [
        'draft' => 'Draft',
        'proses' => 'Menunggu Approval',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'revision' => 'Perlu Revisi',
    ];

    public static function validate(Request $request): array
    {
        return $request->validate([
            'sub_kategori_rkbu_id' => [
                'nullable',
                'uuid',
                Rule::exists('sub_kategori_rkbus', 'id_sub_kategori_rkbu'),
            ],
            'sumber_dana_id' => [
                'nullable',
                'uuid',
                Rule::exists('sumber_danas', 'id_sumber_dana'),
            ],
            'approval_status' => [
                'nullable',
                Rule::in(array_keys(self::APPROVAL_STATUSES)),
            ],
        ]);
    }

    public static function apply(Builder $query, array $filters): Builder
    {
        return $query->whereHas('rkbu', function (Builder $rkbuQuery) use ($filters) {
            if (! empty($filters['sub_kategori_rkbu_id'])) {
                $rkbuQuery->where('sub_kategori_rkbu_id', $filters['sub_kategori_rkbu_id']);
            }

            if (! empty($filters['sumber_dana_id'])) {
                $rkbuQuery->where('sumber_dana_id', $filters['sumber_dana_id']);
            }

            if (! empty($filters['approval_status'])) {
                $rkbuQuery->where('status', $filters['approval_status']);
            }
        });
    }

    public static function availableSubcategories(Builder $subcategories, string $detailRelation)
    {
        $rkbUs = Rkbu::query()
            ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
            ->whereHas($detailRelation);

        if (! auth()->user()->hasRole('Administrator')) {
            $rkbUs->where('created_by', auth()->id());
        }

        return $subcategories
            ->whereIn(
                'id_sub_kategori_rkbu',
                $rkbUs->select('sub_kategori_rkbu_id')->distinct()
            )
            ->orderBy('kode_sub_kategori_rkbu')
            ->orderBy('nama_sub_kategori_rkbu')
            ->get();
    }
}
