<?php

namespace Assignment\Order;

interface OrderInterface
{

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;
}
