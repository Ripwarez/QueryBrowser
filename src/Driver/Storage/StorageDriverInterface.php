<?php

/**
 * QueryBrowser
 *
 * @link      https://gitlab.kapma.nl/paulhekkema/QueryBrowser
 * @license   MIT (see LICENSE for details)
 * @author    Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Hekkema\QueryBrowser\Driver\Storage;

/**
 * Interface implemented by QueryBrowser\StorageDriver classes.
 *
 * The storage driver handles persisting the last known state of a QueryBrowser instance.
 */
interface StorageDriverInterface
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
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function set(string $key, string $value);
}
