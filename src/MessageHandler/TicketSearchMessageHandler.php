<?php

namespace App\MessageHandler;

use App\Entity\Airport;
use App\Entity\Ticket;
use App\Message\TicketSearchMessage;
use App\Repository\AirportRepository;
use App\Repository\TicketRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class TicketSearchMessageHandler implements MessageHandlerInterface
{
    private $ticketRepository;
    private $airportRepository;

    public function __construct(TicketRepository $ticketRepository, AirportRepository $airportRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->airportRepository = $airportRepository;
    }

    /**
     * @return mixed[]
     */
    public function __invoke(TicketSearchMessage $message): array
    {
        $departureAirport = $this->airportRepository->find($message->getDepartureAirportId());
        $arrivalAirport = $this->airportRepository->find($message->getArrivalAirportId());
        $departureDate = new DateTimeImmutable($message->getDepartureTime());

        if (empty($departureAirport) || empty($arrivalAirport)) {
            return [];
        }

        $tickets = $this->ticketRepository->findByAirportsAndDepartureTime($departureAirport, $arrivalAirport, $departureDate);

        return [
            'tickets' => $tickets,
            'oneStopTickets' => $this->searchOneStopTickets($departureAirport, $arrivalAirport, $departureDate, $this->getTicketIds($tickets)),
        ];
    }

    /**
     * @param int[] $ticketIds
     *
     * @return mixed[]
     */
    private function searchOneStopTickets(Airport $departureAirport, Airport $arrivalAirport, DateTimeInterface $departureDate, array $ticketIds): array
    {
        $ticketsFirst = $this->ticketRepository->findByDepartureAirportAndDepartureTime(
            $departureAirport,
            $departureDate,
            $ticketIds
        );

        $ticketsSecond = $this->ticketRepository->findByArrivalAirportAndDepartureTime(
            $arrivalAirport,
            $departureDate,
            $ticketIds
        );

        $oneStopTickets = [];

        foreach ($ticketsFirst as $ticketFirst) {
            foreach ($ticketsSecond as $ticketSecond) {
                if ($ticketFirst->getArrivalAirport() === $ticketSecond->getDepartureAirport()
                    && $ticketFirst->getArrivalTime() < $ticketSecond->getDepartureTime()
                ) {
                    $oneStopTickets[] = [
                        'first' => $ticketFirst,
                        'second' => $ticketSecond,
                    ];
                }

            }
        }

        return $oneStopTickets;
    }

    /**
     * @param Ticket[] $tickets
     *
     * @return int[]
     */
    private function getTicketIds(array $tickets): array
    {
        return array_map(function ($ticket) {
            return $ticket->getId();
        }, $tickets);
    }
}
