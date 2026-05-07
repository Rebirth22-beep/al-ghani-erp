<?php

namespace App\Services;

use App\Models\Product;

/**
 * Thin Product CRUD service. Owns audit logging for create/update/delete so
 * controllers stay free of workflow logic (SKILL.md §4).
 */
class ProductService
{
    public function __construct(private AuditLogService $auditLog) {}

    public function create(array $data): Product
    {
        $product = Product::create($data);
        $this->auditLog->log('create', Product::class, $product->id, [], $product->toArray());
        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $before = $product->toArray();
        $product->update($data);
        $this->auditLog->log('update', Product::class, $product->id, $before, $product->fresh()->toArray());
        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
        $this->auditLog->log('delete', Product::class, $product->id, [], []);
    }
}
