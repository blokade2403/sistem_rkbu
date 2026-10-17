<?php

namespace App\Imports\SettingRkbu;

use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRekening;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubKategoriRkbuImport implements ToCollection
{
    private int $importedCount = 0;

    public function collection(Collection $collection)
    {
        $headings = $collection->shift();

        if (! $headings) {
            throw ValidationException::withMessages([
                'file' => 'File Excel tidak memiliki baris header.',
            ]);
        }

        $headingIndexes = collect($headings)
            ->mapWithKeys(fn ($heading, $index) => [
                Str::of((string) $heading)->trim()->lower()->replace([' ', '-'], '_')->toString() => $index,
            ]);

        $requiredHeadings = [
            'id_kategori_rkbu',
            'id_sub_kategori_rekening',
            'kode_sub_kategori_rkbu',
            'nama_sub_kategori_rkbu',
        ];
        $missingHeadings = array_diff($requiredHeadings, $headingIndexes->keys()->all());

        if ($missingHeadings) {
            throw ValidationException::withMessages([
                'file' => 'Header file tidak sesuai. Kolom wajib: '.implode(', ', $requiredHeadings).'.',
            ]);
        }

        $rows = [];
        $errors = [];

        foreach ($collection->values() as $index => $row) {
            $values = [];
            foreach ($headingIndexes as $heading => $columnIndex) {
                $values[$heading] = trim((string) data_get($row, $columnIndex, ''));
            }

            if (collect($values)->every(fn ($value) => $value === '')) {
                continue;
            }

            $rowNumber = $index + 2;
            foreach ($requiredHeadings as $heading) {
                if (($values[$heading] ?? '') === '') {
                    $errors[] = "Baris {$rowNumber}: kolom {$heading} wajib diisi.";
                }
            }

            $status = Str::lower($values['status'] ?? '');
            if ($status !== '' && ! in_array($status, ['aktif', 'tidak aktif'], true)) {
                $errors[] = "Baris {$rowNumber}: status harus 'aktif' atau 'tidak aktif'.";
            }

            $values['status'] = $status !== '' ? $status : 'aktif';
            $values['row_number'] = $rowNumber;
            $rows[] = $values;
        }

        if (! $rows) {
            throw ValidationException::withMessages([
                'file' => 'File Excel tidak berisi data untuk diimpor.',
            ]);
        }

        $codes = collect($rows)->pluck('kode_sub_kategori_rkbu');
        foreach ($codes->duplicates() as $duplicateCode) {
            $errors[] = "Kode sub kategori '{$duplicateCode}' tercantum lebih dari sekali dalam file.";
        }

        $existingCodes = SubKategoriRkbu::query()
            ->whereIn('kode_sub_kategori_rkbu', $codes->unique())
            ->pluck('kode_sub_kategori_rkbu');
        foreach ($existingCodes as $existingCode) {
            $errors[] = "Kode sub kategori '{$existingCode}' sudah terdaftar.";
        }

        $categories = KategoriRkbu::query()
            ->whereIn('id_kategori_rkbu', collect($rows)->pluck('id_kategori_rkbu')->unique())
            ->pluck('id_kategori_rkbu');
        $subCategoryAccounts = SubKategoriRekening::query()
            ->whereIn('id_sub_kategori_rekening', collect($rows)->pluck('id_sub_kategori_rekening')->unique())
            ->pluck('id_sub_kategori_rekening');
        foreach ($rows as $row) {
            $rowNumber = $row['row_number'];
            foreach ([
                'id_kategori_rkbu' => $categories,
                'id_sub_kategori_rekening' => $subCategoryAccounts,
            ] as $column => $lookup) {
                if (($row[$column] ?? '') !== '' && ! $lookup->contains($row[$column])) {
                    $errors[] = "Baris {$rowNumber}: {$column} '{$row[$column]}' tidak ditemukan.";
                }
            }
        }

        if ($errors) {
            throw ValidationException::withMessages([
                'file' => implode(' ', $errors),
            ]);
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                SubKategoriRkbu::create([
                    'id_kategori_rkbu' => $row['id_kategori_rkbu'],
                    'id_sub_kategori_rekening' => $row['id_sub_kategori_rekening'],
                    'kode_sub_kategori_rkbu' => $row['kode_sub_kategori_rkbu'],
                    'nama_sub_kategori_rkbu' => $row['nama_sub_kategori_rkbu'],
                    'status' => $row['status'],
                ]);
            }
        });

        $this->importedCount = count($rows);
    }

    public function importedCount(): int
    {
        return $this->importedCount;
    }
}
