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
    public function render($config = [], $view = '')
    {
        if ($view == '') {
            $view = dirname(__FILE__) . '/Resources/views/qbr.php';
        }

        if (!file_exists($view)) {
            throw new ViewNotFoundException(sprintf('Unable to find file %s.', $view));
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
            'qbr'             => $this,
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
            'deleteURI'       => $config['deleteURI'],
        ];

        return $this->render_view($data, $view);
    }

    /**
     * sets only variables that the view may have
     */
    protected function render_view($data, $file)
    {
        // create the variables
        extract($data);

        ob_start();
        include($file);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * Replace placeholders within the uri using data from the current result.
     *
     * @param  string  $uri
     * @param  array   $result
     * @return string
     */
    public function rewriteUriPlaceholders($uri, &$result)
    {
        // find placeholders
        preg_match_all('#<([a-zA-Z0-9_]++)>#', $uri, $matches);

        if (isset($matches[1])) {
            foreach ($matches[1] as $i => $columnKey) {
                // <key>
                $key = $matches[0][$i];

                // get the column value
                if (isset($this->columns[$columnKey]) && isset($result[$columnKey])) {
                    $uri = str_replace($key, $result[$columnKey], $uri);
                }
            }
        }

        return $uri;
    }
}
