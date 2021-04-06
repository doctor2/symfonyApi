<?php

namespace App\Repository\ExpressionFactory;

use DateInterval;
use DateTimeInterface;
use Doctrine\ORM\Query\Expr;

class TicketDepartureTimeBeforeDatePlusThree
{
    private CONST TICKET_SEARCH_DAYS = 3;

    public static function create(string $ticketAlias, DateTimeInterface $date): Expr\Comparison
    {
        $expressionBuilder = new Expr();

        $interval = new DateInterval(sprintf('P%dD', self::TICKET_SEARCH_DAYS));

        return $expressionBuilder->lte(
            $ticketAlias.'.departureTime',
            $expressionBuilder->literal($date->add($interval)->format('Y-m-d 00:00:00'))
        );
    }
}
