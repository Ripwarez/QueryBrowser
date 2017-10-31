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
 * StorageDriver for null (used for testing).
 */
class NullStorageDriver implements StorageDriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function get(string $key)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, string $value)
    {
        return false;
    }
}
