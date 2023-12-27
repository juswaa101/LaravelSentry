<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Traits\ResponseHelper;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{

    use ResponseHelper;

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductStoreRequest $request
     * @return JsonResponse
     */
    public function store(ProductStoreRequest $request)
    {
        // validate the request
        try {
            // create the product instance
            $product = new Product($request->validated());

            // save the product
            $product->saveOrFail();

            // return success message
            return $this->success(
                [],
                'Product created successfully',
                200
            );
        } catch (\Exception $e) {
            // return error message
            return $this->error('Product failed to create', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product)
    {
        // retrieve the product
        return $this->success($product, null, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function edit(Product $product)
    {
        // retrieve the product
        return $this->success($product, null, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        // validate the request
        try {
            // update the product
            $product->updateOrFail($request->validated());

            // retrieve the updated data
            return $this->success(
                $product,
                'Product updated successfully',
                200,

            );
        } catch (\Exception $e) {
            // return error message
            return $this->error(
                'Product failed to update',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product)
    {
        // validate the request
        try {
            // delete the product
            $product->deleteOrFail();

            // return success message
            return $this->success(
                $product,
                'Product deleted successfully',
                200,
            );
        } catch (\Exception $e) {
            // return error message
            return $this->error(
                'Product failed to delete',
                500
            );
        }
    }

    /**
     * Retrieve all products.
     *
     * @return JsonResponse
     */
    public function getProducts()
    {
        // retrieve all products
        $products = Product::latest()->get();

        // return success response
        return $this->success($products, null, 200);
    }
}
