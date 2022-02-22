<?php declare(strict_types=1);

namespace Assignment\Calculator;

/**
 * Here we can add custom logic which will be added to grand total in the end
 * Depends on the custom logic and the requirements, we would need to refactor a bit to know what do we want to return
 * For assignment, assumptions has been made for the given example
 *
 * @see \Assignment\Test\Integration\Order\CalculatorTest
 */
final class Calculator extends AbstractCalculator
{

    /**
     * @inheritDoc
     */
    protected function getPreCalculationPrice(): float
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    protected function getPostCalculationPrice(): float
    {
        return 0;
    }
}
