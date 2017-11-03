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

namespace Hekkema\QueryBrowser;

use Hekkema\QueryBrowser\Exception\InvalidArgumentException;
use Hekkema\QueryBrowser\Driver\Request\DefaultRequestDriver;
use Hekkema\QueryBrowser\Driver\Storage\CookieStorageDriver;
use Hekkema\QueryBrowser\Driver\View\DefaultViewDriver;
use Hekkema\QueryBrowser\Search;

/**
 *
 */
class ConfigManager
{
    /**
     * [$defaultConfig description]
     *
     * @var array
     */
    protected $defaultConfig = [
        'qb.requestDriver'          => DefaultRequestDriver::class,
        'qb.storageDriver'          => CookieStorageDriver::class,
        'qb.pageSize'               => 25,
        'qb.search.operator'        => Search::OPERATOR_SUBSTRING,
        'qb.search.caseSensitivity' => Search::CASE_INSENSITIVE,
        'qbr.viewDriver'            => DefaultViewDriver::class,
        'qbr.template'              => '',
        'qbr.defaultData'           => [
        	'createURI'       => '',
        	'updateURI'       => '',
        	'sortURI'         => '',
        	'deleteURI'       => '',
        	'pageSizeOptions' => [10, 25, 50, 100],
        ],
    ];

    /**
     *
     *
     * @var array
     */
    protected $config;

    /**
     *
     *
     * @param array $config
     *
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * [get description]
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        if (isset($this->defaultConfig[$key])) {
            return $this->defaultConfig[$key];
        }

        throw new InvalidArgumentException(sprintf('Unknown key: %s', $key));
    }
}
