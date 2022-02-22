<?php declare(strict_types=1);

namespace Assignment\Test\Integration;

use Assignment\Database\DbFactory;
use Assignment\Database\Exception\DbConnectionException;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class BaseIntegrationTest extends TestCase
{

    /**
     * @var PDO
     */
    protected $db = null;

    /**
     * @return PDO
     * @throws DbConnectionException
     */
    public function getDb(): PDO
    {
        if (null === $this->db) {
            $this->db = $this->createPDO();
        }

        return $this->db;
    }

    /**
     * @param string $tableName
     * @param string $idField
     * @param string $id
     * @return void
     * @throws DbConnectionException
     */
    protected function clearTestData(string $tableName, string $idField, string $id)
    {
        $query = sprintf('DELETE from %s WHERE %s = :id', $tableName, $idField);

        $stm = $this->getDb()->prepare($query);
        $stm->execute([':id' => $id]);
    }

    /**
     * @param string $tableName
     * @param string $where
     * @param array $bindings
     * @return void
     * @throws DbConnectionException
     */
    protected function clearTestDataWhere(string $tableName, string $where, array $bindings)
    {
        $query = sprintf('DELETE from %s WHERE %s', $tableName, $where);

        $stm = $this->getDb()->prepare($query);
        $stm->execute($bindings);
    }

    /**
     * @return PDO
     * @throws DbConnectionException
     */
    private function createPDO(): PDO
    {
        return (new DbFactory())->createPDO();
    }

    /**
     * @param string $productId
     * @param float $price
     * @param string $countryCode
     * @param float $tax
     * @param string $title
     * @return void
     * @throws DbConnectionException
     */
    protected function storeProduct(string $productId, float $price, string $countryCode, float $tax, string $title = 'Irrelevant' ): void
    {
        $db = $this->getDb();

        $stm = $db->prepare("INSERT IGNORE INTO `products` (`id`, `title`, `price`) VALUES (?, ?, ?)");
        $stm->execute([
            $productId,
            $title,
            $price,
        ]);

        $stm = $db->prepare("INSERT INTO `product_taxes` (`productId`, `countryCode`, `tax`) VALUES (?, ?, ?)");
        $stm->execute([
            $productId,
            $countryCode,
            $tax,
        ]);
    }

    /**
     * @param string $productId
     * @param string $countryCode
     * @return void
     * @throws DbConnectionException
     */
    protected function clearProducts(string $productId, string $countryCode): void
    {
        $this->clearTestData('products', 'id', $productId);
        $this->clearTestDataWhere('product_taxes', sprintf('%s = ? AND %s = ?', 'productId', 'countryCode'), [$productId, $countryCode]);
    }
}
