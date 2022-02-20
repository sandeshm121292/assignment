<?php declare(strict_types=1);

namespace Assignment\Product\Exception;

use Exception;

final class MissingProductException extends Exception
{
    public static function create(string $id, string $countryCode): self
    {
        $message = sprintf('Product with id "%s" for country "%s" does not exists', $id, $countryCode);

        return new MissingProductException($message);
    }
}