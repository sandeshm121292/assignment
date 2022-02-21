<?php declare(strict_types=1);

namespace Assignment\Calculator;

final class CalculatedOutcome
{

    /**
     * @var string
     */
    private $productId;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $basePrice;

    /**
     * @var float
     */
    private $taxPrice;

    /**
     * @var float
     */
    private $totalPrice;

    /**
     * @param string $productId
     * @param int $quantity
     * @param float $basePrice
     * @param float $taxPrice
     * @param float $totalPrice
     */
    public function __construct(string $productId, int $quantity, float $basePrice, float $taxPrice, float $totalPrice)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->basePrice = $basePrice;
        $this->taxPrice = $taxPrice;
        $this->totalPrice = $totalPrice;
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

    /**
     * @return float
     */
    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    /**
     * @return float
     */
    public function getTaxPrice(): float
    {
        return $this->taxPrice;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }
}
