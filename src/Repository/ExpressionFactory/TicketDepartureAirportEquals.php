<?php

namespace App\Repository\ExpressionFactory;

use App\Entity\Airport;
use Doctrine\ORM\Query\Expr;

class TicketDepartureAirportEquals
{
    public static function create(string $tableAlias, Airport $departureAirport): Expr\Comparison
    {
        $expressionBuilder = new Expr();

        return $expressionBuilder->eq($tableAlias.'.departureAirport', $departureAirport->getId());
    }
}
