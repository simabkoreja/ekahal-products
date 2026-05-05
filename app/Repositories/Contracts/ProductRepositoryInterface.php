<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginatedForUser(User $user, ?string $search, int $perPage = 10): LengthAwarePaginator;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Product;

    /**
     * @param array<string, mixed> $data
     */
    public function update(Product $product, array $data): Product;

    public function delete(Product $product): void;
}
