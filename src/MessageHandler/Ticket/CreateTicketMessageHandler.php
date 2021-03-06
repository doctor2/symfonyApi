<?php

namespace App\MessageHandler\Ticket;

use App\Entity\Airport;
use App\Entity\Ticket;
use App\Message\Ticket\CreateTicketMessage;
use App\Repository\AirportRepository;
use App\Repository\TicketRepository;
use DateTime;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateTicketMessageHandler implements MessageHandlerInterface
{
    private $ticketRepository;
    private $airportRepository;

    public function __construct(TicketRepository $ticketRepository, AirportRepository $airportRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->airportRepository = $airportRepository;
    }

    /** @phan-suppress PhanPossiblyNullTypeArgument */
    public function __invoke(CreateTicketMessage $message): Ticket
    {
        /** @var Airport $departureAirport */
        $departureAirport = $this->airportRepository->find($message->getDepartureAirportId());
        /** @var Airport $arrivalAirport */
        $arrivalAirport = $this->airportRepository->find($message->getArrivalAirportId());

        $ticket = new Ticket(
            $departureAirport,
            new DateTime($message->getDepartureTime()),
            $arrivalAirport,
            new DateTime($message->getArrivalTime())
        );

        $this->ticketRepository->save($ticket);

        return $ticket;
    }
}
