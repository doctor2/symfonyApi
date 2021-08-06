<?php

namespace App\Message\Airport;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateAirportMessage
{
    /**
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Timezone()
     */
    private $timezone;

    public function __construct(array $requestData)
    {
        $this->name = $requestData['name'] ?? null;
        $this->timezone = $requestData['timezone'] ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }
}
