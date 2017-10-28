<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PaulHekkema\QueryBrowser\Factory;
use PaulHekkema\QueryBrowser\QueryBrowser;

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
     * @expectedException PaulHekkema\QueryBrowser\Exception\DriverNotFoundException
     */
    public function testDriverNotFoundException()
    {
        Factory::create(null);
    }
}
