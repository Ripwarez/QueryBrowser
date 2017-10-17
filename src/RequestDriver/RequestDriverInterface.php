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

namespace QueryBrowser\RequestDriver;

/**
 * Interface implemented by QueryBrowser\RequestDriver classes.
 */
interface RequestDriverInterface
{
    /**
     * .
     *
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key);

    /**
     * .
     *
     * @return array|null
     */
    public function getAll();

    /**
     * .
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set(string $key, $value);
}
