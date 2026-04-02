<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportProduk extends Command
{
    protected $signature = 'import:produk';
    protected $description = 'Import produk dari Excel otomatis';

    public function handle()
    {
        $path = storage_path('app/import/produk.xlsx');

        if (!file_exists($path)) {
            $this->error('File tidak ditemukan');
            return;
        }

        Excel::import(new ProductImport, $path);

        $this->info('Import sukses');
    }
}