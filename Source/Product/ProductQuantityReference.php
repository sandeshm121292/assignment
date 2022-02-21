<?php declare(strict_types=1);

namespace Assignment\Product;

final class ProductQuantityReference
{

    /**
     * @var string
     */
    private $productId;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @param string $productId
     * @param int $quantity
     */
    public function __construct(string $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
