<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.products.index')
                    ->with('products',Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {

        $data = request()->only('name','price','image','description');

        $image = $request->image->store('products');

        // storing path of image
        $data['image'] = 'storage/'.$image;

        Product::create($data);

        session()->flash('success','Product created successfully');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {

        $data = request()->only('name','price','image','description');

        if(request()->hasFile('image'))
        {
            $image = $request->image->store('products');
            $data['image'] = 'storage/'.$image;

            $product->deleteImage();
        }

        $product->update($data);

        session()->flash('success','Product updated successfully');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        $product->deleteImage();
        $product->delete();

        session()->flash('success','Product deleted successfully');

        return back();
    }
}
