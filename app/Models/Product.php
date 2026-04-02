<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('barcode')->unique();
        $table->string('nama_barang');
        $table->integer('harga');
        $table->timestamps();
    });
}
}
