<?php

namespace App\Service;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function list(User $user, ?string $search): LengthAwarePaginator
    {
        return $this->productRepository->paginatedForUser($user, $this->sanitizeSearch($search));
    }

    /**
     * @param array<string, mixed> $input
     */
    public function create(User $user, array $input): Product
    {
        $payload = $this->sanitizePayload($input);
        $payload['user_id'] = $user->id;

        return $this->productRepository->create($payload);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function update(Product $product, array $input): Product
    {
        return $this->productRepository->update($product, $this->sanitizePayload($input));
    }

    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function sanitizePayload(array $input): array
    {
        $allowedTags = '<p><br><b><strong><i><em><u><ul><ol><li><a><blockquote>';
        $description = strip_tags((string) $input['description'], $allowedTags);
        $description = preg_replace('/<(script|style)[^>]*>.*?<\\/\\1>/is', '', $description) ?? '';

        return [
            'title' => Str::squish(strip_tags((string) $input['title'])),
            'description' => $description,
            'price' => $input['price'],
            'date_available' => $input['date_available'],
        ];
    }

    private function sanitizeSearch(?string $search): ?string
    {
        if ($search === null) {
            return null;
        }

        $clean = Str::of($search)
            ->stripTags()
            ->squish()
            ->substr(0, 80)
            ->value();

        return $clean === '' ? null : $clean;
    }
}
