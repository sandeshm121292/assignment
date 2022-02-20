<?php declare(strict_types=1);

namespace Assignment\Product;

interface ProductInterface
{

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @return float
     */
    public function getTax(): float;

    /**
     * @return float
     */
    public function getPrice(): float;
}