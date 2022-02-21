<?php declare(strict_types=1);

namespace Assignment\Formatter;

class HtmlFormatter extends AbstractFormatter implements FormatterInterface
{

    /**
     * @return string
     */
    public function getFormattedInvoice(): string
    {
        // @todo
        // returns HTML page with calculated outcome
        return "<!DOCTYPE html>
<html>
    <head>
        <title>Invoice</title>
    </head>
    <body>
        <p>Foo invoice</p>
    </body>
</html>";
    }
}
