<?php declare(strict_types=1);

namespace Assignment\Order\Controller;

use Assignment\Application\JsonApiResponse;
use Assignment\Calculator\AbstractCalculator;
use Assignment\Calculator\CalculatorFactory;
use Assignment\Calculator\ConsolidatedCalculationOutcome;
use Assignment\Database\Exception\DbConnectionException;
use Assignment\Email\EmailSenderFactory;
use Assignment\Email\EmailSenderInterface;
use Assignment\Formatter\Exception\InvalidInvoiceFormatException;
use Assignment\Formatter\FormatterInterface;
use Assignment\Formatter\InvoiceFormatterProvider;
use Assignment\Order\Controller\Form\Exception\EmptyRequestBodyException;
use Assignment\Order\Controller\Form\Exception\FormValidationFailedException;
use Assignment\Order\Controller\Form\OrderForm;
use Assignment\Order\Controller\Form\OrderFormFactory;
use Assignment\Order\Exception\CannotStoreOrderException;
use Assignment\Order\OrderFactory;
use Assignment\Order\OrderInterface;
use Assignment\Product\Exception\CannotFindProductException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class OrderController
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $form = $this->createOrderForm($request);
            $form->validate();

            $calculatedOutcome = $this->getCalculatedOutcome($form);
            $order = $this->storeOrder($calculatedOutcome, $form);
            $formattedInvoice = $this->createInvoiceFormatter($form->getInvoiceFormat(), $order->getId(), $calculatedOutcome)->getFormattedInvoice();

            if ($form->isSendEmail()) {
                $this->createEmailSender($form, $formattedInvoice)->send();
            }

            return new JsonApiResponse($formattedInvoice, 200);
        } catch (Exception $exception) {
            return $this->createJsonApiErrorResponse($exception);
        }
    }

    /**
     * @param OrderForm $form
     * @return ConsolidatedCalculationOutcome
     * @throws CannotFindProductException
     * @throws DbConnectionException
     * @throws EmptyRequestBodyException
     * @throws FormValidationFailedException
     */
    private function getCalculatedOutcome(OrderForm $form): ConsolidatedCalculationOutcome
    {
        return (new CalculatorFactory())->createCalculator($form->getProducts(), $form->getCountry())->calculate();
    }

    /**
     * @param ConsolidatedCalculationOutcome $consolidatedCalculationOutcome
     * @param OrderForm $form
     * @return OrderInterface
     * @throws CannotStoreOrderException
     */
    private function storeOrder(ConsolidatedCalculationOutcome $consolidatedCalculationOutcome, OrderForm $form): OrderInterface
    {
        return (new OrderFactory())->createOrderStorage($consolidatedCalculationOutcome, $form)->storeCalculatedOrder();
    }

    /**
     * @param string $getInvoiceFormat
     * @param string $orderId
     * @param ConsolidatedCalculationOutcome $consolidatedCalculationOutcome
     * @return FormatterInterface
     * @throws InvalidInvoiceFormatException
     */
    private function createInvoiceFormatter(string $getInvoiceFormat, string $orderId, ConsolidatedCalculationOutcome $consolidatedCalculationOutcome): FormatterInterface
    {
        return (new InvoiceFormatterProvider())->getFormatter($getInvoiceFormat, $orderId, $consolidatedCalculationOutcome);
    }

    /**
     * @param OrderForm $form
     * @param string $response
     * @return EmailSenderInterface
     * @throws FormValidationFailedException
     * @throws EmptyRequestBodyException
     */
    private function createEmailSender(OrderForm $form, string $response): EmailSenderInterface
    {
        return (new EmailSenderFactory())->createEmailSender($form->getEmail(), 'Invoice', $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @return OrderForm
     */
    private function createOrderForm(ServerRequestInterface $request): OrderForm
    {
        return (new OrderFormFactory())->createOrderForm($request);
    }

    /**
     * @param Exception $exception
     * @return JsonApiResponse
     * @throws Exception
     */
    private function createJsonApiErrorResponse(Exception $exception): JsonApiResponse
    {
        return new JsonApiResponse(
            json_encode(
                [
                    "data" => [
                        "error" => $exception->getMessage(),
                        "code" => $exception->getCode(),
                    ]
                ]
            ),
            $exception->getCode()
        );
    }
}
