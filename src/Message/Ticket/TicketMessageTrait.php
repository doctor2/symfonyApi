<?php

namespace App\Message\Ticket;

use App\Doctrine\Constraint\EntityExists\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

trait TicketMessageTrait
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
     * @Assert\Type(type="numeric")
     *
     * @EntityExists(message="Аэропорт прибытия не найден.", entityClass="\App\Entity\Airport")
     */
    private $arrivalAirportId;

    public function getArrivalAirportId(): int
    {
        return $this->arrivalAirportId;
    }

    public function getDepartureAirportId(): int
    {
        return $this->departureAirportId;
    }
}
