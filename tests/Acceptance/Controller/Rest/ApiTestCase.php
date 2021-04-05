<?php

namespace Tests\Acceptance\Controller\Rest;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{
    use FixturesTrait;

    /** @var AbstractBrowser */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }
}
