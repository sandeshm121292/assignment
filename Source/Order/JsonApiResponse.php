<?php
declare(strict_types=1);

namespace Assignment\Order;

use Exception;
use Psr\Http\Message\StreamInterface;
use Laminas\Diactoros\Exception\InvalidArgumentException;
use Laminas\Diactoros\Response\TextResponse;

class JsonApiResponse extends TextResponse
{
    private const HEADER_CONTENT_TYPE   = 'content-type';
    private const CONTENT_TYPE_API_JSON = 'application/vnd.api+json';

    /**
     * @param string|StreamInterface $body
     * @param int                    $status
     * @param array                  $headers
     *
     * @throws Exception|InvalidArgumentException
     */
    public function __construct(string $body, int $status = 200, array $headers = [])
    {
        $headers[self::HEADER_CONTENT_TYPE] = self::CONTENT_TYPE_API_JSON;

        parent::__construct($body, $status, $headers);
    }
}
