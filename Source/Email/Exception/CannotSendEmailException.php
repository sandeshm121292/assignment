<?php declare(strict_types=1);

namespace Assignment\Email\Exception;

use Exception;

final class CannotSendEmailException extends Exception
{

    /**
     * @param string $email
     * @param Exception $previous
     * @return CannotSendEmailException
     */
    public static function create(string $email, Exception $previous): CannotSendEmailException
    {
        $message = sprintf('Cannot send email "%s": %s', $email, $previous->getMessage());
        return new CannotSendEmailException(
            $message,
            $previous->getCode(),
            $previous
        );
    }
}
