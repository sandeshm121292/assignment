<?php declare(strict_types=1);

namespace Assignment\Order\Resource;

use Assignment\Calculator\CalculatedOutcome;
use Assignment\Order\OrderInterface;

final class OrderResource
{

    /**
     * @var OrderInterface[]
     */
    private $orders;

    /**
     * @param OrderInterface[] $orders
     */
    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return float
     */
    public function getGrandTotal(): float
    {
        $total = 0;

        foreach ($this->orders as $outcome) {
            $total += $outcome->getTotalPrice();
        }

        return round($total, 2);
    }

    /**
     * @return float
     */
    public function getGrandTotalTaxPrice(): float
    {
        $total = 0;

        foreach ($this->orders as $outcome) {
            $total += $outcome->getTaxPrice();
        }

        return round($total, 2);
    }

    /**
     * @return CalculatedOutcome[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }
}
