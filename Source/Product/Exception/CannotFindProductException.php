<?php declare(strict_types=1);

namespace Assignment\Product\Exception;

use Exception;

final class CannotFindProductException extends Exception
{
    /**
     * @param string $id
     * @param string $countryCode
     * @param Exception $previous
     * @return CannotFindProductException
     */
    public static function create(string $id, string $countryCode, Exception $previous): CannotFindProductException
    {
        $message = sprintf('Cannot find product with id "%s" country code "%s": %s', $id, $countryCode, $previous->getMessage());
        return new CannotFindProductException(
            $message,
            $previous->getCode(),
            $previous
        );
    }
}
