<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingInput\JudulHeader;

class JudulHeaderController extends Controller
{

    public function index()
    {
        $judul_headers  = JudulHeader::all();
        return view('master_backend.setting_input.judul_header.index', compact('judul_headers'), [
            'title'         => 'Halaman Judul Header',
            'title2'        => 'Judul Header',
            'routePrefix'   => 'judul_headers'
        ]);
    }

    public function create()
    {
        $judul_headers  = JudulHeader::all();
        return view('master_backend.setting_input.judul_header.create',  [
            'title'         => 'Halaman Judul Header',
            'title2'        => 'Judul Header',
            'routePrefix'   => 'judul_headers',
            'judul_headers' => $judul_headers
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_rs'       => 'required',
                'alamat_rs'     => 'required',
                'tlp_rs'        => 'required',
                'email_rs'      => 'required|email',
                'wilayah'       => 'required',
                'kode_pos'      => 'nullable',
                'header1'       => 'nullable',
                'header2'       => 'nullable',
                'header3'       => 'nullable',
                'header4'       => 'nullable',
                'header5'       => 'nullable',
                'header6'       => 'nullable',
                'gambar1'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'gambar2'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'gambar3'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'gambar4'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'header7'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'nama_rs.required'   => 'Nama RS wajib diisi.',
                'alamat_rs.required' => 'Alamat RS wajib diisi.',
                'tlp_rs.required'    => 'Telepon RS wajib diisi.',
                'email_rs.required'  => 'Email RS wajib diisi.',
                'email_rs.email'     => 'Format email tidak valid.',
                'wilayah.required'   => 'Wilayah wajib diisi.',
                'wilayah.required'   => 'Wilayah wajib diisi.',
                'wilayah.required'   => 'Wilayah wajib diisi.',
                'gambar1.mimes'      => 'Gambar 1 harus berformat jpg, jpeg, atau png.',
                'gambar1.max'        => 'Ukuran gambar 1 tidak boleh lebih dari 2MB.',
                'gambar2.mimes'      => 'Gambar 2 harus berformat jpg, jpeg, atau png.',
                'gambar3.mimes'      => 'Gambar 3 harus berformat jpg, jpeg, atau png.',
                'gambar4.mimes'      => 'Gambar 4 harus berformat jpg, jpeg, atau png.',
                'header7.mimes'      => 'Gambar 3 harus berformat jpg, jpeg, atau png.',
                'header7.max'        => 'Ukuran gambar 3 tidak boleh lebih dari 2MB.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Periksa Kembali Inputan Anda !!!');
        }

        // Proses upload file gambar
        $namaFile1 = $request->hasFile('gambar1') ? $request->file('gambar1')->store('public/judul_header') : null;
        $namaFile2 = $request->hasFile('gambar2') ? $request->file('gambar2')->store('public/judul_header') : null;
        $namaFile3 = $request->hasFile('header7') ? $request->file('header7')->store('public/judul_header') : null;
        $namaFile4 = $request->hasFile('gambar3') ? $request->file('gambar3')->store('public/judul_header') : null;
        $namaFile5 = $request->hasFile('gambar4') ? $request->file('gambar4')->store('public/judul_header') : null;

        $judul_headers = new JudulHeader([
            'nama_rs'   => $request->nama_rs,
            'alamat_rs' => $request->alamat_rs,
            'tlp_rs'    => $request->tlp_rs,
            'email_rs'  => $request->email_rs,
            'wilayah'   => $request->wilayah,
            'kode_pos'  => $request->kode_pos,
            'header1'   => $request->header1,
            'header2'   => $request->header2,
            'header3'   => $request->header3, // Kolom ini sebelumnya tidak dimasukkan
            'header4'   => $request->header4, // Kolom ini sebelumnya tidak dimasukkan
            'header5'   => $request->header5, // Kolom ini sebelumnya tidak dimasukkan
            'header6'   => $request->header6, // Kolom ini sebelumnya tidak dimasukkan
            'gambar1'   => $namaFile1,
            'gambar2'   => $namaFile2,
            'gambar3'   => $namaFile4,
            'gambar4'   => $namaFile5,
            'header7'   => $namaFile3,
        ]);

        $judul_headers->save();

        return redirect()->route('judul_headers.index')->with('success', 'Data Berhasil Ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(JudulHeader $judulHeader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JudulHeader $judulHeader)
    {
        return view('master_backend.setting_input.judul_header.edit', compact('judulHeader'), [
            'title'         => 'Halaman Judul Header',
            'title2'        => 'Judul Header',
            'routePrefix'   => 'judul_headers'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JudulHeader $judulHeader)
    {

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'nama_rs'   => 'required',
                'alamat_rs' => 'required',
                'tlp_rs'    => 'required',
                'email_rs'  => 'required|email',
                'wilayah'   => 'required',
                'kode_pos'  => 'nullable',
                'header1'   => 'nullable',
                'header2'   => 'nullable',
                'header3'   => 'nullable',
                'header4'   => 'nullable',
                'header5'   => 'nullable',
                'header6'   => 'nullable',
                'gambar1'   => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'gambar2'   => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'gambar3'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'gambar4'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'header7'   => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'nama_rs.required'   => 'Nama RS wajib diisi.',
                'alamat_rs.required' => 'Alamat RS wajib diisi.',
                'tlp_rs.required'    => 'Telepon RS wajib diisi.',
                'email_rs.required'  => 'Email RS wajib diisi.',
                'email_rs.email'     => 'Format email tidak valid.',
                'wilayah.required'   => 'Wilayah wajib diisi.',
                'gambar1.mimes'      => 'Gambar 1 harus berformat jpg, jpeg, atau png.',
                'gambar1.max'        => 'Ukuran gambar 1 tidak boleh lebih dari 2MB.',
                'gambar2.mimes'      => 'Gambar 2 harus berformat jpg, jpeg, atau png.',
                'gambar2.max'        => 'Ukuran gambar 2 tidak boleh lebih dari 2MB.',
                'gambar3.max'        => 'Ukuran gambar 2 tidak boleh lebih dari 2MB.',
                'gambar3.mimes'      => 'Gambar 3 harus berformat jpg, jpeg, atau png.',
                'gambar4.max'        => 'Ukuran gambar 2 tidak boleh lebih dari 2MB.',
                'gambar4.mimes'      => 'Gambar 4 harus berformat jpg, jpeg, atau png.',
                'header7.mimes'      => 'Gambar 3 harus berformat jpg, jpeg, atau png.',
                'header7.max'        => 'Ukuran gambar 3 tidak boleh lebih dari 2MB.',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Periksa Kembali Inputan Anda !!!');
        }

        // Upload gambar 1 (hapus lama jika ada upload baru)
        if ($request->hasFile('gambar1')) {
            if ($judulHeader->gambar1 && Storage::exists($judulHeader->gambar1)) {
                Storage::delete($judulHeader->gambar1);
            }
            $namaFile1 = $request->file('gambar1')->store('public/judul_header');
        } else {
            $namaFile1 = $judulHeader->gambar1;
        }

        // Upload gambar 2 (hapus lama jika ada upload baru)
        if ($request->hasFile('gambar2')) {
            if ($judulHeader->gambar2 && Storage::exists($judulHeader->gambar2)) {
                Storage::delete($judulHeader->gambar2);
            }
            $namaFile2 = $request->file('gambar2')->store('public/judul_header');
        } else {
            $namaFile2 = $judulHeader->gambar2;
        }

        // Upload header7 (hapus lama jika ada upload baru)
        if ($request->hasFile('header7')) {
            if ($judulHeader->header7 && Storage::exists($judulHeader->header7)) {
                Storage::delete($judulHeader->header7);
            }
            $namaFile3 = $request->file('header7')->store('public/judul_header');
        } else {
            $namaFile3 = $judulHeader->header7;
        }

        // Upload gambar 3 (hapus lama jika ada upload baru)
        if ($request->hasFile('gambar3')) {
            if ($judulHeader->gambar3 && Storage::exists($judulHeader->gambar3)) {
                Storage::delete($judulHeader->gambar3);
            }
            $namaFile4 = $request->file('gambar3')->store('public/judul_header');
        } else {
            $namaFile4 = $judulHeader->gambar3;
        }

        // Upload gambar 3 (hapus lama jika ada upload baru)
        if ($request->hasFile('gambar4')) {
            if ($judulHeader->gambar4 && Storage::exists($judulHeader->gambar4)) {
                Storage::delete($judulHeader->gambar4);
            }
            $namaFile5 = $request->file('gambar4')->store('public/judul_header');
        } else {
            $namaFile5 = $judulHeader->gambar4;
        }

        // Update data ke database
        $judulHeader->update([
            'nama_rs'   => $request->nama_rs,
            'alamat_rs' => $request->alamat_rs,
            'tlp_rs'    => $request->tlp_rs,
            'email_rs'  => $request->email_rs,
            'wilayah'   => $request->wilayah,
            'kode_pos'  => $request->kode_pos,
            'header1'   => $request->header1,
            'header2'   => $request->header2,
            'header3'   => $request->header3,
            'header4'   => $request->header4,
            'header5'   => $request->header5,
            'header6'   => $request->header6,
            'gambar1'   => $namaFile1,
            'gambar2'   => $namaFile2,
            'gambar3'   => $namaFile4,
            'gambar4'   => $namaFile5,
            'header7'   => $namaFile3,
        ]);

        return redirect()->route('judul_headers.index')->with('success', 'Data Berhasil di Update');
    }


    public function destroy(JudulHeader $judulHeader)
    {
        if ($judulHeader->gambar1) {
            Storage::delete($judulHeader->gambar1);
        }

        if ($judulHeader->gambar2) {
            Storage::delete($judulHeader->gambar2);
        }

        if ($judulHeader->gambar3) {
            Storage::delete($judulHeader->gambar3);
        }

        if ($judulHeader->gambar4) {
            Storage::delete($judulHeader->gambar4);
        }

        if ($judulHeader->header7) {
            Storage::delete($judulHeader->header7);
        }
        $judulHeader->delete();

        return redirect()->route('judul_headers.index')
            ->with('success', 'kategori_rkbu deleted successfully.');
    }
}
