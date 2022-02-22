<?php declare(strict_types=1);

namespace Assignment\Product;

use Assignment\Database\DbFactory;
use Assignment\Database\Exception\DbConnectionException;
use PDO;

final class ProductFactory
{

    /**
     * @return Product
     * @throws DbConnectionException
     */
    public function createProduct(): Product
    {
        return new Product($this->createDb());
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
