<?php declare(strict_types=1);

namespace Assignment\Order\Exception;

use Exception;

final class CannotStoreOrderException extends Exception
{

    /**
     * @param Exception $previous
     * @return CannotStoreOrderException
     */
    public static function create(Exception $previous): CannotStoreOrderException
    {
        $message = sprintf('Cannot store order: %s', $previous->getMessage());
        return new CannotStoreOrderException(
            $message,
            500,
            $previous
        );
    }
}
