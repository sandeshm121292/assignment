<?php

namespace Assignment\Calculator;

use Assignment\Product\Exception\CannotFindProductException;

interface CalculatorInterface
{

    /**
     * @return ConsolidatedCalculationOutcome
     * @throws CannotFindProductException
     */
    public function calculate(): ConsolidatedCalculationOutcome;
}
