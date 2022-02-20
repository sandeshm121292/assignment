<?php declare(strict_types=1);

use Assignment\Application\ApplicationFactory;

require_once __DIR__ . '/vendor/autoload.php';

try {
    (new ApplicationFactory())->createApplication()->run();
} catch (Throwable $exception) {
    http_response_code(500);
}

