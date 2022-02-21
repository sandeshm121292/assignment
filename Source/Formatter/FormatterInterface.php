<?php

namespace Assignment\Formatter;

interface FormatterInterface
{

    /**
     * @return string
     */
    public function getFormattedInvoice(): string;
}
