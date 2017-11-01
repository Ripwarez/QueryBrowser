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
use Hekkema\QueryBrowser\Factory;
/**
 * @covers QueryBrowser
 */
class QueryBrowserTest extends TestCase
{
    private $qb;

    public function setUp()
    {
        $this->qb = Factory::create([]);
    }

    /**
     * @expectedException Hekkema\QueryBrowser\Exception\InvalidArgumentException
     *
     * @dataProvider idProvider
     */
    public function testSetIdException($id)
    {
        $this->qb->setId($id);
    }

    public function idProvider()
    {
        return [
            [''],
            ['-']
        ];
    }
}
