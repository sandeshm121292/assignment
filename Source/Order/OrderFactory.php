<?php declare(strict_types=1);

namespace Assignment\Order;

use Assignment\Calculator\ConsolidatedCalculationOutcome;
use Assignment\Database\DbFactory;
use Assignment\Database\Exception\DbConnectionException;
use Assignment\Order\Controller\Form\OrderForm;
use Assignment\Order\Item\OrderItem;
use PDO;

final class OrderFactory
{

    /**
     * @var PDO
     */
    private $db;

    /**
     * @return Order
     * @throws DbConnectionException
     */
    public function createOrder(): Order
    {
        return new Order($this->createDb());
    }

    /**
     * @return OrderItem
     * @throws DbConnectionException
     */
    public function createOrderItem(): OrderItem
    {
        return new OrderItem($this->createDb());
    }

    /**
     * @param ConsolidatedCalculationOutcome $consolidatedCalculationOutcome
     * @param OrderForm $form
     * @return OrderStorage
     */
    public function createOrderStorage(ConsolidatedCalculationOutcome $consolidatedCalculationOutcome, OrderForm $form): OrderStorage
    {
        return new OrderStorage($consolidatedCalculationOutcome, new OrderFactory(), $form);
    }

    /**
     * @return PDO
     * @throws DbConnectionException
     */
    private function createDb(): PDO
    {
        if (null === $this->db) {
            $this->db = (new DbFactory())->createPDO();
        }

        return $this->db;
    }
}
