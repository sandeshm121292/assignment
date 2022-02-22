<?php declare(strict_types=1);

namespace Assignment\Application;

use Assignment\Order\Controller\OrderController;

final class RouteFacade
{
    private const POST = 'POST';

    /**
     * @link https://route.thephpleague.com/5.x/controllers/
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        /**
         * Want to add more endpoints? here's the place.
         * Format: method, path, handler (class, function)
         */
        return [
            [
                self::POST,
                '/api/order/create',
                [OrderController::class, 'create'],
            ],
        ];
    }
}
