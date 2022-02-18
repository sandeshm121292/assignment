<?php declare(strict_types=1);

namespace Assignment\Database\Exception;

use Exception;

class DbConnectionException extends Exception
{
    /**
     * @param string $errorMessage
     * @return DbConnectionException
     */
    public static function error(string $errorMessage): DbConnectionException
    {
        return new self('Unable to connect to database : ' . $errorMessage);
    }
}
