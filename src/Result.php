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

namespace PaulHekkema\QueryBrowser;

use PaulHekkema\QueryBrowser\Exception\InvalidArgumentException;
use PaulHekkema\QueryBrowser\Exception\ViewNotFoundException;
use PaulHekkema\QueryBrowser\Result\Column;
use PaulHekkema\QueryBrowser\Result\View;

/**
 * class Result
 */
class Result
{
    /**
     *
     *
     * @var array
     */
    protected $results;

    /**
     *
     *
     * @var int
     */
    protected $totalResults;

    /**
     *
     *
     * @var Column[]
     */
    protected $columns = [];

    /**
     *
     *
     * @var QueryBrowser
     */
    protected $qb;

    /**
     * Construct a new QueryBrowser\Result.
     *
     * @param array $results
     * @param int   $totalResults
     *
     * @return  void
     */
    public function __construct(array $results, int $totalResults, QueryBrowser $qb)
    {
        $this->results = $results;
        $this->totalResults = $totalResults;
        $this->qb = $qb;

        if (count($results) > 0) {
            $result = reset($results);
            foreach (array_keys($result) as $i => $name) {
                $column = new Column($name, $i);

                $orderBy = $qb->getOrderBy();
                if ($name === $orderBy->getField()) {
                    $column->setOrderDirection($orderBy->getDirection());
                }

                $this->columns[$name] = $column;
            }
        }
    }

    /**
     * Renders
     *
     * @param array
     *
     * @return string  output (HTML)
     *
     * @throws ViewNotFoundException When view is not found
     */
    public function render(array $config = [], string $file = '')
    {
        if ($file == '') {
            $file = dirname(__FILE__) . '/Result/Resources/views/qbr.php';
        }

        if (!file_exists($file)) {
            throw new ViewNotFoundException(sprintf('Unable to find file %s.', $file));
        }

        $config = array_merge(
            [
                'createURI' => '',
                'updateURI' => '',
                'sortURI'   => '',
                'deleteURI' => '',
            ],
            $config
        );

        $totalPages = ceil($this->totalResults / $this->qb->getPageSize());
        $nrResults = count($this->results);
        $page = $this->qb->getPage();
        $offset = $this->qb->getOffset();
        $orderBy = $this->qb->getOrderBy();
        $searchManager = $this->qb->getSearchManager();
        $globalSearch = '';

        $data = [
            'id'              => $this->qb->getId(),
            'results'         => $this->results,
            'columns'         => $this->columns,
            'orderBy'         => $orderBy->getField(),
            'orderDirection'  => $orderBy->getDirection(),
            'globalSearch'    => $globalSearch,
            'firstResult'     => $offset + 1,
            'lastResult'      => $offset + $nrResults,
            'nrResults'       => $nrResults,
            'totalResults'    => $this->totalResults,
            'totalPages'      => $totalPages,
            'currentPage'     => $page,
            'previousPage'    => ($page > 1) ? $page - 1 : 0,
            'nextPage'        => ($page < $totalPages) ? $page + 1 : 0,
            'firstPage'       => ($page != 1) ? 1 : 0,
            'lastPage'        => ($page < $totalPages) ? $totalPages : 0,
            'pageSize'        => $this->qb->getPageSize(),
            'pageSizeOptions' => [10, 25, 50, 100],
            'createURI'       => $config['createURI'],
            'updateURI'       => $config['updateURI'],
            'deleteURI'       => $config['deleteURI']
        ];

        $view = new View($file, $data);

        return $view->render();
    }

    /**
     * Call an user-defined function on a column.
     *
     * The first parameter of the user function will contain the value of the record.
     * The second parameter of the user function will contain the complete record.
     * Extra parameters needed for the user function can be added to this function parameters.
     *
     * @param   string $columnId        column
     * @param   string $function        callback function
     *
     * @return  void
     */
    public function callFunctionOnColumn(string $columnId, string $function)
    {
        if (!isset($this->columns[$columnId])) {
            throw new InvalidArgumentException(sprintf('Unknown column: %s', $columnId));
        }

        // get user arguments
        $userArgs = array_slice(func_get_args(), 2);

        foreach ($this->results as $i => $row) {
            // merge callback parameters
            $callbackParams = array_merge([$this->results[$i][$columnId]], [$row], $userArgs);

            // apply function
            $this->results[$i][$columnId] = call_user_func_array($function, $callbackParams);
        }
    }

    /**
     * Adds a (static) column.
     *
     * @param   string $f      column
     * @param   int    $offset offset, 0 is first, -1 is last
     *
     * @return Column
     */
    public function addColumn(string $columnId, int $sequence, string $value = null)
    {
        if (isset($this->columns[$columnId])) {
            throw new InvalidArgumentException(sprintf('Unknown column: %s', $columnId));
        }

        // add column
        $column = new Column($columnId, $sequence);
        $column->setOrderable(false);
        $this->columns[$columnId] = $column;

        // set value
        foreach ($this->results as $k => $v) {
            $this->results[$k][$columnId] = $value;
        }

        return $column;
    }
}
