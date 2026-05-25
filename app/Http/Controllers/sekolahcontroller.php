<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    /**
     * Tampilkan profil sekolah pertama (karena SIRAPI biasanya untuk single sekolah).
     */
    public function index()
    {
        $sekolah = Sekolah::first();

        // Jika belum ada data sekolah, buat data default kosong agar tidak error di view
        if (!$sekolah) {
            $sekolah = Sekolah::create([
                'npsn'                => '00000000',
                'nama_sekolah'        => 'Nama Sekolah Default',
                'alamat'              => 'Alamat Default',
                'kode_pos'            => '00000',
                'telepon'             => '000-000000',
                'email'               => 'sekolah@sirapi.sch.id',
                'nip_kepsek'          => '-',
                'status_sekolah'      => 'Negeri',
                'nama_kepala_sekolah' => '-',
                'bentuk_pendidikan'   => 'SD',
            ]);
        }

        return view('pages.data-sekolah.index', compact('sekolah'));
    }

    public function create()
    {
        return view('pages.sekolah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'npsn'                => 'required|string|max:20|unique:sekolahs,npsn',
            'nama_sekolah'        => 'required|string|max:255',
            'alamat'              => 'required|string',
            'kode_pos'            => 'nullable|string|max:10',
            'telepon'             => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:255',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nip_kepsek'          => 'nullable|string|max:50',
            'status_sekolah'      => 'nullable|string|max:50',
            'nama_kepala_sekolah' => 'nullable|string|max:255',
            'bentuk_pendidikan'   => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $sekolah = Sekolah::create($validated);

        return redirect()
            ->route('data-sekolah')
            ->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        return view('pages.sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $validated = $request->validate([
            'npsn'                => 'required|string|max:20|unique:sekolahs,npsn,' . $id,
            'nama_sekolah'        => 'required|string|max:255',
            'alamat'              => 'required|string',
            'kode_pos'            => 'nullable|string|max:10',
            'telepon'             => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:255',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nip_kepsek'          => 'nullable|string|max:50',
            'status_sekolah'      => 'nullable|string|max:50',
            'nama_kepala_sekolah' => 'nullable|string|max:255',
            'bentuk_pendidikan'   => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($sekolah->logo) {
                Storage::disk('public')->delete($sekolah->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $sekolah->update($validated);

        return redirect()
            ->route('data-sekolah')
            ->with('success', 'Informasi sekolah berhasil diperbarui.');
    }

    /**
     * Update via AJAX (fetch/FormData) — mengembalikan JSON response.
     */
    public function updateAjax(Request $request)
    {
        $sekolah = Sekolah::first();
        if (!$sekolah) {
            return response()->json(['message' => 'Data sekolah tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'npsn'                => 'required|string|max:20|unique:sekolahs,npsn,' . $sekolah->id,
            'nama_sekolah'        => 'required|string|max:255',
            'alamat'              => 'required|string',
            'kode_pos'            => 'nullable|string|max:10',
            'telepon'             => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:255',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nip_kepsek'          => 'nullable|string|max:50',
            'status_sekolah'      => 'nullable|string|max:50',
            'nama_kepala_sekolah' => 'nullable|string|max:255',
            'bentuk_pendidikan'   => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('logo')) {
            if ($sekolah->logo) {
                Storage::disk('public')->delete($sekolah->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $sekolah->update($validated);

        return response()->json([
            'message'  => 'Informasi sekolah berhasil diperbarui.',
            'logo_url' => $sekolah->logo ? asset('storage/' . $sekolah->logo) : null,
        ]);
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        if ($sekolah->logo) {
            Storage::disk('public')->delete($sekolah->logo);
        }
        $sekolah->delete();

        return redirect()
            ->route('data-sekolah')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }
}