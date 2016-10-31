<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace QueryBrowser\Exception;

/**
 * DriverNotFoundException is thrown when a driver cannot be determined.
 */
class DriverNotFoundException extends \Exception implements ExceptionInterface
{
}
