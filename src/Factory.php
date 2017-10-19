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

namespace QueryBrowser;

use QueryBrowser\Exception\DriverNotFoundException;
use QueryBrowser\QueryDriver\ArrayDriver;
use QueryBrowser\RequestDriver\RequestDriverInterface;
use QueryBrowser\StorageDriver\StorageDriverInterface;
use QueryBrowser\RequestDriver\SuperGlobalDriver;
use QueryBrowser\StorageDriver\CookieDriver;

/**
 * Factory for creating a new QueryBrowser.
 */
class Factory
{
    /**
     * List of possible drivers.
     *
     * @var array
     */
    private static $driversList = [
        'array' => ArrayDriver::class,
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
                $queryDriverClass = self::$driversList['array'];
                break;

            case 'object':
                $class = getclass($sourceObject);
                if (isset(self::$driversList[$class])) {
                    $queryDriverClass = self::$driversList[$class];
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
            $requestDriver = new SuperGlobalDriver();
        }

        if (null === $storageDriver) {
            $storageDriver = new CookieDriver();
        }

        return new QueryBrowser($id, $queryDriver, $requestDriver, $storageDriver);
    }
}
