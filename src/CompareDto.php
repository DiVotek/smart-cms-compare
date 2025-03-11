<?php

namespace SmartCms\Compare;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use SmartCms\Core\Repositories\DtoInterface;
use SmartCms\Core\Traits\Dto\AsDto;

class CompareDto implements DtoInterface
{
    use AsDto;

    public function __construct(public int $id, public string $name, public array $breadcrumbs, public array $products, public array $attributes = [], public ?string $image, public ?string $heading, public ?string $short_description, public ?string $content = '', public ?string $banner = '') {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'heading' => $this->heading ?? $this->name,
            'breadcrumbs' => array_map(fn($breadcrumb) => (object) $breadcrumb, $this->breadcrumbs),
            'image' => $this->validateImage($this->image ?? no_image()),
            'banner' => $this->validateImage($this->banner ?? no_image()),
            'products' => $this->products,
            'attributes' => $this->attributes,
            'summary' => $this->short_description ?? '',
            'content' => $this->content ?? '',
            ...$this->extra,
        ];
    }
}
