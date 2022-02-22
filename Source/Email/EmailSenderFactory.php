<?php declare(strict_types=1);

namespace Assignment\Email;

final class EmailSenderFactory
{

    /**
     * @param string $email
     * @param string $subject
     * @param string $body
     * @param array $header
     * @return EmailSenderInterface
     */
    public function createEmailSender(string $email, string $subject, string $body, array $header = []): EmailSenderInterface
    {
        return new EmailSender($email, $subject, $body, $header);
    }
}
