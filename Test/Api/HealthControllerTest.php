<?php
declare(strict_types=1);

namespace Assignment\Test\Api;

use Assignment\Test\Helper\ApiTestHelper;
use PHPUnit\Framework\TestCase;
use Throwable;

require_once __DIR__ . '/../../vendor/autoload.php';

class HealthControllerTest extends TestCase
{
    use ApiTestHelper;

    /**
     * @return void
     * @throws Throwable
     */
    public function testIndex(): void
    {
        $response = $this->dispatchGet('/api/assignment/health');

        self::assertSame(
            200,
            $response->getStatusCode()
        );
    }
}
