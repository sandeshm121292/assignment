<?php

namespace Assignment\Order\Controller\Form;

use Assignment\Order\Controller\Form\Exception\EmptyRequestBodyException;
use Assignment\Order\Controller\Form\Exception\FormValidationFailedException;
use Assignment\Product\ProductQuantityReference;

interface OrderFormInterface
{

    /**
     * @return ProductQuantityReference[]
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getProducts(): array;

    /**
     * @return string
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getCountry(): string;

    /**
     * @return string
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getInvoiceFormat(): string;

    /**
     * @return bool
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function isSendEmail(): bool;

    /**
     * @return string|null
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getEmail(): ?string;
}
