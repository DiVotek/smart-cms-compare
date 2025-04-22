<?php

namespace SmartCms\Compare\Services;


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
}
