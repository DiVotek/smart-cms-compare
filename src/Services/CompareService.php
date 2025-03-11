<?php

namespace SmartCms\Compare\Services;

use Illuminate\Support\Collection;
use SmartCms\Compare\CompareDto;
use SmartCms\Core\Models\Page;
use SmartCms\Core\Models\Seo;
use SmartCms\Store\Models\AttributeValue;
use SmartCms\Store\Models\Product;
use SmartCms\Store\Repositories\Product\ProductDto;

class CompareService
{
    public static function toggle(int $id): void
    {
        $products = self::getProducts();
        if (in_array($id, $products)) {
            $products = array_diff($products, [$id]);
        } else {
            $products[] = $id;
        }
        session()->put('compare', $products);
    }

    public static function getProducts(): array
    {
        return session()->get('compare', []);
    }

    public static function getProductsCount(): int
    {
        return count(self::getProducts());
    }

    public static function check(int $id): bool
    {
        return in_array($id, self::getProducts());
    }

    public static function make(): CompareDto
    {
        $page = Page::query()->where('id', setting('pages.compare', 0))->first() ?? new Page(['name' => 'Compare', 'slug' => 'compare']);
        $seo = $page->seo()->where('language_id', current_lang_id())->first() ?? new Seo();

        $result = self::getProductsWithAttributes();

        return new CompareDto(
            id: $page->id ?? 0,
            name: $page->name(),
            breadcrumbs: $page->getBreadcrumbs(),
            products: $result['products'],
            attributes: $result['attributes'],
            image: $page->image ?? null,
            heading: $seo->heading ?? $page->name(),
            short_description: $seo->short_description ?? '',
            content: $page->content ?? '',
            banner: $page->banner ?? null,
        );
    }

    /**
     * Get all available attributes for the products in the compare list
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAttributes(): Collection
    {
        $productIds = self::getProducts();

        if (empty($productIds)) {
            return collect();
        }

        $attributeIds = AttributeValue::query()
            ->whereHas('products', function ($query) use ($productIds) {
                $query->whereIn(Product::getDb() . '.id', $productIds);
            })
            ->distinct()
            ->pluck(AttributeValue::getDb() . '.attribute_id');

        return \SmartCms\Store\Models\Attribute::query()
            ->whereIn('id', $attributeIds)
            ->get();
    }

    /**
     * Get products with their attribute values organized for comparison
     *
     * @return array
     */
    public static function getProductsWithAttributes()
    {
        $productIds = self::getProducts();
        $products = Product::query()->whereIn('id', $productIds)->get();
        $attributes = self::getAttributes();

        $result = [];
        foreach ($products as $product) {
            $attibuteValuesDto = [];
            // Get all attribute values for this product
            foreach ($attributes as $attribute) {
                $attributeValues = $product->attributeValues()
                    ->where('attribute_id', $attribute->id)
                    ->get();
                $values = $attributeValues->map(function ($value) {
                    return [
                        'name' => $value->name(),
                    ];
                });
                $originValue = implode(', ', $values->pluck('name')->toArray());
                if (empty($originValue)) {
                    $originValue = ' - ';
                }
                $attibuteValuesDto[$attribute->id] = $originValue;
            }
            $productDto = ProductDto::fromModel($product);
            $productDto->attributes = $attibuteValuesDto;
            $result[] = $productDto;
        }
        $attributesDto = $attributes->map(function ($attribute) {
            return $attribute->name();
        });
        return [
            'attributes' => $attributesDto->toArray(),
            'products' => $result
        ];
    }
}
