<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ScanController extends Controller
{
    public function index()
    {
        return view('scan');
    }

    public function scan(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return response()->json(['status' => 'error']);
        }

        return response()->json([
            'status' => 'ok',
            'nama' => $product->nama_barang,
            'harga' => $product->harga
        ]);
    }
}