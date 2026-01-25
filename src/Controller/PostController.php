<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController
{
    public function list(): JsonResponse
    {
        return new JsonResponse([
            ['id' => 1, 'userId' => 1, 'title' => 'First Post', 'body' => 'Hello world'],
            ['id' => 2, 'userId' => 1, 'title' => 'Second Post', 'body' => 'Another post'],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return new JsonResponse(['id' => $id, 'userId' => 1, 'title' => 'Sample Post', 'body' => 'Post body']);
    }

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $errors = [];
        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }
        if (empty($data['body'])) {
            $errors['body'] = 'Body is required';
        }
        if (empty($data['userId'])) {
            $errors['userId'] = 'User ID is required';
        }

        if (!empty($errors)) {
            return new JsonResponse([
                'code' => 'VALIDATION_ERROR',
                'message' => 'Validation failed',
                'details' => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $data['id'] = 1;
        return new JsonResponse($data, Response::HTTP_CREATED);
    }
}
