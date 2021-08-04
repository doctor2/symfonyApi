<?php

namespace App\Message\Command\Ticket;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateTicketMessage
{
    use TicketMessageTrait;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $departureTime;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $arrivalTime;

    public function __construct(array $requestData)
    {
        $this->departureAirportId = $requestData['departureAirportId'] ?? null;
        $this->departureTime = $requestData['departureTime'] ?? null;
        $this->arrivalAirportId = $requestData['arrivalAirportId'] ?? null;
        $this->arrivalTime = $requestData['arrivalTime'] ?? null;
    }

    public function getDepartureTime(): string
    {
        return $this->departureTime;
    }

    public function getArrivalTime(): string
    {
        return $this->arrivalTime;
    }
}
