<?php declare(strict_types=1);

namespace Assignment\Email;

use Assignment\Email\Exception\CannotSendEmailException;
use Exception;

final class EmailSender implements EmailSenderInterface
{

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string[]
     */
    private $header = [];

    /**
     * @param string $email
     * @param string $subject
     * @param string $body
     * @param array $header
     */
    public function __construct(string $email, string $subject, string $body, array $header = [])
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->body = $body;
        $this->header = $header;
    }

    /**
     * @inheritDoc
     */
    public function send(): void
    {
        try {
            mail($this->email, $this->subject, $this->body, $this->header);
        } catch (Exception $exception) {
            throw CannotSendEmailException::create($this->email, $exception);
        }
    }
}
