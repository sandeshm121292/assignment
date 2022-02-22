<?php declare(strict_types=1);

namespace Assignment\Formatter;

use Assignment\Calculator\ConsolidatedCalculationOutcome;
use Assignment\Formatter\Exception\InvalidInvoiceFormatException;

class InvoiceFormatterProvider
{

    /**
     * @param string $invoiceFormat
     * @param string $orderId
     * @param ConsolidatedCalculationOutcome $consolidatedCalculationOutcome
     * @return FormatterInterface
     * @throws InvalidInvoiceFormatException
     */
    public function getFormatter(string $invoiceFormat, string $orderId, ConsolidatedCalculationOutcome $consolidatedCalculationOutcome): FormatterInterface
    {
        switch ($invoiceFormat) {
            case 'json':
                return new JsonFormatter($orderId, $consolidatedCalculationOutcome);
            case 'html':
                return new HtmlFormatter($orderId, $consolidatedCalculationOutcome);
            default:
                throw InvalidInvoiceFormatException::create($invoiceFormat);
        }
    }
}
