<?php

namespace App\Repository\ExpressionFactory;

use DateTime;
use Doctrine\ORM\Query\Expr;

class TicketDepartureTimeAfterDate
{
    public static function create(string $ticketAlias, DateTime $date): Expr\Comparison
    {
        $expressionBuilder = new Expr();

        return $expressionBuilder->gte(
            $ticketAlias.'.departureTime',
            $expressionBuilder->literal($date->format('Y-m-d 00:00:00'))
        );
    }
}
