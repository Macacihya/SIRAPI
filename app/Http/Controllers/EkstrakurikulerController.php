<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        $ekstrakurikulers = Ekstrakurikuler::with('raportEkskuls')->orderBy('nama_eskul')->get();

        return view('pages.rapor.index', compact('ekstrakurikulers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_eskul' => 'required|string|max:100|unique:ekstrakurikulers,nama_eskul',
        ]);

        Ekstrakurikuler::create($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $validated = $request->validate([
            'nama_eskul' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ekstrakurikulers', 'nama_eskul')->ignore($ekstrakurikuler->id),
            ],
        ]);

        $ekstrakurikuler->update($validated);

        return redirect()
            ->route('rapor')
            ->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler)
    {
        $ekstrakurikuler->delete();

        return redirect()
            ->route('rapor')
            ->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}
