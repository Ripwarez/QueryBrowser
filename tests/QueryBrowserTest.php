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

/**
  *
  */
class QueryBrowserTest extends TestCase
{
    /**
     * [test description]
     * @return [type] [description]
     */
    public function test()
    {
        $qb = Factory::create([], null, new \QueryBrowser\StorageDriver\NullDriver());
        $qbr = $qb->execute();
        $html = $qbr->render();
        echo $html;
        //$this->assertTrue($nacho->hasCheese());
    }
}
