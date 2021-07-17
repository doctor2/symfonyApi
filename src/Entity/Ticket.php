<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Airport::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $departureAirport;

    /**
     * @ORM\Column(type="datetime")
     */
    private $departureTime;

    /**
     * @ORM\ManyToOne(targetEntity=Airport::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $arrivalAirport;

    /**
     * @ORM\Column(type="datetime")
     */
    private $arrivalTime;

    public function __construct(
        Airport $departureAirport,
        DateTime $departureTime,
        Airport $arrivalAirport,
        DateTime $arrivalTime
    ) {
        $this->departureAirport = $departureAirport;
        $this->departureTime = $departureTime;
        $this->arrivalAirport = $arrivalAirport;
        $this->arrivalTime = $arrivalTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureAirport(): ?Airport
    {
        return $this->departureAirport;
    }

    public function setDepartureAirport(Airport $departureAirport): self
    {
        $this->departureAirport = $departureAirport;

        return $this;
    }

    public function getDepartureTime(): ?DateTime
    {
        return $this->departureTime;
    }

    public function setDepartureTime(DateTime $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getArrivalAirport(): ?Airport
    {
        return $this->arrivalAirport;
    }

    public function setArrivalAirport(Airport $arrivalAirport): self
    {
        $this->arrivalAirport = $arrivalAirport;

        return $this;
    }

    public function getArrivalTime(): ?DateTime
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(DateTime $arrivalTime): self
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }
}
