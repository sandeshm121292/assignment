<?php declare(strict_types=1);

namespace Assignment\Order\Calculator;

class CalculatedOutcome
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
    private $unitCost;

    /**
     * @var float
     */
    private $taxedCost;

    /**
     * @var float
     */
    private $totalCost;

    /**
     * @param string $productId
     * @param int $quantity
     * @param float $unitCost
     * @param float $taxedCost
     * @param float $totalCost
     */
    public function __construct(string $productId, int $quantity, float $unitCost, float $taxedCost, float $totalCost)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unitCost = $unitCost;
        $this->taxedCost = $taxedCost;
        $this->totalCost = $totalCost;
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
    public function getUnitCost(): float
    {
        return $this->unitCost;
    }

    /**
     * @return float
     */
    public function getTaxedCost(): float
    {
        return $this->taxedCost;
    }

    /**
     * @return float
     */
    public function getTotalCost(): float
    {
        return $this->totalCost;
    }
}