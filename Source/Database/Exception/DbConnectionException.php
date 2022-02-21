<?php declare(strict_types=1);

namespace Assignment\Database\Exception;

use Exception;

final class DbConnectionException extends Exception
{
    /**
     * @param Exception $previous
     * @return DbConnectionException
     */
    public static function create(Exception $previous): DbConnectionException
    {
        $message = sprintf('Cannot connect to db: %s', $previous->getMessage());
        return new DbConnectionException(
            $message,
            $previous->getCode(),
            $previous
        );
    }
}
