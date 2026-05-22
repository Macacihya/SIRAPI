<?php

namespace App\Http\Controllers;

use App\Models\NilaiSikap;
use App\Models\Raport;
use App\Models\Sikap;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NilaiSikapController extends Controller
{
    public function index()
    {
        $nilaiSikaps = NilaiSikap::with(['raport.siswa.kelas', 'sikap'])->latest()->get();
        $raports = Raport::with(['siswa', 'tahunAjaran', 'nilaiSikaps.sikap'])->latest()->get();
        $sikaps = Sikap::orderBy('nama_sikap')->get();

        return view('pages.rapor.index', compact('nilaiSikaps', 'raports', 'sikaps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id',
            'sikap_id' => 'nullable|exists:sikaps,id',
            'predikat' => 'nullable|in:A,B,C,D',
            'deskripsi' => 'nullable|string',
            'nilai_sikaps' => 'nullable|array|min:1',
            'nilai_sikaps.*.sikap_id' => 'required|exists:sikaps,id|distinct',
            'nilai_sikaps.*.predikat' => 'nullable|in:A,B,C,D',
            'nilai_sikaps.*.deskripsi' => 'nullable|string',
        ]);

        if (!empty($validated['nilai_sikaps'])) {
            $raport = Raport::findOrFail($validated['raport_id']);
            $syncData = collect($validated['nilai_sikaps'])
                ->mapWithKeys(fn (array $nilaiSikap) => [
                    $nilaiSikap['sikap_id'] => [
                        'predikat' => $nilaiSikap['predikat'] ?? null,
                        'deskripsi' => $nilaiSikap['deskripsi'] ?? null,
                    ],
                ])
                ->all();

            $raport->sikaps()->sync($syncData);
        } else {
            $request->validate([
                'sikap_id' => [
                    'required',
                    Rule::unique('raport_sikaps')->where(fn ($query) => $query
                        ->where('raport_id', $validated['raport_id'])
                        ->where('sikap_id', $validated['sikap_id'])),
                ],
            ]);

            NilaiSikap::create([
                'raport_id' => $validated['raport_id'],
                'sikap_id' => $validated['sikap_id'],
                'predikat' => $validated['predikat'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);
        }

        return redirect()
            ->route('rapor')
            ->with('success', 'Nilai sikap berhasil ditambahkan.');
    }

    public function update(Request $request, NilaiSikap $nilaiSikap)
    {
        $validated = $request->validate([
            'raport_id' => 'required|exists:raports,id',
            'sikap_id' => [
                'required',
                'exists:sikaps,id',
                Rule::unique('raport_sikaps')->ignore($nilaiSikap->id)->where(fn ($query) => $query
                    ->where('raport_id', $request->input('raport_id'))
                    ->where('sikap_id', $request->input('sikap_id'))),
            ],
            'predikat' => 'nullable|in:A,B,C,D',
            'deskripsi' => 'nullable|string',
        ]);

        $nilaiSikap->update($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Nilai sikap berhasil diperbarui.');
    }

    public function destroy(NilaiSikap $nilaiSikap)
    {
        $nilaiSikap->delete();

        return redirect()
            ->route('rapor')
            ->with('success', 'Nilai sikap berhasil dihapus.');
    }
}
