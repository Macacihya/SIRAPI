<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController_Fariz extends Controller
{
    public function index()
    {
        return view('list_product', [
            'id' => 1,
            'produk' => 'Laptop'
        ]);
    }
}
