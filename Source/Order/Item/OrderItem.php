<?php declare(strict_types=1);

namespace Assignment\Order\Item;

use Assignment\Order\Exception\CannotStoreOrderException;
use Exception;
use PDO;

final class OrderItem implements OrderItemInterface, OrderItemStorageInterface
{

    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $productId;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $basePrice;

    /**
     * @var float
     */
    private $taxPrice;

    /**
     * @var float
     */
    private $totalPrice;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var PDO
     */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @inheritDoc
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @inheritDoc
     */
    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    /**
     * @inheritDoc
     */
    public function getTaxPrice(): float
    {
        return $this->taxPrice;
    }

    /**
     * @inheritDoc
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @inheritDoc
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $orderId
     * @return OrderItem
     */
    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @param string $productId
     * @return OrderItem
     */
    public function setProductId(string $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @param int $quantity
     * @return OrderItem
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param float $basePrice
     * @return OrderItem
     */
    public function setBasePrice(float $basePrice): self
    {
        $this->basePrice = $basePrice;
        return $this;
    }

    /**
     * @param float $taxPrice
     * @return OrderItem
     */
    public function setTaxPrice(float $taxPrice): self
    {
        $this->taxPrice = $taxPrice;
        return $this;
    }

    /**
     * @param float $totalPrice
     * @return OrderItem
     */
    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * @param string|null $email
     * @return OrderItem
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function store(): OrderItemInterface
    {
        try {
            /**
             * Hardcoded sql queries are old school, we should have some query builder library in reality
             */
            $query = <<<SQL
INSERT INTO `order_items` (`orderId`, `productId`, `quantity`, `basePrice`, `taxPrice`, `totalPrice`)
VALUES (:orderId, :productId, :quantity, :basePrice, :taxPrice, :totalPrice);        
SQL;

            $statement = $this->db->prepare($query);
            $statement->execute([
                ':orderId' => $this->getOrderId(),
                ':productId' => $this->getProductId(),
                ':quantity' => $this->getQuantity(),
                ':basePrice' => $this->getBasePrice(),
                ':taxPrice' => $this->getTaxPrice(),
                ':totalPrice' => $this->getTotalPrice(),
            ]);

            return $this;
        } catch (Exception $exception) {
            throw CannotStoreOrderException::create($exception);
        }
    }
}
