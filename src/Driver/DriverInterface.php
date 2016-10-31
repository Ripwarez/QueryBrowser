<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace QueryBrowser\Driver;

/**
 *
 */
interface DriverInterface
{
    public function getId();
    
    public function getResults();

    public function getTotalResults();
}
