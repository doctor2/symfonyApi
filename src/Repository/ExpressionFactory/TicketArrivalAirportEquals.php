<?php

namespace App\Repository\ExpressionFactory;

use App\Entity\Airport;
use Doctrine\ORM\Query\Expr;

class TicketArrivalAirportEquals
{
    public static function create(string $tableAlias, Airport $arrivalAirport): Expr\Comparison
    {
        $expressionBuilder = new Expr();

        return $expressionBuilder->eq($tableAlias.'.arrivalAirport', $arrivalAirport->getId());
    }
}
