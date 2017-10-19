<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;
use QueryBrowser\Factory;
use QueryBrowser\QueryBrowser;

/**
 * @covers Factory
 */
class FactoryTest extends TestCase
{
    public function testCanBeCreatedWithArray()
    {
        $this->assertInstanceOf(
            QueryBrowser::class,
            Factory::create([])
        );
    }

    /**
     * @expectedException QueryBrowser\Exception\DriverNotFoundException
     */
    public function testDriverNotFoundException()
    {
        Factory::create(null);
    }
}
