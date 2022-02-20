<?php declare(strict_types=1);

namespace Assignment\Order\Controller;

use Assignment\Database\Exception\DbConnectionException;
use Assignment\Order\Calculator\Calculator;
use Assignment\Order\Calculator\CalculatorFactory;
use Assignment\Order\Controller\Form\Exception\FormValidationFailedException;
use Assignment\Order\Controller\Form\OrderForm;
use Assignment\Order\FormatterInterface;
use Assignment\Order\FormatterProvider;
use Assignment\Order\JsonApiResponse;
use Assignment\Order\Resource\OrderResource;
use Assignment\Product\Exception\CannotFindProductException;
use Exception;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class OrderController
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws FormValidationFailedException
     * @throws DbConnectionException
     * @throws CannotFindProductException
     * @throws Exception
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $form = new OrderForm($request);
        $form->validate();
        $calculatedOutCome = $this->createCalculator($form->getProducts(), $form->getCountry())->calculate();
        $isSendEmail = $form->isSendEmail();
        $response = $this->createFormatter($form->getInvoiceFormat(), $isSendEmail, $calculatedOutCome)->getFormattedResponse();

        if ($isSendEmail) {
            mail($form->getEmail(), 'Invoice', $response);
        }

        return new JsonApiResponse($response, 200);
    }

    /**
     * @param array $products
     * @param string $countryCode
     * @return Calculator
     * @throws DbConnectionException
     */
    private function createCalculator(array $products, string $countryCode): Calculator
    {
        return (new CalculatorFactory())->createCalculator($products, $countryCode);
    }

    /**
     * @param string $getInvoiceFormat
     * @param bool $isSendEmail
     * @param OrderResource $calculatedOutCome
     * @return FormatterInterface
     * @throws Exception
     */
    private function createFormatter(string $getInvoiceFormat, bool $isSendEmail, OrderResource $calculatedOutCome): FormatterInterface
    {
        return (new FormatterProvider())->getFormatter($getInvoiceFormat, $isSendEmail, $calculatedOutCome);
    }
}