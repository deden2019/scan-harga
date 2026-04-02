<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $index => $row) {

            // ⛔ skip header (baris pertama)
            if ($index == 0) continue;

            // ⛔ skip kalau barcode kosong
            if (empty($row[1])) continue;

            $data[] = [
                'barcode' => trim($row[1]),
                'nama_barang' => $row[2],
                'harga' => (int) $row[7],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Product::upsert(
            $data,
            ['barcode'],
            ['nama_barang', 'harga', 'updated_at']
        );
    }
}