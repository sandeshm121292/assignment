<?php

namespace Assignment\Order\Item;

use Assignment\Order\Exception\CannotStoreOrderException;

interface OrderItemStorageInterface
{

    /**
     * @return OrderItemInterface
     * @throws CannotStoreOrderException
     */
    public function store(): OrderItemInterface;
}
