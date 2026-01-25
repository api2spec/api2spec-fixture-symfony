<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthControllerTest extends WebTestCase
{
    public function testHealthEndpointReturnsOkStatus(): void
    {
        $client = static::createClient();

        $client->request('GET', '/health');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('version', $response);
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('0.1.0', $response['version']);
    }

    public function testHealthReadyEndpointReturnsReadyStatus(): void
    {
        $client = static::createClient();

        $client->request('GET', '/health/ready');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('version', $response);
        $this->assertEquals('ready', $response['status']);
        $this->assertEquals('0.1.0', $response['version']);
    }

    public function testHealthEndpointReturnsJsonContentType(): void
    {
        $client = static::createClient();

        $client->request('GET', '/health');

        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testHealthEndpointRejectsPostMethod(): void
    {
        $client = static::createClient();

        $client->request('POST', '/health');

        $this->assertResponseStatusCodeSame(405);
    }
}
