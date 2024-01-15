<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::latest()->get();
        return response()->json([
            'data'=>ProductResource::collection($product),
            'messasge'=>'fetch all data',
            'success'=> true
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_barang'=>'required|string|max:100',
            'harga'=>'required',
            'tentang'=>'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'data'=>[],
                'message'=>$validator->errors(),
                'success'=>false
            ]);
        }

        $product = Product::create([
            'nama_barang' => $request->get('nama_barang'),
            'harga' => $request->get('harga'),
            'tentang' => $request->get('tentang'),
        ]);

        return response()->json([
            'data' => new ProductResource($product),
            'message' => 'Post created successfully.',
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'data'=> new ProductResource($product),
            'message'=> 'Data Product ditemukan',
            'success'=> true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message'=>'Data Product berhasil dihapus',
            'success'=> true
        ]);
    }
}
