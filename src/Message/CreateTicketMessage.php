<?php

namespace App\Message;

use App\Doctrine\Constraint\EntityExists\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateTicketMessage
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     *
     * @EntityExists(message="Аэропорт отправления не найден.", entityClass="\App\Entity\Airport")
     */
    private $departureAirportId;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $departureTime;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     *
     * @EntityExists(message="Аэропорт прибытия не найден.", entityClass="\App\Entity\Airport")
     */
    private $arrivalAirportId;

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

    public function getArrivalTime(): string
    {
        return $this->arrivalTime;
    }

    public function getArrivalAirportId(): int
    {
        return $this->arrivalAirportId;
    }

    public function getDepartureTime(): string
    {
        return $this->departureTime;
    }

    public function getDepartureAirportId(): int
    {
        return $this->departureAirportId;
    }
}
