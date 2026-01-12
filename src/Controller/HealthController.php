<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class HealthController
{
    public function index(): JsonResponse
    {
        return new JsonResponse(['status' => 'ok', 'version' => '0.1.0']);
    }

    public function ready(): JsonResponse
    {
        return new JsonResponse(['status' => 'ready', 'version' => '0.1.0']);
    }
}
