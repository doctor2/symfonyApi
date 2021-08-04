<?php

namespace App\Message\Command\Ticket;

use Symfony\Component\Validator\Constraints as Assert;

final class TicketSearchMessage
{
    use TicketMessageTrait;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $departureTime;

    public function __construct(array $queryParams)
    {
        $this->departureAirportId = $queryParams['departureAirportId'] ?? null;
        $this->departureTime = $queryParams['departureTime'] ?? null;
        $this->arrivalAirportId = $queryParams['arrivalAirportId'] ?? null;
    }

    public function getDepartureTime(): string
    {
        return $this->departureTime;
    }
}
