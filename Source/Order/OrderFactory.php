<?php declare(strict_types=1);

namespace Assignment\Order;

use Assignment\Calculator\CalculatedOutcome;
use Assignment\Database\DbFactory;
use Assignment\Database\Exception\DbConnectionException;
use Assignment\Order\Controller\Form\OrderForm;
use PDO;

class OrderFactory
{

    /**
     * @return Order
     * @throws DbConnectionException
     */
    public function createOrder(): Order
    {
        return new Order($this->createDb());
    }

    /**
     * @param CalculatedOutcome[] $calculatedOutcomes
     * @param OrderForm $form
     * @return OrderStorage
     */
    public function createOrderStorage(array $calculatedOutcomes, OrderForm $form): OrderStorage
    {
        return new OrderStorage($calculatedOutcomes, new OrderFactory(), $form);
    }

    /**
     * @return PDO
     * @throws DbConnectionException
     */
    private function createDb(): PDO
    {
        return (new DbFactory())->createPDO();
    }
}
