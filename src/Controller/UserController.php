<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function list(): JsonResponse
    {
        return new JsonResponse([
            ['id' => 1, 'name' => 'Alice', 'email' => 'alice@example.com'],
            ['id' => 2, 'name' => 'Bob', 'email' => 'bob@example.com'],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return new JsonResponse(['id' => $id, 'name' => 'Sample User', 'email' => 'user@example.com']);
    }

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $data['id'] = 1;
        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $data['id'] = $id;
        return new JsonResponse($data);
    }

    public function delete(int $id): Response
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function posts(int $userId): JsonResponse
    {
        return new JsonResponse([['id' => 1, 'userId' => $userId, 'title' => 'User Post', 'body' => 'Content']]);
    }
}
