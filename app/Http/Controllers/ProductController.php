<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::get();

        if ($product->count() > 0) {
            return ProductResource::collection($product);
        }else {
            return response()->json(['message'=>'No record available'], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   =>'required',
            'detail' => 'required|string|max:255',
        ]);

        $product = product::create($data);

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        $data = $request->validate([
            'name'   => 'required',
            'detail' => 'required|string|max:255',
        ]);

        $product->update($data);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        $product->delete();

        return 'the product has been deleted successfully';
    }
}
