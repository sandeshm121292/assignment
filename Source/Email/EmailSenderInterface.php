<?php declare(strict_types=1);

namespace Assignment\Email;

use Assignment\Email\Exception\CannotSendEmailException;

interface EmailSenderInterface
{

    /**
     * @return void
     * @throws CannotSendEmailException
     */
    public function send(): void;
}
