<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Sekolah;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('sekolah')->get();
        return view('guru.index', compact('gurus'));
    }

    public function create()
    {
        $sekolahs = Sekolah::all();
        return view('guru.create', compact('sekolahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'mata_pelajaran' => 'required',
            'sekolah_id' => 'required'
        ]);

        Guru::create($request->all());

        return redirect()->route('guru.index');
    }
}