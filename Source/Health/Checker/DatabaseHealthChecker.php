<?php declare(strict_types=1);

namespace Assignment\Health\Checker;

use PDO;
use Throwable;

class DatabaseHealthChecker
{
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
     * @return void
     * @throws Throwable
     */
    public function checkHealth(): void
    {
        $this->db->query('SELECT DATABASE()')->fetch();
    }
}
