<?php declare(strict_types=1);

namespace Assignment\Formatter;

use Exception;

class InvoiceFormatterProvider
{

    /**
     * @param $invoiceFormat
     * @param $resource
     * @return FormatterInterface
     * @throws Exception
     */
    public function getFormatter($invoiceFormat, $resource): FormatterInterface
    {
        switch ($invoiceFormat) {
            case 'json':
                return new JsonFormatter($resource);
            case 'html':
                return new HtmlFormatter($resource);
            default:
                throw new Exception('Invalid invoice format');
        }
    }
}
