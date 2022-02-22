<?php declare(strict_types=1);

namespace Assignment\Order;

use Assignment\Order\Exception\CannotStoreOrderException;
use Exception;
use PDO;

/**
 * @see \Assignment\Test\Integration\Order\OrderTest
 */
final class Order implements OrderInterface, OrderStorageInterface
{

    /**
     * @var PDO
     */
    private $db;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Order
     */
    public function setId(string $id): Order
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Order
     */
    public function setEmail(?string $email): Order
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return OrderInterface
     * @throws CannotStoreOrderException
     */
    public function store(): OrderInterface
    {
        try {
            /**
             * Hardcoded sql queries are old school, we should have some query builder library in reality
             */
            $query = <<<SQL
INSERT INTO `orders` (`id`, `email`)
VALUES (:id, :email);        
SQL;

            $statement = $this->db->prepare($query);
            $statement->execute([
                ':id' => $this->getId(),
                ':email' => $this->getEmail(),
            ]);

            return $this;
        } catch (Exception $exception) {
            throw CannotStoreOrderException::create($exception);
        }
    }
}
