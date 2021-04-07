<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase as UnitTestCase;

abstract class TestCase extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }
}
