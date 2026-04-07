<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;

class SekolahController extends Controller
{
    public function index()
    {
        $sekolahs = Sekolah::all();
        return view('sekolah.index', compact('sekolahs'));
    }

    public function create()
    {
        return view('sekolah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required',
            'alamat' => 'required',
            'telepon' => 'required'
        ]);

        Sekolah::create($request->all());

        return redirect()->route('sekolah.index');
    }

    public function edit($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        return view('sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $request->validate([
            'nama_sekolah' => 'required',
            'alamat' => 'required',
            'telepon' => 'required'
        ]);

        $sekolah->update($request->all());

        return redirect()->route('sekolah.index');
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $sekolah->delete();

        return redirect()->route('sekolah.index');
    }
}