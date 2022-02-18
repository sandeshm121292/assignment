<?php declare(strict_types=1);

namespace Assignment\Health\Controller;

use Assignment\Database\DbConnector;
use Assignment\Health\Checker\DatabaseHealthChecker;
use Exception;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class HealthController
{

    /**
     * @throws Exception
     */
    public function index(): ResponseInterface
    {
        try {

            $db = new DbConnector('lamia_assignment', 'root', 'root', 'mysql');

            $databaseHealthChecker = new DatabaseHealthChecker($db->connect());
            $databaseHealthChecker->checkHealth();
            $response = new Response();
            $response->getBody()->write('OK');
            return $response;
        } catch (Throwable $e) {
            throw new Exception($e->getMessage(), 500, $e->getPrevious());
        }
    }
}
