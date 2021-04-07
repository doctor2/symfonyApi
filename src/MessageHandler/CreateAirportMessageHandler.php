<?php

namespace App\MessageHandler;

use App\Entity\Airport;
use App\Message\CreateAirportMessage;
use App\Repository\AirportRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateAirportMessageHandler implements MessageHandlerInterface
{
    private $airportRepository;

    public function __construct(AirportRepository $airportRepository)
    {
        $this->airportRepository = $airportRepository;
    }

    public function __invoke(CreateAirportMessage $message): Airport
    {
        $airport = new Airport($message->getName(), $message->getTimezone());

        $this->airportRepository->save($airport);

        return $airport;
    }
}
