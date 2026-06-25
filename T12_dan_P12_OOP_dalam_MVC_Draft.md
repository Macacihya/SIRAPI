# LAPORAN TUGAS TEORI & PRAKTIKUM
## T11/P11 & T12/P12: Pemrograman Berorientasi Objek (OOP) dalam Arsitektur Model View Controller (MVC) Laravel

*   **Mata Kuliah:** Pemrograman Berorientasi Objek
*   **Nama / NIM:** [Nama Anda] / [NIM Anda]
*   **Proyek PBL:** SIRAPI (Sistem Informasi Rapor dan Penilaian Akademik)
*   **File Pengumpulan:** `T12 dan P12 OOP dalam MVC_[NIM].pdf`

---

## BAGIAN A — IMPLEMENTASI MVC DI LARAVEL

Fitur utama PBL yang dipilih untuk analisis ini adalah **Tambah Data Guru Baru (Create Guru)**. 

### 1. Implementasi Kode Program Fitur Tambah Data Guru

#### A. Model (`app/Models/Guru.php`)
Model `Guru` bertugas mewakili entitas data guru di database serta menyimpan definisi hubungan data.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Guru extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'int';

    // Kolom-kolom yang diperbolehkan diisi (mass assignment protection)
    protected $fillable = [
        'user_id',
        'nip',
        'sekolah_id',
        'jabatan',
    ];

    // Relasi 1:1 (Inverse/ISA) ke Model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
```

#### B. Controller (`app/Http/Controllers/gurucontroller.php`)
Controller menangani penerimaan data input form tambah guru, memvalidasi input, dan mengoordinasikan proses penyimpanan.

```php
<?php

namespace App\Http\Controllers;

use App\Contracts\GuruServiceInterface;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuruController extends Controller
{
    // Menggunakan OOP Dependency Injection untuk memanggil service penyimpanan
    public function __construct(
        private GuruServiceInterface $assignmentService
    ) {}

    // Menyimpan data guru baru via AJAX (Fitur Tambah Data)
    public function storeAjax(Request $request)
    {
        try {
            // Validasi data input dari user
            $validated = $request->validate([
                'nama'                => 'required|string|max:255',
                'user_id'             => 'nullable|exists:users,id',
                'email'               => 'nullable|email|unique:users,email',
                'nip'                 => 'required|numeric|digits_between:1,18|unique:gurus,nip',
                'peran'               => 'required|in:GURU MAPEL,WALI KELAS,GURU MAPEL & WALI KELAS',
                'mapel_ids'           => 'nullable|array',
                'kelas_pengampu_ids'  => 'nullable|array',
                'kelas_wali_id'       => 'nullable|exists:kelas,id',
            ]);

            // Validasi logis menggunakan Service Layer
            $this->assignmentService->validatePengampuSelection($validated);
            $this->assignmentService->validateKelasWaliSelection($validated);

            // Simpan data melalui service
            $guru = $this->assignmentService->prosesSimpan($validated);
            
            // Mengembalikan respons sukses berformat JSON ke View
            return response()->json([
                'message' => 'Data guru berhasil ditambahkan.',
                'guru'    => $this->assignmentService->buildGuruRow($guru)
            ]);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
```

#### C. View (`resources/views/pages/guru-tendik/index.blade.php`)
View menyediakan form modal input data guru untuk diisi oleh user dan mengirimkannya secara AJAX reaktif menggunakan Alpine.js.

```html
<!-- Tombol untuk membuka Modal Tambah Guru -->
<button @click="showAdd = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
    Tambah Data Baru
</button>

<!-- Modal Form Tambah Guru -->
<div x-show="showAdd" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded-xl w-full max-w-xl">
        <h3 class="text-lg font-bold">Tambah Data Guru Baru</h3>
        
        <div class="mt-4 space-y-4">
            <!-- Input NIP -->
            <div>
                <label class="text-xs font-bold text-gray-500">NIP / NUPTK</label>
                <input x-model="form.user_nip" class="w-full border rounded-lg p-2" placeholder="19XXXXXXXX">
            </div>
            <!-- Input Nama -->
            <div>
                <label class="text-xs font-bold text-gray-500">Nama Lengkap & Gelar</label>
                <input x-model="form.user_name" class="w-full border rounded-lg p-2" placeholder="Nama Guru, S.Pd.">
            </div>
            <!-- Input Email -->
            <div>
                <label class="text-xs font-bold text-gray-500">Email</label>
                <input x-model="form.user_email" class="w-full border rounded-lg p-2" placeholder="guru@email.com">
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-end gap-2">
            <button @click="showAdd = false" class="border px-4 py-2 rounded-lg">Batal</button>
            <button @click="submitAdd()" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Tambah Guru</button>
        </div>
    </div>
</div>
```

### 2. Penjelasan Peran Masing-Masing dalam Fitur "Tambah Data Guru"
*   **Model (M):** Bertanggung jawab mengelola representasi data tabel `gurus` dan `users` di database. Model bertugas menjaga integritas data (kolom yang boleh diisi melalui `$fillable`) serta memetakan hubungan keterkaitan data guru dengan user login dasar (`belongsTo`).
*   **Controller (C):** Bertindak sebagai pengatur lalu lintas request. Ketika tombol "Tambah Guru" diklik, Controller menerima data input, memvalidasi format data input (misal mendeteksi jika NIP sudah terdaftar), mendelegasikan transaksi penyimpanan ke Service Layer, dan membalas dengan JSON response (status `200` jika sukses, atau `422` jika validasi input gagal).
*   **View (V):** Bertindak sebagai antarmuka yang berinteraksi langsung dengan user. View menampilkan form input teks, mendeteksi event klik tombol simpan, mengirim request AJAX ke controller, dan menampilkan status sukses berupa notifikasi/toast tanpa perlu memuat ulang seluruh halaman browser.

---

## BAGIAN B — FLOW SISTEM (WAJIB)

Berikut adalah detail alur perjalanan data dari Request hingga Response untuk fitur **Tambah Data Guru**:

### 1. Diagram Alur
```text
User ──> Route ──> Controller ──> Model ──> Database ──> Controller ──> View
```

### 2. Penjelasan Detail Langkah demi Langkah

*   **User:**
    Pengguna mengisi formulir NIP, nama lengkap, dan email pada modal tambah data di halaman browser, kemudian mengklik tombol **"Tambah Guru"**. Halaman web memicu skrip JavaScript `submitAdd()` untuk mengirimkan HTTP POST Request berisi data form ke server menggunakan fungsi `fetch()` ke URL `/guru-ajax`.
    
*   **Route:**
    Server Laravel menangkap HTTP Request tersebut. Router pada **`routes/web.php`** mencocokkan URL target `/guru-ajax` dan HTTP Method `POST`, lalu mengalihkan jalannya request untuk ditangani oleh method `storeAjax` pada kelas **`GuruController`**.
    
*   **Controller:**
    **`GuruController`** menerima objek data request. Controller pertama kali mengeksekusi validasi dasar tipe data (memastikan NIP harus angka, email berformat valid, dan mendeteksi apakah data sudah ada). Jika validasi lolos, Controller memanggil logika penyimpanan pada Service Layer (`GuruAssignmentService`) yang memanggil Model Eloquent.
    
*   **Model:**
    Model **`User`** dan **`Guru`** diaktifkan untuk mempersiapkan query pembuatan record baru. Model bertindak menjaga aturan *Mass Assignment* (mencocokkan atribut dengan kolom `$fillable`) dan merumuskan query SQL insert.
    
*   **Database:**
    Engine Database (MySQL) menerima query SQL insert yang dikirimkan oleh ORM Model. Database memproses penyimpanan baris baru ke dalam tabel `users` dan tabel `gurus`. Setelah data berhasil disimpan secara fisik, database mengirimkan status sukses kembali ke Model, yang diteruskan ke Controller.
    
*   **Controller:**
    Setelah menerima objek data guru baru dari model/service, **`GuruController`** menyusun respons data. Controller mengubah objek model menjadi format representasi array terstruktur (JSON) dan mengembalikannya ke client sebagai HTTP Response dengan status code `200 OK`.
    
*   **View:**
    JavaScript (Alpine.js) di **`View`** menerima response JSON sukses tersebut. Skrip reaktif View memasukkan objek guru baru ke dalam list array tampilan guru lokal (`this.gurus.unshift(...)`), memperbarui baris tabel HTML secara reaktif tanpa me-reload browser, dan memunculkan toast notifikasi sukses kepada User.

---

## BAGIAN C — INTEGRASI OOP DI LARAVEL

### 1. Identifikasi Konsep OOP yang Digunakan
1.  **Inheritance (Pewarisan):** Model `Guru` mewarisi kelas Eloquent Model (`class Guru extends Model`) dan `GuruController` mewarisi base Controller Laravel (`class GuruController extends Controller`).
2.  **Encapsulation (Pengkapsulan):** Menggunakan visibilitas keyword `protected` pada atribut model (`protected $fillable`, `protected $primaryKey`) dan keyword `private` pada parameter dependency injection constructor controller.
3.  **Abstraction & Polymorphism (Interface & Dependency Injection):** Menggunakan interface `GuruServiceInterface` sebagai tipe parameter di constructor controller, yang secara dinamis memanggil kelas konkret `GuruAssignmentService`.

### 2. Penjelasan Peran dalam Arsitektur MVC
*   **Inheritance (Pewarisan):** Membantu komponen Model mengakses secara instan fitur Active Record (seperti fungsi `create()`, `find()`, `save()`) tanpa perlu menulis manual kueri koneksi database mentah, menyederhanakan kode model MVC.
*   **Encapsulation (Pengkapsulan):** Membatasi akses atribut data secara ilegal dari luar kelas. Di MVC, properti `protected $fillable` melindungi data model dari modifikasi input form berbahaya (Mass Assignment Vulnerability), menjaga keamanan data sebelum disimpan ke database.
*   **Abstraction & Polymorphism:** Memisahkan ikatan ketergantungan yang kaku (*Loose Coupling*) antara Controller dan Service/Model. Membantu unit testing karena implementasi logika penyimpanan data guru dapat ditukar dengan mudah tanpa merusak file Controller.

---

## BAGIAN D — PRAKTIK ANALISIS KODE

### 1. Pembagian Penanganan Kode Fitur "Tambah Data Guru"

#### A. Bagian yang Menangani Logic (Business Logic & Validation)
Ditangani oleh **Controller** dan kelas **Service**.
*Contoh Kode Logic (di `app/Services/GuruAssignmentService.php`):*
```php
public function prosesSimpan(array $validated): Guru
{
    return DB::transaction(function () use ($validated) {
        // Membuat data user autentikasi dasar
        $user = User::create([
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'username' => $validated['username'] ?? $this->uniqueUsername($validated['email']),
            'password' => Hash::make($validated['nip']), // password default NIP
        ]);

        // Menyimpan data profil guru
        $guru = Guru::create([
            'user_id'    => $user->id,
            'nip'        => $validated['nip'],
            'sekolah_id' => $validated['sekolah_id'] ?? Sekolah::first()->id,
        ]);

        return $guru;
    });
}
```

#### B. Bagian yang Menangani Data (Representasi Database)
Ditangani oleh **Model** melalui Eloquent ORM.
*Contoh Kode Data (di `app/Models/Guru.php`):*
```php
protected $primaryKey = 'user_id';
public $incrementing = false;

protected $fillable = [
    'user_id',
    'nip',
    'sekolah_id',
    'jabatan',
    'mata_pelajaran',
];

// Relasi 1:1 (Inverse/ISA) ke Model User
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
```

#### C. Bagian yang Menangani Tampilan (UI)
Ditangani oleh **View (Blade & Alpine.js)**.
*Contoh Kode Tampilan (di `resources/views/pages/guru-tendik/index.blade.php`):*
```html
<div x-show="showAdd" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded-xl w-full max-w-xl">
        <h3 class="text-lg font-bold">Tambah Data Guru Baru</h3>
        <input x-model="form.user_name" class="w-full border rounded-lg p-2" placeholder="Nama Guru">
        <button @click="submitAdd()" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Tambah Guru</button>
    </div>
</div>
```

### 2. Penelusuran Error (Debugging Flow)
Jika terjadi error pada fitur "Tambah Data Guru":

*   **Bagian Pertama yang Dicek:** **Developer Tools Browser (F12) -> Tab Network**.
*   **Alasannya:** Karena proses pengiriman data menggunakan AJAX, tab Network akan langsung menampilkan respon request POST `/guru-ajax`. 
    *   Jika status **`422 Unprocessable Entity`**, berarti kesalahan ada pada **Controller/Request Validation** (data input form tidak sesuai aturan, misalnya NIP sudah terdaftar).
    *   Jika status **`500 Internal Server Error`**, berarti ada kendala di backend/server. Saya selanjutnya akan memeriksa file **`storage/logs/laravel.log`** untuk mengidentifikasi kesalahan database, relasi model, atau bug di kelas **`GuruAssignmentService`**.
    *   Jika tidak ada request terkirim sama sekali di Network, berarti kesalahan ada pada **View (skrip Alpine.js di frontend)**.

---

## BAGIAN E — REFLEKSI

### 1. Manfaat MVC + OOP dalam PBL Anda?
*   **Struktur Kode Sangat Rapi (Separation of Concerns):** Logika tampilan UI (Blade) terpisah mutlak dengan logika bisnis (Service/Controller) dan data (Model). Modifikasi tampilan form tidak akan merusak skrip database.
*   **Dapat Digunakan Ulang (Reusability):** Pewarisan (*Inheritance*) dari model dasar Laravel menghemat penulisan ribuan baris kode query. Penggunaan *Trait* logs aktivitas dapat dipakai di semua model tanpa menulis ulang logic logging.

### 2. Kesulitan Terbesar yang Dialami saat Implementasi?
*   **Sinkronisasi Relasi Tabel Bersamaan:** Proses penambahan guru mengharuskan penyimpanan ke tabel `users` (untuk login) dan tabel `gurus` (untuk profil kepegawaian) secara simultan. Menjaga agar kedua penyimpanan ini berjalan sukses secara bersamaan menggunakan mekanisme `DB::transaction` agar tidak ada data user tanpa profil guru (atau sebaliknya) menjadi tantangan teknis tersendiri.
