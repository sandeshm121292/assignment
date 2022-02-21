<?php declare(strict_types=1);

namespace Assignment\Formatter;

use Assignment\Order\Resource\OrderResource;

abstract class AbstractFormatter
{

    /**
     * @var OrderResource
     */
    protected $resource;

    /**
     * @param OrderResource $resource
     */
    public function __construct(OrderResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return OrderResource
     */
    protected function getResource(): OrderResource
    {
        return $this->resource;
    }
}
