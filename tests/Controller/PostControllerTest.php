<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testListPostsReturnsArrayOfPosts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/posts');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertCount(2, $response);

        // Check first post structure
        $post = $response[0];
        $this->assertArrayHasKey('id', $post);
        $this->assertArrayHasKey('userId', $post);
        $this->assertArrayHasKey('title', $post);
        $this->assertArrayHasKey('body', $post);
        $this->assertEquals(1, $post['id']);
        $this->assertEquals(1, $post['userId']);
        $this->assertEquals('First Post', $post['title']);
        $this->assertEquals('Hello world', $post['body']);
    }

    public function testShowPostReturnsPostData(): void
    {
        $client = static::createClient();

        $client->request('GET', '/posts/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('userId', $response);
        $this->assertArrayHasKey('title', $response);
        $this->assertArrayHasKey('body', $response);
        $this->assertEquals(1, $response['id']);
        $this->assertEquals(1, $response['userId']);
    }

    public function testShowPostWithDifferentIdReturnsCorrectId(): void
    {
        $client = static::createClient();

        $client->request('GET', '/posts/99');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(99, $response['id']);
    }

    public function testCreatePostReturns201WithCreatedPost(): void
    {
        $client = static::createClient();

        $postData = [
            'userId' => 1,
            'title' => 'New Post',
            'body' => 'This is a new post body',
        ];

        $client->request(
            'POST',
            '/posts',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($postData)
        );

        $this->assertResponseStatusCodeSame(201);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('userId', $response);
        $this->assertArrayHasKey('title', $response);
        $this->assertArrayHasKey('body', $response);
        $this->assertEquals(1, $response['id']);
        $this->assertEquals(1, $response['userId']);
        $this->assertEquals('New Post', $response['title']);
        $this->assertEquals('This is a new post body', $response['body']);
    }

    public function testCreatePostWithEmptyBodyReturns400(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/posts',
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
        $this->assertArrayHasKey('title', $response['details']);
        $this->assertArrayHasKey('body', $response['details']);
        $this->assertArrayHasKey('userId', $response['details']);
    }

    public function testCreatePostWithPartialDataReturns400(): void
    {
        $client = static::createClient();

        $postData = [
            'title' => 'Only Title',
        ];

        $client->request(
            'POST',
            '/posts',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($postData)
        );

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('code', $response);
        $this->assertEquals('VALIDATION_ERROR', $response['code']);
        $this->assertArrayHasKey('details', $response);
        $this->assertArrayHasKey('body', $response['details']);
        $this->assertArrayHasKey('userId', $response['details']);
        $this->assertArrayNotHasKey('title', $response['details']);
    }

    public function testPostsEndpointRejectsInvalidMethod(): void
    {
        $client = static::createClient();

        $client->request('PUT', '/posts');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testPostsEndpointRejectsDeleteMethod(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/posts');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testPostsShowEndpointRejectsPostMethod(): void
    {
        $client = static::createClient();

        $client->request('POST', '/posts/1');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testPostsEndpointReturnsJsonContentType(): void
    {
        $client = static::createClient();

        $client->request('GET', '/posts');

        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testSecondPostInListHasCorrectData(): void
    {
        $client = static::createClient();

        $client->request('GET', '/posts');

        $response = json_decode($client->getResponse()->getContent(), true);
        $secondPost = $response[1];

        $this->assertEquals(2, $secondPost['id']);
        $this->assertEquals(1, $secondPost['userId']);
        $this->assertEquals('Second Post', $secondPost['title']);
        $this->assertEquals('Another post', $secondPost['body']);
    }
}
