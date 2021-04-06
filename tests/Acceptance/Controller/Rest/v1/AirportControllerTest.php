<?php

namespace Tests\Acceptance\Controller\Rest\v1;

use Symfony\Component\HttpFoundation\Response;
use Tests\Acceptance\Controller\Rest\ApiTestCase;
use Tests\DataFixtures\ORM\LoadAirportVnukovo;

class AirportControllerTest extends ApiTestCase
{
    public function testGetAirports(): void
    {
        $referenceRepository = $this->loadFixtures([
            LoadAirportVnukovo::class,
        ])->getReferenceRepository();

        $referenceRepository->getReference(LoadAirportVnukovo::REFERENCE_NAME);

        $this->client->request('GET', '/api/v1/airport');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertCount(4, $content);
        $this->assertEquals(1, $content['current_page_number']);
        $this->assertEquals(10, $content['num_items_per_page']);
        $this->assertEquals(1, $content['total_count']);
        $this->assertCount(1, $content['items']);

        $this->assertAirport($content['items'][0]);
    }

    public function testErrorWhileCreatingAirport(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/airport',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'timezone' => 'Europe',
            ])
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertArrayHasKey('code', $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertArrayHasKey('errors', $content);
    }

    public function testCreateAirport(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/airport',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'new Airport',
                'timezone' => 'Europe/Skopje',
            ])
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $this->assertAirport($content);
    }

    private function assertAirport(array $airport): void
    {
        $this->assertCount(4, $airport);
        $this->assertArrayHasKey('id', $airport);
        $this->assertArrayHasKey('name', $airport);
        $this->assertArrayHasKey('timezone', $airport);
        $this->assertArrayHasKey('departure_tickets', $airport);
    }
}
