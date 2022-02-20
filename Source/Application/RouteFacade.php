<?php declare(strict_types=1);

namespace Assignment\Application;

use Assignment\Order\Controller\OrderController;

final class RouteFacade
{
    private const POST = 'POST';

    /**
     * @return array
     */
    public static function getRoutes(): array
    {
        return [
            [
                self::POST,
                '/api/invoice/create',
                [OrderController::class, 'create'],
            ],
        ];
    }
}
