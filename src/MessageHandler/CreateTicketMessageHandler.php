<?php

namespace App\MessageHandler;

use App\Entity\Ticket;
use App\Message\CreateTicketMessage;
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

    public function __invoke(CreateTicketMessage $message): Ticket
    {
        $ticket = new Ticket();
        $ticket->setDepartureAirport($this->airportRepository->find($message->getDepartureAirportId()));
        $ticket->setDepartureTime(new DateTime($message->getDepartureTime()));
        $ticket->setArrivalAirport($this->airportRepository->find($message->getArrivalAirportId()));
        $ticket->setArrivalTime(new DateTime($message->getArrivalTime()));

        $this->ticketRepository->save($ticket);

        return $ticket;
    }
}