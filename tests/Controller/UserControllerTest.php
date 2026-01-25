<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListUsersReturnsArrayOfUsers(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertCount(2, $response);

        // Check first user structure
        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('email', $response[0]);
        $this->assertEquals(1, $response[0]['id']);
        $this->assertEquals('Alice', $response[0]['name']);
        $this->assertEquals('alice@example.com', $response[0]['email']);
    }

    public function testShowUserReturnsUserData(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertEquals(1, $response['id']);
    }

    public function testShowUserWithDifferentIdReturnsCorrectId(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/42');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(42, $response['id']);
    }

    public function testCreateUserReturns201WithCreatedUser(): void
    {
        $client = static::createClient();

        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ];

        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        $this->assertResponseStatusCodeSame(201);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertEquals(1, $response['id']);
        $this->assertEquals('New User', $response['name']);
        $this->assertEquals('newuser@example.com', $response['email']);
    }

    public function testCreateUserWithEmptyBodyReturns400(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{}'
        );

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('code', $response);
        $this->assertEquals('VALIDATION_ERROR', $response['code']);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('details', $response);
        $this->assertArrayHasKey('name', $response['details']);
        $this->assertArrayHasKey('email', $response['details']);
    }

    public function testUpdateUserReturnsUpdatedData(): void
    {
        $client = static::createClient();

        $userData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $client->request(
            'PUT',
            '/users/5',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals(5, $response['id']);
        $this->assertEquals('Updated Name', $response['name']);
        $this->assertEquals('updated@example.com', $response['email']);
    }

    public function testDeleteUserReturns204NoContent(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/users/1');

        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($client->getResponse()->getContent());
    }

    public function testDeleteUserWithAnyIdReturns204(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/users/999');

        $this->assertResponseStatusCodeSame(204);
    }

    public function testGetUserPostsReturnsUserPostsArray(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/1/posts');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertCount(1, $response);

        $post = $response[0];
        $this->assertArrayHasKey('id', $post);
        $this->assertArrayHasKey('userId', $post);
        $this->assertArrayHasKey('title', $post);
        $this->assertArrayHasKey('body', $post);
        $this->assertEquals(1, $post['userId']);
    }

    public function testGetUserPostsWithDifferentUserIdReturnsCorrectUserId(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users/42/posts');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(42, $response[0]['userId']);
    }

    public function testUsersEndpointRejectsInvalidMethod(): void
    {
        $client = static::createClient();

        $client->request('PATCH', '/users');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testUsersEndpointReturnsJsonContentType(): void
    {
        $client = static::createClient();

        $client->request('GET', '/users');

        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
