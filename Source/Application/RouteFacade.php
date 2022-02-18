<?php declare(strict_types=1);

namespace Assignment\Application;

use Assignment\Health\Controller\HealthController;

final class RouteFacade
{
    private const POST = 'POST';
    private const GET = 'GET';

    /**
     * @return array
     */
    public static function getRoutes(): array
    {
        return [
            [
                self::GET,
                '/api/assignment/health',
                [HealthController::class, 'index'],
            ],
        ];
    }
}
