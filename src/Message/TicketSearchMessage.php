<?php

namespace App\Message;

use App\Doctrine\Constraint\EntityExists\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final class TicketSearchMessage
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     *
     * @EntityExists(message="Аэропорт отправления не найден.", entityClass="\App\Entity\Airport")
     */
    private $departureAirportId;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $departureTime;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     *
     * @EntityExists(message="Аэропорт прибытия не найден.", entityClass="\App\Entity\Airport")
     */
    private $arrivalAirportId;

    public function __construct(array $queryParams)
    {
        $this->departureAirportId = $queryParams['departureAirportId'] ?? null;
        $this->departureTime = $queryParams['departureTime'] ?? null;
        $this->arrivalAirportId = $queryParams['arrivalAirportId'] ?? null;
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
