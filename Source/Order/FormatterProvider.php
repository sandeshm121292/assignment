<?php declare(strict_types=1);

namespace Assignment\Order;

use Exception;

class FormatterProvider
{

    /**
     * @param $invoiceFormat
     * @param $resource
     * @return FormatterInterface
     * @throws Exception
     */
    public function getFormatter($invoiceFormat, $isSendEmail, $resource): FormatterInterface
    {
        switch ($invoiceFormat) {
            case 'json':
                return new JsonFormatter($resource, $isSendEmail);
            case 'html':
                return new HtmlFormatter($resource);
            default:
                throw new Exception('Invalid invoice format');
        }
    }
}
