<?php declare(strict_types=1);

namespace Assignment\Calculator;

final class Calculator extends AbstractCalculator
{

    /**
     * @inheritDoc
     */
    protected function performPreCalculation(): float
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    protected function performPostCalculation(): float
    {
        return 0;
    }
}
