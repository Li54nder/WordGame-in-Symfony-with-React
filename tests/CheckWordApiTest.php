<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CheckWordApiTest extends WebTestCase
{
    public function testCheckWordEndpoint(): void
    {
        // This word needs to exist in the DB
        $word_for_test = "test";
        // Score for this word needs to be  5 
        // (t, e, s = 3 (+2 because of "almost palindrome" [test -> tet]))
        $expected_score = 5;

        $client = static::createClient();
        $client->catchExceptions(false);

        $requestData = [
            'word' => $word_for_test
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

        $this->assertSame($expected_score, $data['score']);
    }
}