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

use QueryBrowser\State;
use QueryBrowser\View;
use QueryBrowser\Result\Column;
use QueryBrowser\Exception\ViewNotFoundException;

/**
 * class Result
 */
class Result extends State
{
    protected $columns;

    protected $results;

    protected $totalResults;

    /**
     * Constructs a new QueryBrowser\Result.
     *
     * @param
     * @return  void
     */
    public function __construct(&$results, $totalResults)
    {
        $this->results = $results;
        $this->totalResults = $totalResults;
        
        $this->columns = [];
        if (count($results) > 0) {
            $result = reset($results);
            foreach (array_keys($result) as $i => $name) {
                $column = new Column($name, $i);

                if ($name == $this->orderBy) {
                    $column->setOrderDirection($this->orderDirection);
                }

                $this->columns[$name] = $column;
            }
        }
    }

    /**
     * Renders
     *
     * @param   array
     * @return  string  output (HTML)
     */
    public function render($config = [], $file = '')
    {
        if ($file == '') {
            $file = dirname(__FILE__) . '/Resources/views/qbr.php';
        }

        if (!file_exists($file)) {
            throw new ViewNotFoundException(sprintf('Unable to find file %s.', $file));
        }

        $config = array_merge([
                'createURI'   => '',
                'updateURI'   => '',
                'sortURI'     => '',
                'deleteURI'   => '',
            ],
            $config
        );

        $totalPages = ceil($this->totalResults / $this->pageSize);
        $nrResults = count($this->results);
        $offset = $this->getOffset();

        $data = [
            'id'              => $this->id,
            'results'         => $this->results,
            'columns'         => $this->columns,
            'orderBy'         => $this->orderBy,
            'orderDirection'  => $this->orderDirection,
            'globalSearch'    => $this->globalSearch,
            'firstResult'     => $offset + 1,
            'lastResult'      => $offset + $nrResults,
            'nrResults'       => $nrResults,
            'totalResults'    => $this->totalResults,
            'totalPages'      => $totalPages,
            'currentPage'     => $this->page,
            'previousPage'    => ($this->page > 1) ? $this->page - 1 : 0,
            'nextPage'        => ($this->page < $totalPages) ? $this->page + 1 : 0,
            'firstPage'       => ($this->page != 1) ? 1: 0,
            'lastPage'        => ($this->page < $totalPages) ? $totalPages : 0,
            'pageSize'        => $this->pageSize,
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
     * Extra parameters needed for the user function can be added to this function parameters.
     *
     * @param   string  $columnId      column
     * @param   string  $function         callback function
     * @param   bool    $resultParameter  use $row as second parameter in callback function
     * @return  void
     */
    public function callFunctionOnColumn($columnId, $function, $resultParameter = false)
    {
        if (isset($this->columns[$columnId])) {
            $userArgs = array_slice(func_get_args(), 2);
            foreach ($this->results as $i => $row) {
                if ($resultParameter) {
                    $callbackParams = array_merge(array($this->results[$i][$columnId]), array($row), $userArgs);
                } else {
                    $callbackParams = array_merge(array($this->results[$i][$columnId]), $userArgs);
                }
                $this->results[$i][$columnId] = call_user_func_array($function, $callbackParams);
            }
        }
    }

    /**
     * Adds a (static) column.
     *
     * @param   string  $f       column
     * @param   int     $offset  offset, 0 is first, -1 is last
     * @param   string  $dv      default value
     * @return  void
     */
    public function addColumn($columnId, $value = null, $offset = -1)
    {
        if (isset($this->columns[$columnId])) {
            return false;
        } else {
            $this->addValueToArray($this->columns, $columnId, $columnId, $offset);
            foreach ($this->results as $k => $v) {
                $this->addValueToArray($this->results[$k], $columnId, $value, $offset);
            }
            $column = new Column($columnId, $columnId);
            $column->setOrderable(false);
            $this->columns[$columnId] = $column;
        }
    }

    /**
     * Add a value to an array at specified offset.
     *
     * @param  array   $arr
     * @param  string  $key
     * @param  string  $value
     * @param  int     $offset
     */
    private function addValueToArray(&$arr, $key, $value, $offset)
    {
        switch ($offset) {
            case 0: // first
                $arr = array($key => $value) + $arr;
                break;

            case -1: // last
                $arr[$key] = $value;
                break;

            default:
                $part1 = array_slice($arr, 0, $offset, true);
                $part2 = array_slice($arr, $offset, count($arr), true);
                $arr = array_merge($part1, array($key => $value), $part2);
                break;
        }
    }
}
