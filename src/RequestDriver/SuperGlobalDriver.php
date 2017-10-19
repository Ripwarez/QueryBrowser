<?php

/**
 * QueryBrowser
 *
 * @link      https://gitlab.kapma.nl/paulhekkema/querybrowser
 * @license   MIT (see LICENSE for details)
 * @author    Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace QueryBrowser\RequestDriver;

use QueryBrowser\RequestDriver\RequestDriverInterface;

/**
 * RequestDriver for PHP superglobal $_REQUEST.
 */
class SuperGlobalDriver implements RequestDriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function get(string $key)
    {
        if (isset($_REQUEST[$key])) {
            return $$_REQUEST[$key];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return $_REQUEST;
    }
    /**
     * {@inheritDoc}
     */
    public function set(string $key, $value)
    {
        $_REQUEST[$key] = $value;
    }
}
