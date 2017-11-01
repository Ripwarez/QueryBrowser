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
use Hekkema\QueryBrowser\Exception\ViewNotFoundException;
use Hekkema\QueryBrowser\Result\Column;
use Hekkema\QueryBrowser\Result\View;
use Hekkema\QueryBrowser\Driver\View\ViewDriverInterface;

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
     *
     *
     * @var ViewDriverInterface
     */
    protected $viewDriver;

    /**
     *
     *
     * @var string
     */
    protected $template;

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

        $viewDriver = $qb->getConfig()->get('qbr.viewDriver');
        $this->setViewDriver(new $viewDriver);

        $template = $qb->getConfig()->get('qbr.template');
        if ('' !== $template) {
            $this->setTemplate($template);
        }

        if (count($results) > 0) {
            $result = reset($results);
            foreach (array_keys($result) as $i => $name) {
                $column = new Column($name, $i * 100);

                $orderBy = $qb->getOrderBy();
                if ($name === $orderBy->getField()) {
                    $column->setOrderDirection($orderBy->getDirection());
                }

                $this->columns[$name] = $column;
            }
        }
    }

    /**
     * Get results
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Get total results
     *
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * [getViewDriver description]
     *
     * @return [type] [description]
     */
    public function getViewDriver()
    {
        return $this->viewDriver;
    }

    /**
     * [setViewDriver description]
     *
     * @param ViewDriverInterface $viewDriver [description]
     */
    public function setViewDriver(ViewDriverInterface $viewDriver)
    {
        $this->viewDriver = $viewDriver;

        return $this;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return self
     *
     * @throws InvalidArgumentException When template is not found
     */
    public function setTemplate(string $template)
    {
        if (!file_exists($template)) {
            throw new InvalidArgumentException(sprintf('Unable to find file %s.', $template));
        }

        $this->template = $template;

        return $this;
    }

    /**
     * Renders
     *
     * @param array
     *
     * @return string  output (HTML)
     */
    public function render(array $data = [], string $template = '')
    {
        if ('' !== $template) {
            $this->setTemplate($template);
        }

        $data = array_merge(
            [
                'createURI' => '',
                'updateURI' => '',
                'sortURI'   => '',
                'deleteURI' => '',
            ],
            $data
        );

        $totalPages = ceil($this->totalResults / $this->qb->getPageSize());
        $nrResults = count($this->results);
        $page = $this->qb->getPage();
        $offset = $this->qb->getOffset();
        $orderBy = $this->qb->getOrderBy();
        $searchManager = $this->qb->getSearchManager();

        $this->sortColumns();

        $data = array_merge($data, [
            'id'              => $this->qb->getId(),
            'results'         => $this->results,
            'columns'         => $this->columns,
            'orderBy'         => $orderBy->getField(),
            'orderDirection'  => $orderBy->getDirection(),
            'globalSearch'    => $searchManager->getGlobalSearch()->getQuery(),
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
            'pageSizeOptions' => $this->qb->getConfig()->get('qbr.pageSizeOptions'),
            'createURI'       => $data['createURI'],
            'updateURI'       => $data['updateURI'],
            'deleteURI'       => $data['deleteURI']
        ]);

        return $this->viewDriver->render($template, $data);
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
    public function callFunctionOnColumn(string $columnId, callable $function)
    {
        if (!isset($this->columns[$columnId])) {
            throw new InvalidArgumentException(sprintf('Unknown column: %s', $columnId));
        }

        if (!is_callable($function)) {
            throw new InvalidArgumentException(sprintf('Function not callable: %s', $function));
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
            throw new InvalidArgumentException(sprintf('Column already exists: %s', $columnId));
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

    /**
     * Sort the columns by sequence ascending
     *
     * @return void
     */
    protected function sortColumns()
    {
        uasort($this->columns, function ($columnA, $columnB) {
            $sequenceA = $columnA->getSequence();
            $sequenceB = $columnB->getSequence();

            if ($sequenceA === $sequenceB) {
                return 0;
            }

            return ($sequenceA < $sequenceB) ? -1 : 1;
        });
    }
}
