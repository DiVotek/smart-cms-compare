<?php

namespace SmartCms\Compare;

use Illuminate\Support\Collection;
use SmartCms\Compare\Services\CompareService;
use SmartCms\Core\Resources\BaseResource;
use SmartCms\Core\Resources\PageResource;
use SmartCms\Store\Models\Attribute;
use SmartCms\Store\Models\AttributeValue;
use SmartCms\Store\Models\Product;
use SmartCms\Store\Resources\Attribute\AttributeResource;
use SmartCms\Store\Resources\Attribute\ProductAttributeResource;
use SmartCms\Store\Resources\Product\ProductResource;

class CompareResource extends BaseResource
{
    public Collection $attributes;

    public function prepareData($request): array
    {
        $seo = $this->resource->getSeo();
        $name = $this->resource->name();
        $this->getAttributes();
        $data = [
            'id' => $this->resource->id,
            'name' => $name,
            'heading' => $seo->heading ?? $name,
            'breadcrumbs' => array_map(fn($breadcrumb) => (object) $breadcrumb, $this->resource->getBreadcrumbs()),
            'image' => $this->validateImage($this->resource->image),
            'banner' => $this->validateImage($this->resource->banner),
            'attributes' => $this->attributes->map(fn($attribute) => AttributeResource::make($attribute)->get())->toArray(),
            'products' => $this->getProducts(),
            'summary' => $seo->summary ?? '',
            'content' => $seo->content ?? '',
            'parent' => $this->resource->parent ? PageResource::make($this->resource->parent)->get() : null,
            'title' => $seo->title ?? $name,
            'meta_description' => $seo->description ?? '',
        ];

        return $data;
    }

    public function getAttributes()
    {
        $productIds = CompareService::getProducts();

        if (empty($productIds)) {
            $this->attributes = collect();
        }

        $attributeIds = AttributeValue::query()
            ->whereHas('products', function ($query) use ($productIds) {
                $query->whereIn(Product::getDb() . '.id', $productIds);
            })
            ->distinct()
            ->pluck(AttributeValue::getDb() . '.attribute_id');
        $this->attributes = Attribute::query()
            ->whereIn('id', $attributeIds)
            ->get();
    }

    /**
     * Get products with their attribute values organized for comparison
     *
     * @return array
     */
    public function getProducts(): array
    {
        $productIds = CompareService::getProducts();
        $products = Product::query()->whereIn('id', $productIds)->get();
        foreach ($products as $product) {
            $attibuteValuesDto = [];
            foreach ($this->attributes as $attribute) {
                $attributeValues = $product->attributeValues()
                    ->where('attribute_id', $attribute->id)
                    ->get();
                $values = $attributeValues->map(function ($value) {
                    return [
                        'name' => $value->name(),
                    ];
                });
                $originValue = implode(', ', $values->pluck('name')->toArray());
                if (blank($originValue)) {
                    $originValue = ' - ';
                }
                $attibuteValuesDto[$attribute->id] = ProductAttributeResource::make($attribute, ['value' => $originValue])->get();
            }
            $productDto = ProductResource::make($product)->get();
            $productDto->attributes = $attibuteValuesDto;
            $result[] = $productDto;
        }
        return $result;
    }
}
