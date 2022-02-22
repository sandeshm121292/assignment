<?php

namespace Assignment\Order\Item;

interface OrderItemInterface
{

    /**
     * @return string
     */
    public function getOrderId(): string;

    /**
     * @return string
     */
    public function getProductId(): string;

    /**
     * @return int
     */
    public function getQuantity(): int;

    /**
     * @return float
     */
    public function getBasePrice(): float;

    /**
     * @return float
     */
    public function getTaxPrice(): float;

    /**
     * @return float
     */
    public function getTotalPrice(): float;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;
}
