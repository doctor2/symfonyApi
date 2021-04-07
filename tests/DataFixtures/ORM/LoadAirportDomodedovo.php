<?php

namespace Tests\DataFixtures\ORM;

use App\Entity\Airport;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class LoadAirportDomodedovo extends AbstractFixture
{
    public const REFERENCE_NAME = 'airport-domodedovo';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $this->create(self::REFERENCE_NAME, $manager);
    }

    public function create(string $referenceName, ObjectManager $manager): Airport
    {
        $airport = new Airport('Домодедово', 'Europe/Moscow');

        $manager->persist($airport);
        $this->addReference($referenceName, $airport);

        $manager->flush();

        return $airport;
    }
}
