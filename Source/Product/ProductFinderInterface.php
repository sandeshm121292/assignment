<?php declare(strict_types=1);

namespace Assignment\Product;

use Assignment\Product\Exception\CannotFindProductException;

interface ProductFinderInterface
{
    /**
     * @param string $id
     * @param string $countryCode
     * @return ProductInterface|null
     *
     * @throws CannotFindProductException
     */
    public function findProduct(string $id, string $countryCode): ?ProductInterface;
}
