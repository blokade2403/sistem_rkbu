<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterBackend\SettingRkbu\Program;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs   = Program::all();
        return view('master_backend.setting_rkbu.program.index', compact('programs'), [
            'title'         => 'Halaman Program',
            'title2'        => 'Program',
            'routePrefix'   => 'programs'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master_backend.setting_rkbu.program.create', [
            'title'         => 'Halaman Program',
            'title2'        => 'Program',
            'routePrefix'   => 'programs'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_program'  => 'required|unique:programs',
                'nama_program'  => 'required|unique:programs',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());
            // dd($errors);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        $program = new Program([
            'kode_program'  => $request->kode_program,
            'nama_program'  => $request->nama_program,
        ]);

        $program->save();

        return redirect()->route('programs.index')->with('success', 'Program berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        // $program   = Program::all();
        return view('master_backend.setting_rkbu.program.edit', compact('program'), [
            'title'         => 'Halaman Program',
            'title2'        => 'Program',
            'routePrefix'   => 'programs'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'kode_program'  => 'required',
                'nama_program'  => 'required',
            ],

        );
        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            // Mengambil semua pesan error
            $errors = $validator->errors();

            // dd($errors);

            // Menyusun pesan error menjadi string
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: ' . $errorMessages);
        }

        // dd($request);

        $program->update($request->all());
        return redirect()->route('programs.index')->with('success', 'Program Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', 'Program deleted successfully.');
    }
}
