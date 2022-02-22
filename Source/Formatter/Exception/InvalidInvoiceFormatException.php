<?php declare(strict_types=1);

namespace Assignment\Formatter\Exception;

use Exception;

final class InvalidInvoiceFormatException extends Exception
{

    /**
     * @param string $invoiceFormat
     * @return InvalidInvoiceFormatException
     */
    public static function create(string $invoiceFormat): InvalidInvoiceFormatException
    {
        $message = sprintf('Invalid invoice "%s"', $invoiceFormat);
        return new InvalidInvoiceFormatException($message);
    }
}
