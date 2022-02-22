<?php
declare(strict_types=1);

namespace Assignment\Application;

use Exception;
use Laminas\Diactoros\Exception\InvalidArgumentException;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\StreamInterface;

/**
 * I don't like this TBH, I ended up with issues using JsonResponse when handed strings as a body,
 * for example anything containing a forward slash is interpreted as a resource identifier.
 */
final class JsonApiResponse extends TextResponse
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
