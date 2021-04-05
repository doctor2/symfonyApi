<?php

namespace Tests\Acceptance\Controller\Rest\v1;

use Symfony\Component\HttpFoundation\Response;
use Tests\Acceptance\Controller\Rest\ApiTestCase;
use Tests\DataFixtures\ORM\LoadAirportDomodedovo;
use Tests\DataFixtures\ORM\LoadAirportVnukovo;

class TicketControllerTest extends ApiTestCase
{
    public function testErrorWhileCreatingTicket(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/ticket',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'departureTime' => '2012-04-21 18:25',
            ])
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertArrayHasKey('code', $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertArrayHasKey('errors', $content);
        $this->assertCount(4, $content['errors']);
    }

    public function testCreateAirport(): void
    {
        $referenceRepository = $this->loadFixtures([
            LoadAirportVnukovo::class,
            LoadAirportDomodedovo::class,
        ])->getReferenceRepository();

        $airportVnukovo = $referenceRepository->getReference(LoadAirportVnukovo::REFERENCE_NAME);
        $airportDomodedovo = $referenceRepository->getReference(LoadAirportDomodedovo::REFERENCE_NAME);

        $this->client->request(
            'POST',
            '/api/v1/ticket',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'departureAirportId' => $airportVnukovo->getId(),
                'departureTime' => '2012-04-21 18:25:43',
                'arrivalAirportId' => $airportDomodedovo->getId(),
                'arrivalTime' => '2012-04-21 19:25:43',
            ])
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $this->assertTicket($content);
    }

    private function assertTicket(array $ticket): void
    {
        $this->assertCount(5, $ticket);
        $this->assertArrayHasKey('id', $ticket);
        $this->assertArrayHasKey('departure_airport', $ticket);
        $this->assertArrayHasKey('departure_time', $ticket);
        $this->assertArrayHasKey('arrival_airport', $ticket);
        $this->assertArrayHasKey('arrival_time', $ticket);
    }
}
