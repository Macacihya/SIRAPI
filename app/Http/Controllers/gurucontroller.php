<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Sekolah;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = collect([
            (object)['nama' => 'Budi Santoso',    'nip' => '198501012010011001', 'mata_pelajaran' => 'Matematika',   'sekolah' => (object)['nama_sekolah' => 'SD Negeri 1 Jakarta']],
            (object)['nama' => 'Siti Rahayu',     'nip' => '198702022011012002', 'mata_pelajaran' => 'Bahasa Indonesia', 'sekolah' => (object)['nama_sekolah' => 'SD Negeri 1 Jakarta']],
            (object)['nama' => 'Ahmad Fauzi',     'nip' => '199003032012011003', 'mata_pelajaran' => 'IPA',          'sekolah' => (object)['nama_sekolah' => 'SD Negeri 2 Jakarta']],
            (object)['nama' => 'Dewi Lestari',    'nip' => '198904042013012004', 'mata_pelajaran' => 'IPS',          'sekolah' => (object)['nama_sekolah' => 'SD Negeri 2 Jakarta']],
            (object)['nama' => 'Rudi Hermawan',   'nip' => '199105052014011005', 'mata_pelajaran' => 'PJOK',         'sekolah' => (object)['nama_sekolah' => 'SD Negeri 3 Jakarta']],
        ]);

        return view('pages.admin.guru-tendik', compact('gurus'));
    }

    public function tampilkan()
    {
        $gurus = collect([
            (object)['nama' => 'Budi Santoso',    'nip' => '198501012010011001', 'mata_pelajaran' => 'Matematika',   'sekolah' => (object)['nama_sekolah' => 'SD Negeri 1 Jakarta']],
            (object)['nama' => 'Siti Rahayu',     'nip' => '198702022011012002', 'mata_pelajaran' => 'Bahasa Indonesia', 'sekolah' => (object)['nama_sekolah' => 'SD Negeri 1 Jakarta']],
        ]);
        return view('pages.admin.guru-tendik', compact('gurus'));
    }

    public function create()
    {
        $sekolahs = Sekolah::all();
        return view('pages.guru.create', compact('sekolahs'));
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
