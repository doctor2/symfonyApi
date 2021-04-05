<?php

namespace App\Service;

use App\Entity\Airport;
use App\Repository\AirportRepository;
use App\Repository\TicketRepository;
use DateTime;

class TicketSearch
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
    public function search(int $departureAirportId, int $arrivalAirportId, string $departureTime): array
    {
        $departureAirport = $this->airportRepository->find($departureAirportId);
        $arrivalAirport = $this->airportRepository->find($arrivalAirportId);
        $departureDate = new DateTime($departureTime);

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
    private function searchOneStopTickets(Airport $departureAirport, Airport $arrivalAirport, DateTime $departureDate, array $ticketIds): array
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
