<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data dummy sesuai permintaan (ID dan Nama Produk)
        $data = [
            ['id' => 1, 'produk' => 'Sepatu Laravel'],
            ['id' => 2, 'produk' => 'Baju Blade'],
            ['id' => 3, 'produk' => 'Topi Artisan'],
            ['id' => 4, 'produk' => 'Tas Composer'],
        ];

        // Mengirim data ke view 'list_product'
        return view('list_product', compact('data'));
    }
}
