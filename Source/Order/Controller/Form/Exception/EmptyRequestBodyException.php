<?php declare(strict_types=1);

namespace Assignment\Order\Controller\Form\Exception;

use Exception;

final class EmptyRequestBodyException extends Exception
{

    /**
     * @param string $message
     *
     * @return EmptyRequestBodyException
     */
    public static function create(string $message): self
    {
        return new self(
            $message,
            400
        );
    }
}
