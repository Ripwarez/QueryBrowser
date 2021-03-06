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
 * StorageDriver for PHP superglobal $_COOKIE.
 */
class CookieStorageDriver implements StorageDriverInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, string $value): bool
    {
        return setcookie($key, $value);
    }
}
