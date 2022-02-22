<?php declare(strict_types=1);

namespace Assignment\Calculator\Exception;

use Assignment\Product\Exception\CannotFindProductException;
use Exception;

final class CannotCalculateException extends Exception
{

    /**
     * @param Exception $previous
     * @return CannotFindProductException
     */
    public static function create(Exception $previous): CannotFindProductException
    {
        $message = sprintf('Cannot calculate product: %s', $previous->getMessage());
        return new CannotFindProductException(
            $message,
            $previous->getCode(),
            $previous
        );
    }
}
