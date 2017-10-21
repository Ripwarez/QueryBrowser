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

namespace QueryBrowser;

use QueryBrowser\Exception\DriverNotFoundException;
use QueryBrowser\Driver\Query\ArrayQueryDriver;
use QueryBrowser\Driver\Request\RequestDriverInterface;
use QueryBrowser\Driver\Request\RequestDriver;
use QueryBrowser\Driver\Storage\CookieStorageDriver;
use QueryBrowser\Driver\Storage\StorageDriverInterface;

/**
 * Factory for creating a new QueryBrowser.
 */
class Factory
{
    /**
     * List of possible query drivers.
     *
     * @var array
     */
    private static $queryDriversList = [
        'array' => ArrayQueryDriver::class,
    ];

    /**
     * Creates a new QueryBrowser bootstrapped with correct driver.
     *
     * The driver will selected automatically based on the supplied source object.
     *
     * @param mixed  $sourceObject Source object
     * @param string $id           Unique identifier
     *
     * @return QueryBrowser
     *
     * @throws DriverNotFoundException When unable to determine driver
     */
    public static function create(
        $sourceObject,
        RequestDriverInterface $requestDriver = null,
        StorageDriverInterface $storageDriver = null,
        string $id = null
    ) {
        $queryDriverClass = '';

        switch (gettype($sourceObject)) {
            case 'array':
                $queryDriverClass = self::$queryDriversList['array'];
                break;

            case 'object':
                $class = getclass($sourceObject);
                if (isset(self::$queryDriversList[$class])) {
                    $queryDriverClass = self::$queryDriversList[$class];
                }
                break;
        }

        if ('' === $queryDriverClass) {
            throw new DriverNotFoundException('Unable to determine QueryDriver.');
        }

        // create the querydriver
        $queryDriver = new $queryDriverClass($sourceObject);

        // if no id is supplied, use the one from the driver
        if (null === $id) {
            $id = $queryDriver->generateId();
        }

        if (null === $requestDriver) {
            $requestDriver = new RequestDriver();
        }

        if (null === $storageDriver) {
            $storageDriver = new CookieStorageDriver();
        }

        return new QueryBrowser($id, $queryDriver, $requestDriver, $storageDriver);
    }
}
