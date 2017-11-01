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

namespace Hekkema\QueryBrowser\Driver\Query;

use Hekkema\QueryBrowser\OrderBy;
use Hekkema\QueryBrowser\SearchManager;
use Hekkema\QueryBrowser\Search;

/**
 * QueryDriver for an array.
 */
class ArrayQueryDriver implements QueryDriverInterface
{
    /**
     * Source data
     *
     * @var array
     */
    protected $data;

    /**
     * Order column that can be accessed from the static sortResults function
     *
     * @var string
     */
    protected static $sortOrderBy;

    /**
     * Order direction that can be accessed from the static sortResults function
     *
     * @var string
     */
    protected static $sortOrderDirection;

    /**
     * Construct a new ArrayDriver.
     *
     * @param array $data
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function generateId(): string
    {
        $keys = implode('-', array_keys($this->data));

        return md5($keys);
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): OrderBy
    {
        return new OrderBy();
    }

    /**
     * @inheritDoc
     */
    public function getResults(OrderBy $orderBy, SearchManager $searchManager, int $offset, int $limit): array
    {
        // search
        if (!$searchManager->isEmpty()) {
            $this->applyGlobalSearch($searchManager->getGlobalSearch());
        }

        // sort
        if (!$orderBy->isEmpty()) {
            self::$sortOrderBy = $orderBy->getField();
            self::$sortOrderDirection = $orderBy->getDirection();
            usort($this->data, 'self::sortResults');
        }

        if ($offset > 0 || $limit > 0) {
            return array_slice($this->data, $offset, $limit);
        }

        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getTotalResults(OrderBy $orderBy, SearchManager $searchManager): int
    {
        return count($this->data);
    }

    /**
     * [applySearch description]
     *
     * @return void
     */
    protected function applyGlobalSearch(Search $search)
    {
        $searchQuery = $search->getQuery();
        $searchOperator = $search->getOperator();
        $searchCaseSensitive = $search->IsCaseSensitive();

        if (false === $searchCaseSensitive) {
            $searchQuery = mb_strtolower($searchQuery);
        }

        foreach ($this->data as $k => $row) {
            $found = false;
            foreach ($row as $value) {
                if (is_array($value) || is_object($value)) {
                    $value = print_r($value, true);
                }

                if (false === $searchCaseSensitive) {
                    $value = mb_strtolower($value);
                }

                switch ($searchOperator) {
                    case Search::OPERATOR_EQUALS:
                        $found = ($searchQuery === $value);
                        break;

                    case Search::OPERATOR_NOT_EQUALS:
                        $found = ($searchQuery !== $value);
                        break;

                    /**
                     * these operators only apply to columns!
                     *
                    case Search::OPERATOR_GREATER_THAN:
                        $found = ($searchQuery > $value);
                        break;

                    case Search::OPERATOR_GREATER_THAN_OR_EQUAL:
                        $found = ($searchQuery >= $value);
                        break;

                    case Search::OPERATOR_LESS_THAN:
                        $found = ($searchQuery < $value);
                        break;

                    case Search::OPERATOR_LESS_THAN_OR_EQUAL:
                        $found = ($searchQuery <= $value);
                        break;
                    */

                    case Search::OPERATOR_STARTS_WITH:
                        $found = (0 === strpos($value, $searchQuery));
                        break;

                    case Search::OPERATOR_ENDS_WITH:
                        $value = mb_substr($value, mb_strlen($searchQuery) * -1);
                        $found = ($searchQuery === $value);
                        break;

                    case Search::OPERATOR_SUBSTRING:
                        $found = (false !== strpos($value, $searchQuery));
                        break;

                    default:
                        throw new \Exception(sprintf('Unknown operator: %d', $searchOperator));
                        break;
                }

                // stop searching if found
                if (true === $found) {
                    break;
                }
            }

            if (false === $found) {
                unset($this->data[$k]);
            }
        }
    }

    /**
     * Sort results based on the static variables in this class.
     *
     * @return int
     */
    protected static function sortResults($a, $b)
    {
        $a = $a[self::$sortOrderBy];
        $b = $b[self::$sortOrderBy];

        if ('asc' === self::$sortOrderDirection) {
            return ($a < $b) ? -1 : 1;
        } else {
            return ($a > $b) ? -1 : 1;
        }

        return 0;
    }
}
