<?php

namespace App\Support\Rkbu;

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
            if (! empty($filters['sumber_dana_id'])) {
                $rkbuQuery->where('sumber_dana_id', $filters['sumber_dana_id']);
            }

            if (! empty($filters['approval_status'])) {
                $rkbuQuery->where('status', $filters['approval_status']);
            }
        });
    }
}
