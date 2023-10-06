<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CheckWordApiTest extends WebTestCase
{
    public function testCheckWordEndpoint(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $requestData = [
            'word' => 'test', // This word must exist in the DB
        ];

        $client->request(
            'POST',
            '/api/checkWord',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );
        
        $response = $client->getResponse();

        // Check the HTTP status code
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        // Assert that the response structure and data are as expected
        $this->assertArrayHasKey('word', $data);
        $this->assertArrayHasKey('score', $data);
    }
}
