<?php

namespace Assignment\Order;

use Assignment\Order\Exception\CannotStoreOrderException;

interface OrderStorageInterface
{

    /**
     * @return OrderInterface
     * @throws CannotStoreOrderException
     */
    public function store(): OrderInterface;
}
