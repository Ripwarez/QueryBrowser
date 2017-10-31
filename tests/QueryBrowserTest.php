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
use Hekkema\QueryBrowser\QueryBrowser;
use Hekkema\QueryBrowser\Driver\Query\ArrayQueryDriver;
use Hekkema\QueryBrowser\Driver\Request\RequestDriver;
use Hekkema\QueryBrowser\Driver\Storage\NullStorageDriver;

/**
 * @covers QueryBrowser
 */
class QueryBrowserTest extends TestCase
{
    /**
     * @expectedException Hekkema\QueryBrowser\Exception\InvalidIdentifierException
     *
     * @dataProvider idProvider
     */
    public function testInvalidIdentifierException($id)
    {
        $arrayDriver = $this->createMock(ArrayQueryDriver::class);
        $requestDriver = $this->createMock(RequestDriver::class);
        $storageDriver = $this->createMock(NullStorageDriver::class);

        new QueryBrowser($arrayDriver, $requestDriver, $storageDriver, $id);
    }

    public function idProvider()
    {
        return [
            [''],
            ['-']
        ];
    }
}
