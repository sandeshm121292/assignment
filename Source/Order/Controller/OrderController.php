<?php declare(strict_types=1);

namespace Assignment\Order\Controller;

use Assignment\Application\JsonApiResponse;
use Assignment\Calculator\CalculatedOutcome;
use Assignment\Calculator\Calculator;
use Assignment\Calculator\CalculatorFactory;
use Assignment\Database\Exception\DbConnectionException;
use Assignment\Email\EmailSenderFactory;
use Assignment\Email\EmailSenderInterface;
use Assignment\Formatter\FormatterInterface;
use Assignment\Formatter\InvoiceFormatterProvider;
use Assignment\Order\Controller\Form\Exception\EmptyRequestBodyException;
use Assignment\Order\Controller\Form\Exception\FormValidationFailedException;
use Assignment\Order\Controller\Form\OrderForm;
use Assignment\Order\Controller\Form\OrderFormFactory;
use Assignment\Order\Exception\CannotStoreOrderException;
use Assignment\Order\OrderFactory;
use Assignment\Order\OrderInterface;
use Assignment\Order\Resource\OrderResource;
use Assignment\Order\Resource\OrderResourceFactory;
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

            $calculatedOutcomes = $this->getCalculatedOutcomes($form);
            $orders = $this->storeCalculatedOrders($calculatedOutcomes, $form);
            $orderResource = $this->createOrderResource($orders);
            $formattedInvoice = $this->createInvoiceFormatter($form->getInvoiceFormat(), $orderResource)->getFormattedInvoice();

            if ($form->isSendEmail()) {
                $this->createEmailSender($form, $formattedInvoice)->send();
            }

            return new JsonApiResponse($formattedInvoice, 200);
        } catch (Exception $exception) {
            return $this->createJsonApiErrorResponse($exception);
        }
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
     * @param OrderResource $orderResource
     * @return FormatterInterface
     * @throws Exception
     */
    private function createInvoiceFormatter(string $getInvoiceFormat, OrderResource $orderResource): FormatterInterface
    {
        return (new InvoiceFormatterProvider())->getFormatter($getInvoiceFormat, $orderResource);
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

    /**
     * @param OrderInterface[] $orders
     * @return OrderResource
     */
    private function createOrderResource(array $orders): OrderResource
    {
        return (new OrderResourceFactory())->createOrderResource($orders);
    }

    /**
     * @param OrderForm $form
     * @return CalculatedOutcome[]
     * @throws DbConnectionException
     * @throws EmptyRequestBodyException
     * @throws FormValidationFailedException
     * @throws CannotFindProductException
     */
    private function getCalculatedOutcomes(OrderForm $form): array
    {
        return $this->createCalculator($form->getProducts(), $form->getCountry())->calculate();
    }

    /**
     * @param array $calculatedOutcomes
     * @param OrderForm $form
     * @return OrderInterface[]
     * @throws CannotStoreOrderException
     */
    private function storeCalculatedOrders(array $calculatedOutcomes, OrderForm $form): array
    {
        return (new OrderFactory())->createOrderStorage($calculatedOutcomes, $form)->storeCalculatedOrders();
    }
}
