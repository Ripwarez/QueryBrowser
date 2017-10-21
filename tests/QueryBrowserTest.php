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
use QueryBrowser\QueryBrowser;
use QueryBrowser\Driver\Query\ArrayQueryDriver;
use QueryBrowser\Driver\Request\RequestDriver;
use QueryBrowser\Driver\Storage\NullStorageDriver;

/**
 * @covers QueryBrowser
 */
class QueryBrowserTest extends TestCase
{
    /**
     * @expectedException QueryBrowser\Exception\InvalidIdentifierException
     *
     * @dataProvider idProvider
     */
    public function testInvalidIdentifierException($id)
    {
        $arrayDriver = $this->createMock(ArrayQueryDriver::class);
        $requestDriver = $this->createMock(RequestDriver::class);
        $storageDriver = $this->createMock(NullStorageDriver::class);

        new QueryBrowser($id, $arrayDriver, $requestDriver, $storageDriver);
    }

    public function idProvider()
    {
        return [
            [''],
            ['-']
        ];
    }
}
