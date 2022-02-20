<?php declare(strict_types=1);

namespace Assignment\Database;

use Assignment\Database\Exception\DbConnectionException;
use Exception;
use PDO;

class DbConnector
{
    /**
     * @var string
     */
    protected $dbName;

    /**
     * @var string
     */
    protected $dbUser;

    /**
     * @var string
     */
    protected $dbPass;

    /**
     * @var string
     */
    protected $dbHost;

    /**
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPass
     * @param string $dbHost
     */
    public function __construct(string $dbName, string $dbUser, string $dbPass, string $dbHost)
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbHost = $dbHost;
    }

    /**
     * @return PDO
     * @throws DbConnectionException
     */
    public function connect(): PDO
    {
        try {
            $pdo = new PDO(
                sprintf('mysql:host=%s;dbname=%s', $this->dbHost, $this->dbName),
                $this->dbUser,
                $this->dbPass
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (Exception $exception) {
            throw DbConnectionException::create($exception->getMessage());
        }
    }
}
