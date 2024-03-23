<?php

namespace App\Repository;

use App\Entity\Airport;
use App\Entity\Ticket;
use App\Repository\ExpressionFactory\TicketArrivalAirportEquals;
use App\Repository\ExpressionFactory\TicketDepartureAirportEquals;
use App\Repository\ExpressionFactory\TicketDepartureTimeAfterDate;
use App\Repository\ExpressionFactory\TicketDepartureTimeBeforeDatePlusThree;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('ticket');
    }

    public function findByAirportsAndDepartureTime(Airport $departureAirport, Airport $arrivalAirport, DateTime $departureTime): array
    {
        return $this->createQueryBuilder('ticket')
            ->andWhere(TicketDepartureAirportEquals::create('ticket',$departureAirport))
            ->andWhere(TicketArrivalAirportEquals::create('ticket',$arrivalAirport))
            ->andWhere(TicketDepartureTimeAfterDate::create('ticket', $departureTime))
            ->andWhere(TicketDepartureTimeBeforeDatePlusThree::create('ticket', $departureTime))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByDepartureAirportAndDepartureTime(Airport $departureAirport, DateTime $departureTime, array $ticketIds): array
    {
        $queryBuilder = $this->createQueryBuilder('ticket');
        $queryBuilder
            ->where(TicketDepartureAirportEquals::create('ticket',$departureAirport))
            ->andWhere(TicketDepartureTimeAfterDate::create('ticket', $departureTime))
            ->andWhere(TicketDepartureTimeBeforeDatePlusThree::create('ticket', $departureTime))
            ;

        if ($ticketIds) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->notIn('ticket.id', $ticketIds)
            );
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByArrivalAirportAndDepartureTime(Airport $arrivalAirport, DateTime $departureTime, array $ticketIds): array
    {
        $queryBuilder = $this->createQueryBuilder('ticket');
        $queryBuilder
            ->where(TicketArrivalAirportEquals::create('ticket',$arrivalAirport))
            ->andWhere(TicketDepartureTimeAfterDate::create('ticket', $departureTime))
            ->andWhere(TicketDepartureTimeBeforeDatePlusThree::create('ticket', $departureTime));

        if ($ticketIds) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->notIn('ticket.id', $ticketIds)
            );
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function save(Ticket $ticket): void
    {
        $this->getEntityManager()->persist($ticket);
        $this->getEntityManager()->flush();
    }
}
