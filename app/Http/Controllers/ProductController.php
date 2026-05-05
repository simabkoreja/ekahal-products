<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Request\ProductStoreRequest;
use App\Request\ProductUpdateRequest;
use App\Service\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request): View
    {
        $products = $this->productService->list(
            $request->user(),
            $request->string('search')->toString()
        );

        return view('products.index', [
            'products' => $products,
            'search' => $request->string('search')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $this->productService->create($request->user(), $request->validated());

        return redirect()
            ->route('products.index')
            ->with('status', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $this->productService->update($product, $request->validated());

        return redirect()
            ->route('products.index')
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product);

        return redirect()
            ->route('products.index')
            ->with('status', 'Product deleted successfully.');
    }
}
