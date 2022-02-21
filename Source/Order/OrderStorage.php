<?php declare(strict_types=1);

namespace Assignment\Order;

use Assignment\Calculator\CalculatedOutcome;
use Assignment\Order\Controller\Form\OrderForm;
use Assignment\Order\Exception\CannotStoreOrderException;
use Exception;

class OrderStorage
{

    /**
     * @var CalculatedOutcome[]
     */
    private $calculatedOutcomes;

    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var OrderForm
     */
    private $form;

    /**
     * @param CalculatedOutcome[] $calculatedOutcomes
     */
    public function __construct(array $calculatedOutcomes, OrderFactory $orderFactory, OrderForm $form)
    {
        $this->calculatedOutcomes = $calculatedOutcomes;
        $this->orderFactory = $orderFactory;
        $this->form = $form;
    }

    /**
     * @return OrderInterface[]
     * @throws CannotStoreOrderException
     */
    public function storeCalculatedOrders(): array
    {
        try {
            $orders = [];
            $orderId = uniqid('order_');
            foreach ($this->calculatedOutcomes as $outcome) {
                $order = $this->orderFactory->createOrder();
                $order
                    ->setOrderId($orderId)
                    ->setProductId($outcome->getProductId())
                    ->setQuantity($outcome->getQuantity())
                    ->setBasePrice($outcome->getBasePrice())
                    ->setTaxPrice($outcome->getTaxPrice())
                    ->setTotalPrice($outcome->getTotalPrice())
                    ->setEmail($this->form->getEmail());
                $orders[] = $order->store();
            }
        } catch (Exception $exception) {
            throw CannotStoreOrderException::create($exception);
        }

        return $orders;
    }
}
