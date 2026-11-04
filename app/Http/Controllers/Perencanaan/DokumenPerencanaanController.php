<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\DokumenPerencanaan;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\Fase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;

class DokumenPerencanaanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAnggarans = TahunAnggaran::orderByDesc('nama_tahun_anggaran')->get();
        $fases = Fase::orderBy('urutan')->orderBy('nama_fase')->get();
        $dokumens = DokumenPerencanaan::with(['tahunAnggaran', 'fase'])
            ->when($request->filled('tahun_anggaran_id'), fn ($q) => $q->where('tahun_anggaran_id', $request->tahun_anggaran_id))
            ->when($request->filled('fase_id'), fn ($q) => $q->where('fase_id', $request->fase_id))
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->latest()->paginate(12)->withQueryString();

        return view('perencanaan.dokumen.index', compact('dokumens', 'tahunAnggarans', 'fases'));
    }

    public function store(Request $request)
    {
        $this->ensureUploadSucceeded($request);
        $data = $this->validated($request);
        $data['file_path'] = $this->storeFile($request->file('file'));
        $data['file_name'] = $request->file('file')->getClientOriginalName();
        $data['created_by'] = $request->user()->id;
        DokumenPerencanaan::create($data);

        return back()->with('success', 'Dokumen perencanaan berhasil diunggah.');
    }

    public function update(Request $request, DokumenPerencanaan $dokumen)
    {
        $this->ensureUploadSucceeded($request);
        $data = $this->validated($request, true);
        if ($request->hasFile('file')) {
            $newPath = $this->storeFile($request->file('file'));
            $data['file_path'] = $newPath;
            $data['file_name'] = $request->file('file')->getClientOriginalName();
            Storage::disk('public')->delete($dokumen->file_path);
        }
        $dokumen->update($data);

        return back()->with('success', 'Dokumen perencanaan berhasil diperbarui.');
    }

    public function destroy(DokumenPerencanaan $dokumen)
    {
        Storage::disk('public')->delete($dokumen->file_path);
        $dokumen->delete();
        return back()->with('success', 'Dokumen perencanaan berhasil dihapus.');
    }

    public function download(DokumenPerencanaan $dokumen)
    {
        abort_unless(Storage::disk('public')->exists($dokumen->file_path), 404);
        return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
    }

    private function validated(Request $request, bool $updating = false): array
    {
        $data = $request->validate([
            'tahun_anggaran_id' => ['required', 'uuid', 'exists:tahun_anggarans,id'],
            'fase_id' => ['required', 'uuid', 'exists:fases,id'],
            'judul' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'file' => [$updating ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', 'max:10240'],
        ]);
        $tahun = TahunAnggaran::findOrFail($data['tahun_anggaran_id']);
        if ($tahun->fase_id !== $data['fase_id']) {
            throw ValidationException::withMessages(['fase_id' => 'Tahapan harus sesuai dengan tahun anggaran yang dipilih.']);
        }
        unset($data['file']);
        return $data;
    }

    private function ensureUploadSucceeded(Request $request): void
    {
        $file = $request->allFiles()['file'] ?? null;
        if (!$file instanceof UploadedFile || $file->isValid()) {
            return;
        }

        $message = match ($file->getError()) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran berkas melewati batas upload server. Coba unggah berkas yang lebih kecil.',
            UPLOAD_ERR_PARTIAL => 'Berkas hanya terkirim sebagian. Periksa koneksi lalu unggah kembali.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder sementara upload PHP tidak tersedia. Hubungi administrator aplikasi.',
            UPLOAD_ERR_CANT_WRITE => 'Server tidak dapat menulis berkas sementara. Periksa ruang disk dan izin folder upload.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP di server.',
            default => 'Berkas gagal diterima oleh server. Coba unggah kembali.',
        };

        throw ValidationException::withMessages(['file' => $message]);
    }

    private function storeFile(UploadedFile $file): string
    {
        $path = $file->store('dokumen-perencanaan', 'public');
        if (!$path) {
            throw ValidationException::withMessages(['file' => 'Server tidak dapat menyimpan berkas. Periksa izin tulis pada storage/app/public.']);
        }

        return $path;
    }
}
