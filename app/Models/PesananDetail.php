<?php

namespace App\Models;

use App\Models\Pesanan;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananDetail extends Model
{
   public function product(){
    return $this->belongsTo(Product::class,'product_id','id');
   }

   public function pesanan(){
    return $this->belongsTo(Pesanan::class,'pesanan_id','id');
   }
}
