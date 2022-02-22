<?php declare(strict_types=1);

namespace Assignment\Database;

use Assignment\Database\Exception\DbConnectionException;
use PDO;

final class DbFactory
{
    /**
     * @var string
     */
    private const DB_NAME = 'lamia_assignment';

    /**
     * @var string
     */
    private const DB_USER = 'root';

    /**
     * @var string
     */
    private const DB_PASSWORD = 'root';

    /**
     * @var string
     */
    private const DB_HOST = 'mysql';

    /**
     * @var PDO
     */
    private $db;

    /**
     * @return PDO
     * @throws DbConnectionException
     *
     * This is to keep the solution as simple as possible, in reality we would have some DbAdapterInterface which already return queryable methods.
     */
    public function createPDO(): PDO
    {
        if (null === $this->db) {
            $this->db = (new DbConnector(self::DB_NAME, self::DB_USER, self::DB_PASSWORD, self::DB_HOST))->connect();
        }

        return $this->db;
    }
}
