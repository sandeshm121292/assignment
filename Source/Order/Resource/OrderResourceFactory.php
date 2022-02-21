<?php declare(strict_types=1);

namespace Assignment\Order\Resource;

final class OrderResourceFactory
{

    /**
     * @param array $orders
     * @return OrderResource
     */
    public function createOrderResource(array $orders): OrderResource
    {
        return new OrderResource($orders);
    }
}
