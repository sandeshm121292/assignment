<?php declare(strict_types=1);

namespace Assignment\Formatter;


use Assignment\Calculator\ConsolidatedCalculationOutcome;

abstract class AbstractFormatter
{

    private $orderId;

    /**
     * @var ConsolidatedCalculationOutcome
     */
    protected $consolidatedCalculationOutcome;

    /**
     * @param string $orderId
     * @param ConsolidatedCalculationOutcome $consolidatedCalculationOutcome
     */
    public function __construct(string $orderId, ConsolidatedCalculationOutcome $consolidatedCalculationOutcome)
    {
        $this->consolidatedCalculationOutcome = $consolidatedCalculationOutcome;
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    protected function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return ConsolidatedCalculationOutcome
     */
    protected function getConsolidatedCalculationOutcome(): ConsolidatedCalculationOutcome
    {
        return $this->consolidatedCalculationOutcome;
    }
}
