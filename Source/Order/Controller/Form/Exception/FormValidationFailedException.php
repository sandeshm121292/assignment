<?php declare(strict_types=1);

namespace Assignment\Order\Controller\Form\Exception;

use Exception;

class FormValidationFailedException extends Exception
{

    /**
     * @param string $message
     *
     * @return FormValidationFailedException
     */
    public static function create(string $message): self
    {
        return new self(
            $message,
            400
        );
    }
}
