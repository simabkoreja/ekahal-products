<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginatedForUser(User $user, ?string $search, int $perPage = 10): LengthAwarePaginator
    {
        $query = Product::query()->with('user');

        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($search !== null && $search !== '') {
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderByDesc('date_available')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
