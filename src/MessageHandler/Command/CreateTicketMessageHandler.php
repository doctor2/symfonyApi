<?php

namespace App\MessageHandler\Command;

use App\Entity\Ticket;
use App\Message\Command\CreateTicketMessage;
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
        $ticket = new Ticket(
            $this->airportRepository->find($message->getDepartureAirportId()),
            new DateTime($message->getDepartureTime()),
            $this->airportRepository->find($message->getArrivalAirportId()),
            new DateTime($message->getArrivalTime())
        );

        $this->ticketRepository->save($ticket);

        return $ticket;
    }
}
