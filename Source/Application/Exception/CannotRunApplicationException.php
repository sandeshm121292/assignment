<?php declare(strict_types=1);

namespace Assignment\Application\Exception;

use Exception;

final class CannotRunApplicationException extends Exception
{

    /**
     * @param Exception $previous
     * @return CannotRunApplicationException
     */
    public static function create(Exception $previous): CannotRunApplicationException
    {
        $message = sprintf('Cannot run application: %s', $previous->getMessage());
        return new CannotRunApplicationException(
            $message,
            $previous->getCode(),
            $previous
        );
    }
}
