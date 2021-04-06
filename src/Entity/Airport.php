<?php

namespace App\Entity;

use App\Repository\AirportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=AirportRepository::class)
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Airport
{
    /**
     * @Serializer\Type("integer")
     * @Serializer\Expose
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @ORM\Column(type="string", length=100)
     */
    private $timezone;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }
}
