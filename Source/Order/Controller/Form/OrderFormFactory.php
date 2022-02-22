<?php declare(strict_types=1);

namespace Assignment\Order\Controller\Form;

use Psr\Http\Message\ServerRequestInterface;

final class OrderFormFactory
{

    /**
     * @param ServerRequestInterface $request
     * @return OrderForm
     */
    public function createOrderForm(ServerRequestInterface $request): OrderForm
    {
        return new OrderForm($request);
    }
}
