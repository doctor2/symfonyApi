<?php

namespace App\Repository\ExpressionFactory;

use DateTimeInterface;
use Doctrine\ORM\Query\Expr;

class TicketDepartureTimeAfterDate
{
    public static function create(string $ticketAlias, DateTimeInterface $date): Expr\Comparison
    {
        $expressionBuilder = new Expr();

        return $expressionBuilder->gte(
            $ticketAlias.'.departureTime',
            $expressionBuilder->literal($date->format('Y-m-d 00:00:00'))
        );
    }
}
