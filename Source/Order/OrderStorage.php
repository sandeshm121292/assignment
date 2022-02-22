<?php declare(strict_types=1);

namespace Assignment\Order;

use Assignment\Calculator\ConsolidatedCalculationOutcome;
use Assignment\Order\Controller\Form\OrderForm;
use Assignment\Order\Exception\CannotStoreOrderException;
use Assignment\Order\Item\OrderItemInterface;
use Exception;

class OrderStorage
{

    /**
     * @var ConsolidatedCalculationOutcome
     */
    private $consolidatedCalculationOutcome;

    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var OrderForm
     */
    private $form;

    /**
     * @param ConsolidatedCalculationOutcome $consolidatedCalculationOutcome
     * @param OrderFactory $orderFactory
     * @param OrderForm $form
     */
    public function __construct(ConsolidatedCalculationOutcome $consolidatedCalculationOutcome, OrderFactory $orderFactory, OrderForm $form)
    {
        $this->consolidatedCalculationOutcome = $consolidatedCalculationOutcome;
        $this->orderFactory = $orderFactory;
        $this->form = $form;
    }

    /**
     * @return OrderInterface
     * @throws CannotStoreOrderException
     */
    public function storeCalculatedOrder(): OrderInterface
    {
        try {
            $orderId = uniqid('order_');

            $order = $this->orderFactory->createOrder();
            $order
                ->setId($orderId)
                ->setEmail($this->form->getEmail())
                ->store();

            foreach ($this->consolidatedCalculationOutcome->getProductCalculationOutcomes() as $outcome) {
                $orderItem = $this->orderFactory->createOrderItem();
                $orderItem
                    ->setOrderId($order->getId())
                    ->setProductId($outcome->getProductId())
                    ->setQuantity($outcome->getQuantity())
                    ->setBasePrice($outcome->getBasePrice())
                    ->setTaxPrice($outcome->getTaxPrice())
                    ->setTotalPrice($outcome->getTotalPrice())
                    ->store();
            }
        } catch (Exception $exception) {
            throw CannotStoreOrderException::create($exception);
        }

        return $order;
    }
}
