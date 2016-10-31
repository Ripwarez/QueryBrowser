<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace QueryBrowser;

use QueryBrowser\Exception\DriverNotFoundException;

/**
 * Factory for creating a new QueryBrowser.
 */
class QueryBrowserFactory
{
    /**
     * List of possible sources.
     *
     * @var array
     */
    private static $sourceList = [
        'array'                             => 'QueryBrowser\Driver\ArrayDriver',
        'Doctrine\ORM\QueryBuilder'         => 'QueryBrowser\Driver\Doctrine_ORM_QueryBuilderDriver',
        'Doctrine\DBAL\Query\Querybuilder'  => 'QueryBrowser\Driver\Doctrine_DBAL_Query_QuerybuilderDriver',
        'Illuminate\Database\Query\Builder' => 'QueryBrowser\Driver\Illuminate_Database_Query_BuilderDriver'
    ];

    /**
     * Creates a new QueryBrowser based on the supplied source.
     *
     * @param mixed  $object Source object
     * @param string $id     Unique identifier
     *
     * @return QueryBrowser
     *
     * @throws InvalidArgumentException When style tags incorrectly nested
     */
    public static function create($object, $id = '')
    {
        $sourceClass = '';

        switch (gettype($object)) {
            case 'array':
                $sourceClass = self::$sourceList['array'];
                break;

            case 'object':
                $class = getclass($object);
                if (isset(self::$sourceList[$class])) {
                    $sourceClass = self::$sourceList[$class];
                }
                break;
        }

        if ($sourceClass == '') {
            throw new DriverNotFoundException('Unable to determine driver.');
        }

        return new QueryBrowser(new $sourceClass($object, $id));
    }
}
