<?php

namespace Assignment\Order;

interface OrderStorageInterface
{

    /**
     * @return OrderInterface
     */
    public function store(): OrderInterface;
}
