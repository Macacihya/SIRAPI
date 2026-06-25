<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\RiwayatStatusSiswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Menampilkan halaman daftar siswa (menggunakan fungsi tampilkan).
     */
    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

    /**
     * Mengambil data siswa, memfilter, mencari, dan menyiapkan data penempatan kelas.
     */
    public function tampilkan(Request $request)
    {
        // Query dasar mengambil siswa beserta relasi kelas dan riwayat statusnya
        $query = Siswa::with(['kelas', 'riwayatStatus']);
        $role = getUserRole();
        $guruKelasIds = collect();

        if ($role === 'guru') {
            $guruKelasIds = auth()->user()?->guru?->guruPengampus?->pluck('kelas_id')->filter()->unique()->values() ?? collect();

            if ($guruKelasIds->isNotEmpty()) {
                $query->whereIn('kelas_id', $guruKelasIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Filter pencarian berdasarkan kelas jika dipilih (bukan 'Semua')
        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('nama_kelas', $request->kelas);
            });
        }

        // Fitur pencarian berdasarkan nama, NISN, atau NIS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Melakukan paginasi hasil (10 data per halaman) dan mempertahankan parameter URL pencarian/filter
        $data = $query->paginate(10)->withQueryString();

        // Mengubah format (transform) data agar sesuai dengan penamaan field yang dibutuhkan di view Alpine.js
        $data->getCollection()->transform(function ($siswa) {
            // Mengambil status riwayat terakhir siswa
            $latestStatus = $siswa->riwayatStatus->sortByDesc('id')->first();
            return (object)[
                'id'             => $siswa->id,
                'nama'           => $siswa->nama_siswa,
                'nisn'           => $siswa->nisn,
                'nis'            => $siswa->nis,
                'kelas'          => $siswa->kelas ? 'Kelas ' . $siswa->kelas->nama_kelas : '-',
                'kelas_id'       => $siswa->kelas_id,
                'jenis_kelamin'  => $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin === 'P' ? 'Perempuan' : ($siswa->jenis_kelamin === 'Pria' ? 'Laki-laki' : ($siswa->jenis_kelamin === 'Wanita' ? 'Perempuan' : '-'))),
                'jk_raw'         => $siswa->jenis_kelamin,
                'tempat_lahir'   => $siswa->tempat_lahir ?? '-',
                'tgl_lahir'      => $siswa->tgl_lahir ? $siswa->tgl_lahir->format('Y-m-d') : '',
                'alamat'         => $siswa->alamat ?? '',
                'status'         => strtoupper($latestStatus?->status ?? ($siswa->status_aktif ? 'AKTIF' : 'NONAKTIF')),
                'status_aktif'   => $siswa->status_aktif,
                'selected'       => false, // Digunakan oleh Alpine.js untuk checkbox pilihan masal
            ];
        });

        // Mengambil daftar seluruh kelas untuk dropdown filter di halaman view
        $daftarKelasQuery = Kelas::query();
        if ($role === 'guru') {
            $daftarKelasQuery->whereIn('id', $guruKelasIds);
        }

        $daftarKelas = $daftarKelasQuery->orderBy('nama_kelas')->get()->map(fn($k) => (object)[
            'id'   => $k->id,
            'nama' => 'Kelas ' . $k->nama_kelas,
        ])->toArray();

        // Statistik ringkas siswa untuk ditampilkan di dashboard/kartu informasi
        $statQuery = Siswa::query();
        if ($role === 'guru') {
            $statQuery->whereIn('kelas_id', $guruKelasIds);
        }

        $totalSiswa = (clone $statQuery)->count();
        $siswaAktif = (clone $statQuery)->where('status_aktif', true)->count();
        $siswaCuti = (clone $statQuery)->whereHas('riwayatStatus', function ($q) {
            $q->where('status', 'Cuti');
        })->count();

        // Inisialisasi variabel untuk kebutuhan penempatan kelas siswa
        $tahunAjarans = [];
        $tahunAjaranId = null;
        $kelasPenempatan = [];
        $kelasId = null;
        $siswaDiKelas = [];
        $siswaBelumDitempatkan = [];

        // Logika khusus Admin untuk memindahkan/menata penempatan kelas siswa
        if (getUserRole() === 'admin') {
            // Mengambil semua periode tahun ajaran
            $tahunAjarans = \App\Models\TahunAjaran::orderByDesc('tahun_mulai')
                ->orderByRaw("CASE WHEN semester = 'Ganjil' THEN 0 ELSE 1 END")
                ->get();

            // Menentukan tahun ajaran penempatan yang dipilih (default: tahun ajaran yang sedang aktif)
            $tahunAjaranId = $request->input('tahun_ajaran_id');
            if (!$tahunAjaranId) {
                // ini memakai tahun ajaran dan harus diaktifkan untuk di akses
                $activeTahun = $tahunAjarans->where('is_active', true)->first();
                $tahunAjaranId = $activeTahun ? $activeTahun->id : $tahunAjarans->first()?->id;
            }

            if ($tahunAjaranId) {
                // Mengambil daftar kelas yang tersedia pada tahun ajaran tersebut
                $kelasPenempatan = Kelas::where('tahun_ajaran_id', $tahunAjaranId)->orderBy('nama_kelas')->get();
                $kelasId = $request->input('kelas_id', $kelasPenempatan->first()?->id);

                if ($kelasId) {
                    // Mengambil daftar siswa yang sudah dimasukkan ke dalam kelas terpilih
                    $siswaDiKelas = Siswa::where('kelas_id', $kelasId)
                        ->where('status_aktif', true)
                        ->orderBy('nama_siswa')
                        ->get()
                        ->map(fn($s) => [
                            'id' => $s->id,
                            'nama' => $s->nama_siswa,
                            'nisn' => $s->nisn,
                            'jk' => $s->jenis_kelamin === 'Pria' ? 'Pria' : ($s->jenis_kelamin === 'Wanita' ? 'Wanita' : ($s->jenis_kelamin === 'L' ? 'Pria' : ($s->jenis_kelamin === 'P' ? 'Wanita' : '-'))),
                        ])
                        ->toArray();
                }

                // Mengambil siswa aktif yang BELUM ditempatkan di kelas manapun pada periode tahun ajaran ini
                $siswaBelumDitempatkan = Siswa::where('status_aktif', true)
                    ->where(function ($query) use ($tahunAjaranId) {
                        $query->whereNull('kelas_id')
                            ->orWhereHas('kelas', function ($q) use ($tahunAjaranId) {
                                $q->where('tahun_ajaran_id', '!=', $tahunAjaranId);
                            });
                    })
                    ->orderBy('nama_siswa')
                    ->get()
                    ->map(fn($s) => [
                        'id' => $s->id,
                        'nama' => $s->nama_siswa,
                        'nisn' => $s->nisn,
                        'jk' => $s->jenis_kelamin === 'Pria' ? 'Pria' : ($s->jenis_kelamin === 'Wanita' ? 'Wanita' : ($s->jenis_kelamin === 'L' ? 'Pria' : ($s->jenis_kelamin === 'P' ? 'Wanita' : '-'))),
                        'current_kelas' => $s->kelas?->nama_kelas, // Informasi kelas sebelumnya/lama
                    ])
                    ->toArray();
            }
        }

        return view('pages.siswa.index', compact(
            'data', 'daftarKelas', 'totalSiswa', 'siswaAktif', 'siswaCuti',
            'tahunAjarans', 'tahunAjaranId', 'kelasPenempatan', 'kelasId',
            'siswaDiKelas', 'siswaBelumDitempatkan'
        ));
    }

    /**
     * Mendaftarkan/menyimpan data siswa baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input data pendaftaran siswa
        $validated = $request->validate([
            'nama_siswa'    => 'required|string|max:255',
            'nisn'          => 'required|string|max:20|unique:siswas,nisn',
            'nis'           => 'nullable|string|max:20|unique:siswas,nis',
            'kelas_id'      => 'nullable|exists:kelas,id',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tgl_lahir'     => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string',
        ]);

        // Otomatis menautkan ke ID sekolah pertama (default sistem)
        $sekolah = Sekolah::first();
        $validated['sekolah_id'] = $sekolah?->id;
        $validated['status_aktif'] = true;

        // Membuat record siswa baru
        $siswa = Siswa::create($validated);

        // Mencatat riwayat status pendaftaran awal siswa sebagai "Aktif"
        RiwayatStatusSiswa::create([
            'siswa_id'           => $siswa->id,
            'status'             => 'Aktif',
            'keterangan'         => 'Pendaftaran siswa baru',
            'tanggal_perubahan'  => now()->toDateString(),
        ]);

        return redirect()
            ->route('siswa')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Memperbarui profil data siswa yang sudah terdaftar.
     */
    public function update(Request $request, Siswa $siswa)
    {
        // Validasi data profil (abaikan keunikan NISN/NIS milik siswa itu sendiri)
        $validated = $request->validate([
            'nama_siswa'    => 'required|string|max:255',
            'nisn'          => 'required|string|max:20|unique:siswas,nisn,' . $siswa->id,
            'nis'           => 'nullable|string|max:20|unique:siswas,nis,' . $siswa->id,
            'kelas_id'      => 'nullable|exists:kelas,id',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tgl_lahir'     => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string',
        ]);

        // Update profil siswa di database
        $siswa->update($validated);

        return redirect()
            ->route('siswa')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa dari database.
     */
    public function destroy(Siswa $siswa)
    {
        // Hapus data siswa terpilih
        $siswa->delete();

        return redirect()
            ->route('siswa')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Mengubah status keaktifan siswa (Aktif <-> Nonaktif) secara bergantian.
     */
    public function toggleStatus(Siswa $siswa)
    {
        // Membalik status aktif saat ini
        $newStatus = !$siswa->status_aktif;
        $siswa->update(['status_aktif' => $newStatus]);

        // Mencatat perubahan status ini ke dalam tabel riwayat_status_siswas
        RiwayatStatusSiswa::create([
            'siswa_id'          => $siswa->id,
            'status'            => $newStatus ? 'Aktif' : 'Nonaktif',
            'keterangan'        => $newStatus ? 'Diaktifkan kembali oleh admin' : 'Dinonaktifkan oleh admin',
            'tanggal_perubahan' => now()->toDateString(),
        ]);

        $message = $newStatus
            ? "Status siswa {$siswa->nama_siswa} berhasil diubah menjadi Aktif."
            : "Status siswa {$siswa->nama_siswa} berhasil diubah menjadi Nonaktif.";

        return redirect()
            ->route('siswa')
            ->with('success', $message);
    }

    /**
     * Menempatkan daftar siswa terpilih ke kelas tertentu secara masal (manual).
     */
    public function penempatanStore(Request $request)
    {
        // Validasi agar kelas tujuan terdaftar dan siswa yang dicentang valid
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
        ]);

        // Mengupdate kelas_id milik semua siswa terpilih secara bersamaan
        Siswa::whereIn('id', $validated['siswa_ids'])
            ->update(['kelas_id' => $validated['kelas_id']]);

        return redirect()->route('siswa', [
            'tab' => 'penempatan',
            'tahun_ajaran_id' => Kelas::find($validated['kelas_id'])->tahun_ajaran_id,
            'kelas_id' => $validated['kelas_id']
        ])->with('success', count($validated['siswa_ids']) . ' siswa berhasil ditempatkan ke kelas.');
    }

    /**
     * Mengeluarkan daftar siswa terpilih dari kelas secara masal (manual).
     */
    public function penempatanRemove(Request $request)
    {
        // Validasi input daftar siswa yang akan dikeluarkan
        $validated = $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajarans,id',
        ]);

        // Mengubah kelas_id menjadi null agar statusnya kembali ke antrean belum ditempatkan
        Siswa::whereIn('id', $validated['siswa_ids'])
            ->update(['kelas_id' => null]);

        return redirect()->route('siswa', [
            'tab' => 'penempatan',
            'tahun_ajaran_id' => $request->input('tahun_ajaran_id'),
            'kelas_id' => $request->input('kelas_id')
        ])->with('success', count($validated['siswa_ids']) . ' siswa berhasil dikeluarkan dari kelas.');
    }
}
