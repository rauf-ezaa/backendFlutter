<?php

namespace App\Http\Controllers\Api;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\PesananDetail;
use App\Http\Controllers\Controller;


class PesananController extends Controller
{
    public function pesan(Request $request, $id){
        $products = Product::where('id', $id)->first();
        $tanggal = Carbon::now();
        
    	$cek_pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();
    	//simpan ke database pesanan
        if(empty($cek_pesanan))
    	{
    		$pesanan = new Pesanan;
	    	$pesanan->user_id = Auth::user()->id;
	    	$pesanan->tanggal = $tanggal;
	    	$pesanan->status = 0;
	    	$pesanan->jumlah_harga = 0;
            $pesanan->kode = mt_rand(100, 999);
	    	$pesanan->save();
    	}

        $pesanan_baru = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();

    	//cek pesanan detail
    	$cek_pesanan_detail = PesananDetail::where('product_id', $products->id)->where('pesanan_id', $pesanan_baru->id)->first();
    	if(empty($cek_pesanan_detail))
    	{
    		$pesanan_detail = new PesananDetail;
	    	$pesanan_detail->product_id = $products->id;
	    	$pesanan_detail->pesanan_id = $pesanan_baru->id;
	    	$pesanan_detail->jumlah = $request->jumlah_pesan;
	    	$pesanan_detail->jumlah_harga = $products->harga*$request->jumlah_pesan;
	    	$pesanan_detail->save();
    	}
        else 
    	{
    		$pesanan_detail = PesananDetail::where('product_id', $products->id)->where('pesanan_id', $pesanan_baru->id)->first();

    		$pesanan_detail->jumlah = $pesanan_detail->jumlah+$request->jumlah_pesan;

    		//harga sekarang
    		$harga_pesanan_detail_baru = $products->harga*$request->jumlah_pesan;
	    	$pesanan_detail->jumlah_harga = $pesanan_detail->jumlah_harga+$harga_pesanan_detail_baru;
	    	$pesanan_detail->update();

    	}
		$pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();
		$pesanan->jumlah_harga = $pesanan->jumlah_harga+$products->harga*$request->jumlah_pesan;
		$pesanan->update();

        return response()->json([
            'message'=>'Pesanan telah berhasil',
            'success'=>true
        ]);
    }

	public function konfirmasi(){
		$user = User::where('id', Auth::user()->id)->first();

        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();
        $pesanan_id = $pesanan->id; 
        $pesanan->status = 1;
        $pesanan->update();

        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan_id)->get();
        foreach ($pesanan_details as $pesanan_detail) {
			$products = Product::where('id', $pesanan_detail->product_id)->first();
			// $products->stok = $products->stok-$pesanan_detail->jumlah;
            $products->update();
        }
		return response()->json([
			'message'=>'Data pesanan telah berhasil diupdate',
			'status' =>true
		]);
	}
}
