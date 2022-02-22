<?php declare(strict_types=1);

namespace Assignment\Test\Integration\Order;

use Assignment\Database\Exception\DbConnectionException;
use Assignment\Order\Exception\CannotStoreOrderException;
use Assignment\Order\Order;
use Assignment\Order\OrderFactory;
use Assignment\Order\OrderInterface;
use Assignment\Test\Integration\BaseIntegrationTest;
use Exception;
use PDO;

/**
 * @see \Assignment\Order\Order
 */
final class OrderTest extends BaseIntegrationTest
{
    /**
     * @return void
     * @throws DbConnectionException
     * @throws CannotStoreOrderException
     */
    public function testStore(): void
    {
        $id = 'Test-id';
        $email = 'Test-email';

        $this->clearTestData('orders', 'id', $id);

        $order = (new OrderFactory())->createOrder();
        $order->setId($id);
        $order->setEmail($email);
        $storedOrder = $order->store();

        self::assertInstanceOf(OrderInterface::class, $storedOrder);

        // let's check if the order is stored for real
        $actualStoredOrders = $this->loadActualStoredOrders($id);
        self::assertCount(1, $actualStoredOrders); // there can be one order per email ever
        self::assertSame($id, $actualStoredOrders[0]['id']);
        self::assertSame($email, $actualStoredOrders[0]['email']);
    }

    /**
     * @return void
     * @throws DbConnectionException
     * @throws CannotStoreOrderException
     */
    public function testStoreFailsOnDuplicate(): void
    {
        $id = 'Test-id';
        $email = 'Test-email';

        $this->clearTestData('orders', 'id', $id);

        $order = $this->createOrder();
        $order->setId($id);
        $order->setEmail($email);
        $order->store();

        try {
            $order = $this->createOrder();
            $order->setId($id);
            $order->setEmail($email);
            $order->store();
            self::fail(sprintf('%s was not thrown', CannotStoreOrderException::class));
        } catch (Exception $exception) {
            self::assertInstanceOf(CannotStoreOrderException::class, $exception);
        }

        // let's check if the order is stored for real
        $actualStoredOrders = $this->loadActualStoredOrders($id);
        self::assertCount(1, $actualStoredOrders); // there can be one order per email ever
    }

    /**
     * @return Order
     * @throws DbConnectionException
     */
    private function createOrder(): Order
    {
        return (new OrderFactory())->createOrder();
    }

    /**
     * @param string $id
     * @return array|false
     * @throws DbConnectionException
     */
    private function loadActualStoredOrders(string $id)
    {
        $stm = $this->getDb()->prepare('select * from orders where id = :id');
        $stm->execute([
            ':id' => $id,
        ]);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
