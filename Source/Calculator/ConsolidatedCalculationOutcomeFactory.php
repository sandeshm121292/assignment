<?php declare(strict_types=1);

namespace Assignment\Calculator;

final class ConsolidatedCalculationOutcomeFactory
{
    /**
     * @return ConsolidatedCalculationOutcome
     */
    public function create(): ConsolidatedCalculationOutcome
    {
        return new ConsolidatedCalculationOutcome();
    }
}
