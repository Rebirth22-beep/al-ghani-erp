<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::when($request->search, fn($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->when($request->category, fn($q, $v) => $q->where('category', $v))
            ->paginate($request->integer('per_page', 20));

        // Pre-load stock totals in one query to avoid N+1 in ProductResource
        $stockTotals = StockMovement::whereIn('product_id', $products->pluck('id'))
            ->selectRaw('product_id, SUM(quantity_change) as total')
            ->groupBy('product_id')
            ->pluck('total', 'product_id');

        $products->each(fn($p) => $p->preloaded_stock = (float) ($stockTotals[$p->id] ?? 0));

        return $this->paginatedResponse($products, ProductResource::class);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $product = $this->service->create($request->validated());

        return $this->successResponse(new ProductResource($product), 'Product created.', 201);
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);
        return $this->successResponse(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $product = $this->service->update($product, $request->validated());

        return $this->successResponse(new ProductResource($product), 'Product updated.');
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $this->service->delete($product);
        return $this->successResponse(message: 'Product deleted.');
    }
}
