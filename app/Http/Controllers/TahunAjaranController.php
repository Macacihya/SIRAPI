<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::query()
            ->orderByDesc('tahun_mulai')
            ->orderByRaw("CASE WHEN semester = 'Ganjil' THEN 0 ELSE 1 END")
            ->get();

        return view('tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        if ($validated['is_active']) {
            TahunAjaran::query()->update(['is_active' => false]);
        }

        TahunAjaran::create($validated);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $validated = $this->validateRequest($request, $tahunAjaran->id);

        if ($validated['is_active']) {
            TahunAjaran::query()
                ->whereKeyNot($tahunAjaran->id)
                ->update(['is_active' => false]);
        }

        $tahunAjaran->update($validated);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    private function validateRequest(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'tahun_mulai' => ['required', 'integer', 'digits:4', 'min:2000'],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'is_active' => ['nullable', 'boolean'],
            'tahun_selesai' => [
                'required',
                'integer',
                'digits:4',
                'min:2001',
                Rule::unique('tahun_ajarans')
                    ->where(fn ($query) => $query
                        ->where('tahun_mulai', $request->tahun_mulai)
                        ->where('semester', $request->semester))
                    ->ignore($ignoreId),
            ],
        ], [
            'tahun_selesai.required' => 'Tahun selesai wajib diisi.',
            'tahun_selesai.unique' => 'Kombinasi tahun ajaran dan semester sudah ada.',
        ]);

        if ((int) $validated['tahun_selesai'] !== (int) $validated['tahun_mulai'] + 1) {
            return back()
                ->withErrors(['tahun_selesai' => 'Tahun selesai harus satu tahun setelah tahun mulai.'])
                ->withInput()
                ->throwResponse();
        }

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
