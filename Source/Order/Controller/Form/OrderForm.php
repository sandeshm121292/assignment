<?php declare(strict_types=1);

namespace Assignment\Order\Controller\Form;

use Assignment\Form\FormInterface;
use Assignment\Order\Controller\Form\Exception\EmptyRequestBodyException;
use Assignment\Order\Controller\Form\Exception\FormValidationFailedException;
use Assignment\Product\ProductQuantityReference;
use Psr\Http\Message\ServerRequestInterface;

class OrderForm implements FormInterface, OrderFormInterface
{
    const PRODUCTS = 'products';
    const COUNTRY = 'country';
    const INVOICE_FORMAT = 'invoiceFormat';
    const IS_SEND_EMAIL = 'isSendEmail';
    const EMAIL = 'email';

    private const ALLOWED_COUNTRY_CODES = [
        'FI',
        'FR',
        'DE',
        'PL',
    ];

    private const ALLOWED_INVOICE_FORMATS = [
        'json',
        'html'
    ];

    const ERROR_FIELD = 'field';
    const ERROR_MESSAGE = 'message';

    /**
     * @var ProductQuantityReference[]
     */
    private $products;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $invoiceFormat;

    /**
     * @var boolean
     */
    private $isSendEmail;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var boolean
     */
    private $isValidated = false;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return void
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function validate(): void
    {
        if (!$this->isValidated) {
            $bodyParams = $this->getRequestBodyAsParsedJson();

            if ([] === $bodyParams) {
                throw EmptyRequestBodyException::create('Empty request given');
            }

            $this->validateProducts($bodyParams);
            $this->validateCountryCode($bodyParams);
            $this->validateInvoiceFormat($bodyParams);
            $this->validateEmail($bodyParams);

            if ($this->hasErrors()) {
                throw FormValidationFailedException::create($this->generateErrorMessage());
            }

            $this->isValidated = true;
        }
    }

    /**
     * @return ProductQuantityReference[]
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getProducts(): array
    {
        $this->validate();
        return $this->products;
    }

    /**
     * @return string
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getCountry(): string
    {
        $this->validate();
        return $this->country;
    }

    /**
     * @return string
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getInvoiceFormat(): string
    {
        $this->validate();
        return $this->invoiceFormat;
    }

    /**
     * @return bool
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function isSendEmail(): bool
    {
        $this->validate();
        return $this->isSendEmail;
    }

    /**
     * @return string|null
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    public function getEmail(): ?string
    {
        $this->validate();
        return $this->email;
    }

    /**
     * @return array
     * @throws FormValidationFailedException
     */
    private function getRequestBodyAsParsedJson(): array
    {
        $parsedBody = json_decode($this->request->getBody()->getContents(), true);

        if (null === $parsedBody) {
            throw FormValidationFailedException::create('Unable to parse request body');
        }

        return $parsedBody;
    }

    /**
     * @param string $field
     * @param string $message
     */
    private function addError(string $field, string $message): void
    {
        $this->errors[] = [
            self::ERROR_FIELD => $field,
            self::ERROR_MESSAGE => $message,
        ];
    }

    /**
     * @return bool
     */
    private function hasErrors(): bool
    {
        return [] !== $this->errors;
    }

    /**
     * @return string
     */
    private function generateErrorMessage(): string
    {
        $errorMessages = [];

        foreach ($this->errors as $error) {
            $errorMessages[] = sprintf(
                '%s: %s',
                $error[self::ERROR_FIELD],
                $error[self::ERROR_MESSAGE]
            );
        }

        return implode(', ', $errorMessages);
    }

    /**
     * @param array $bodyParams
     * @return void
     */
    private function validateProducts(array $bodyParams): void
    {
        $products = $bodyParams[self::PRODUCTS];

        if ([] === $products) {
            $this->addError(self::PRODUCTS, 'No products given');
        }

        $this->products = $this->createProductQuantityReferences($products);
    }

    /**
     * @param array $bodyParams
     * @return void
     */
    private function validateCountryCode(array $bodyParams): void
    {
        $country = $bodyParams[self::COUNTRY];

        if (null === $country || !in_array($country, self::ALLOWED_COUNTRY_CODES)) {
            $this->addError(self::COUNTRY, 'Invalid/missing country code');
        }

        $this->country = $bodyParams[self::COUNTRY];
    }

    /**
     * @param array $bodyParams
     * @return void
     */
    private function validateInvoiceFormat(array $bodyParams): void
    {
        $invoiceFormat = $bodyParams[self::INVOICE_FORMAT];

        if (null === $invoiceFormat || !in_array($invoiceFormat, self::ALLOWED_INVOICE_FORMATS)) {
            $this->addError(self::COUNTRY, 'Invalid/missing invoice format');
        }

        $this->invoiceFormat = $bodyParams[self::INVOICE_FORMAT];
    }

    /**
     * @param array $bodyParams
     *
     * @return void
     */
    private function validateEmail(array $bodyParams): void
    {
        $isSendEmail = $bodyParams[self::IS_SEND_EMAIL];

        if (!is_bool($isSendEmail)) {
            $this->addError(self::IS_SEND_EMAIL, 'Invalid parameter');
            return;
        }

        $fieldEmail = $bodyParams[self::EMAIL];
        if (true === $isSendEmail && $fieldEmail === null) {
            $this->addError(self::EMAIL, 'No email address has been provided to send the invoice as email');
            return;
        }

        if (true === $isSendEmail && !filter_var($fieldEmail, FILTER_VALIDATE_EMAIL)) {
            $this->addError(self::EMAIL, 'Invalid email address');
        }

        $this->isSendEmail = $bodyParams[self::IS_SEND_EMAIL];
        $this->email = $bodyParams[self::EMAIL];
    }

    /**
     * @param $products
     * @return ProductQuantityReference[]
     */
    private function createProductQuantityReferences($products): array
    {
        $productQuantityReferences = [];

        foreach ($products as $product) {
            $productQuantityReferences[] = new ProductQuantityReference($product['productId'], $product['quantity']);
        }

        return $productQuantityReferences;
    }
}
