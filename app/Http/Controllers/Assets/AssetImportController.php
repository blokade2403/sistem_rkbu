<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset\Asset;
use App\Models\Asset\AssetHistory;
use App\Models\MasterBackend\SettingUser\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class AssetImportController extends Controller
{
    private const HEADERS = [
        'unit', 'no_register', 'nama_asset', 'kode_asset', 'satuan', 'spek', 'harga_asset',
        'tahun_perolehan', 'tgl_asset', 'kondisi_asset', 'pengguna_asset', 'nip_pengguna',
        'jabatan_pengguna', 'status_asset', 'id_barang', 'jumlah_asset', 'total_anggaran_asset',
        'merk', 'serial_number', 'type', 'tgl_bpkb', 'no_rangka', 'no_mesin', 'no_polisi',
        'kapitalisasi', 'lokasi_asset', 'penanggung_jawab', 'hibah', 'sumber_anggaran',
    ];

    private const OPTIONAL_FIELDS = [
        'no_register', 'kode_asset', 'satuan', 'spek', 'harga_asset', 'tahun_perolehan', 'tgl_asset',
        'pengguna_asset', 'nip_pengguna', 'jabatan_pengguna', 'status_asset', 'id_barang', 'jumlah_asset',
        'total_anggaran_asset', 'merk', 'serial_number', 'type', 'tgl_bpkb', 'no_rangka', 'no_mesin',
        'no_polisi', 'kapitalisasi', 'lokasi_asset', 'penanggung_jawab', 'hibah', 'sumber_anggaran',
    ];

    public function template()
    {
        $headers = self::HEADERS;

        return response()->streamDownload(function () use ($headers): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);
            fclose($output);
        }, 'template-upload-aset.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request)
    {
        $this->authorizeAssetAccess();
        $request->validate([
            'file' => ['required', 'file', 'extensions:xlsx,xls,csv', 'max:10240'],
        ]);

        try {
            $rows = IOFactory::load($request->file('file')->getPathname())
                ->getActiveSheet()
                ->toArray(null, true, true, false);
        } catch (Throwable $exception) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca. Gunakan template CSV/XLSX yang disediakan.']);
        }

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File belum berisi baris data aset.']);
        }

        $headers = array_map(fn ($value) => Str::lower(trim(str_replace("\xEF\xBB\xBF", '', (string) $value))), array_shift($rows));
        $missingHeaders = array_diff(self::HEADERS, $headers);
        if ($missingHeaders !== []) {
            return back()->withErrors(['file' => 'Header template tidak lengkap. Kolom yang belum ada: ' . implode(', ', $missingHeaders)]);
        }

        if (count($rows) > 5000) {
            return back()->withErrors(['file' => 'Maksimal 5.000 baris aset dalam satu kali upload.']);
        }

        $headerPositions = array_flip($headers);
        $prepared = [];
        $errors = [];
        $seenRegisters = [];
        $user = $request->user();
        $isAdministrator = $user->hasRole('Administrator') || $user->hasRole('Super Admin');

        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $row = array_values($row);
            $data = [];
            foreach (self::HEADERS as $header) {
                $data[$header] = $row[$headerPositions[$header]] ?? null;
            }
            if (collect($data)->every(fn ($value) => $value === null || trim((string) $value) === '')) {
                continue;
            }

            $unit = null;
            if ($isAdministrator) {
                $unitValue = trim((string) ($data['unit'] ?? ''));
                $unit = Unit::query()->whereKey($unitValue)->orWhere('nama_unit', $unitValue)->first();
                if (! $unit) {
                    $errors[] = "Baris {$line}: unit '{$unitValue}' tidak ditemukan.";
                }
            } else {
                abort_unless($user->hasRole('Pengurus Barang') && filled($user->unit_id), 403, 'Hanya Pengurus Barang dan Administrator yang dapat mengupload aset.');
                $unit = Unit::query()->find($user->unit_id);
                if (! $unit) {
                    $errors[] = "Baris {$line}: unit akun Anda tidak ditemukan.";
                }
            }

            $data['harga_asset'] = $this->normalizeNumber($data['harga_asset']);
            $data['jumlah_asset'] = $this->normalizeNumber($data['jumlah_asset']) ?? 1;
            $data['total_anggaran_asset'] = $this->normalizeNumber($data['total_anggaran_asset']);
            if ($data['total_anggaran_asset'] === null && $data['harga_asset'] !== null) {
                $data['total_anggaran_asset'] = $data['harga_asset'] * $data['jumlah_asset'];
            }
            $data['tahun_perolehan'] = $this->normalizeNumber($data['tahun_perolehan']);
            foreach (['tgl_asset', 'tgl_bpkb'] as $dateField) {
                $data[$dateField] = $this->normalizeDate($data[$dateField]);
            }
            foreach (self::OPTIONAL_FIELDS as $field) {
                $data[$field] = is_string($data[$field]) ? trim($data[$field]) : $data[$field];
                if ($data[$field] === '') $data[$field] = null;
            }
            $data['kondisi_asset'] = trim((string) $data['kondisi_asset']);

            $validator = Validator::make($data, [
                'nama_asset' => ['required', 'string', 'max:255'],
                'no_register' => ['nullable', 'string', 'max:255', 'unique:assets,no_register'],
                'tahun_perolehan' => ['nullable', 'integer', 'between:1900,' . (now()->year + 1)],
                'harga_asset' => ['nullable', 'numeric', 'min:0'],
                'jumlah_asset' => ['required', 'numeric', 'gt:0'],
                'total_anggaran_asset' => ['nullable', 'numeric', 'min:0'],
                'kondisi_asset' => ['required', 'in:Baik,Rusak Ringan,Rusak Berat,Belum Dinilai'],
                'tgl_asset' => ['nullable', 'date'],
                'tgl_bpkb' => ['nullable', 'date'],
            ]);
            if ($validator->fails()) {
                $errors[] = "Baris {$line}: " . implode('; ', $validator->errors()->all());
                continue;
            }

            if ($data['no_register']) {
                if (isset($seenRegisters[$data['no_register']])) {
                    $errors[] = "Baris {$line}: nomor register '{$data['no_register']}' duplikat di dalam file.";
                    continue;
                }
                $seenRegisters[$data['no_register']] = true;
            }
            if (! $unit) continue;

            $prepared[] = [
                'line' => $line,
                'unit_id' => $unit->id,
                'data' => $data,
            ];
        }

        if ($errors !== []) {
            return back()->withErrors(['file' => implode(' ', array_slice($errors, 0, 30))]);
        }
        if ($prepared === []) {
            return back()->withErrors(['file' => 'Tidak ada baris aset valid untuk diupload.']);
        }

        DB::transaction(function () use ($prepared, $user): void {
            foreach ($prepared as $item) {
                $data = $item['data'];
                $year = $data['tahun_perolehan'] ?: now()->year;
                $register = $data['no_register'] ?: 'AST-' . $year . '-' . Str::upper(Str::random(8));
                $asset = Asset::query()->create(array_merge($data, [
                    'id_asset' => (string) Str::uuid(),
                    'unit_id' => $item['unit_id'],
                    'registered_by' => $user->id,
                    'no_register' => $register,
                    'status_asset' => $data['status_asset'] ?: 'Aktif',
                    'status_registrasi' => 'terdaftar',
                ]));
                AssetHistory::query()->create([
                    'asset_id' => $asset->id_asset,
                    'asset_unit_id' => $asset->unit_id,
                    'asset_name' => $asset->nama_asset,
                    'asset_register' => $asset->no_register,
                    'action' => 'created',
                    'description' => 'Aset diupload melalui file import (baris ' . $item['line'] . ').',
                    'before_data' => null,
                    'after_data' => $asset->getAttributes(),
                    'user_id' => $user->id,
                ]);
            }
        });

        return redirect()->route('asset-module.index')->with('success', count($prepared) . ' data aset berhasil diupload. QR code akan dibuat saat daftar aset dibuka.');
    }

    private function normalizeNumber(mixed $value): int|float|null
    {
        if ($value === null || trim((string) $value) === '') return null;
        if (is_numeric($value)) return $value + 0;
        $value = preg_replace('/\s+/', '', (string) $value);
        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = strrpos($value, ',') > strrpos($value, '.')
                ? str_replace(',', '.', str_replace('.', '', $value))
                : str_replace(',', '', $value);
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? $value + 0 : null;
    }

    private function normalizeDate(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') return null;
        try {
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }
            $timestamp = strtotime((string) $value);
            return $timestamp === false ? (string) $value : date('Y-m-d', $timestamp);
        } catch (Throwable $exception) {
            return (string) $value;
        }
    }

    private function authorizeAssetAccess(): void
    {
        $user = auth()->user();
        abort_unless(
            $user && ($user->hasRole('Administrator') || $user->hasRole('Super Admin') || ($user->hasRole('Pengurus Barang') && filled($user->unit_id))),
            403,
            'Hanya Pengurus Barang dan Administrator yang dapat mengupload aset.'
        );
    }
}
