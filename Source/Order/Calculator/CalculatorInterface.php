<?php declare(strict_types=1);

namespace Assignment\Order\Calculator;

use Assignment\Order\Resource\OrderResource;
use Assignment\Product\Exception\CannotFindProductException;

interface CalculatorInterface
{
    /**
     * @throws CannotFindProductException
     * @return OrderResource
     */
    public function calculate(): OrderResource;
}
