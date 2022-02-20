<?php

namespace Assignment\Order;

interface FormatterInterface
{


    /**
     * @return string
     */
    public function getFormattedResponse(): string;
}