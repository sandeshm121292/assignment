<?php

namespace Assignment\Calculator;

use Assignment\Order\Resource\OrderResource;
use Assignment\Product\Exception\CannotFindProductException;

interface CalculatorInterface
{

    /**
     * @return CalculatedOutcome[]
     * @throws CannotFindProductException
     */
    public function calculate(): array;
}
