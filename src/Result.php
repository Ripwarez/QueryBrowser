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

use QueryBrowser\Exception\ViewNotFoundException;
use QueryBrowser\Result\Column;

/**
 * class Result
 */
class Result
{
    protected $qb;

    protected $columns;

    protected $results;

    protected $totalResults;

    /**
     * Constructs a new QueryBrowser\Result.
     *
     * @param
     * @return  void
     */
    public function __construct(&$qb, &$results, $totalResults)
    {
        $this->qb = $qb;
        $this->results = $results;
        $this->totalResults = $totalResults;
        
        $this->columns = [];
        if (count($results) > 0) {
            $result = reset($results);
            foreach (array_keys($result) as $i => $name) {
                $column = new Column($name, $i);

                if ($name == $qb->getOrderBy()) {
                    $column->setOrderDirection($qb->getOrderDirection());
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

        $totalPages = ceil($this->totalResults / $this->qb->getPageSize());
        $currentPage = $this->qb->getPage();
        $nrResults = count($this->results);
        $offset = $this->qb->getOffset();

        $data = [
            'qbr'             => $this,
            'id'              => $this->qb->getId(),
            'results'         => $this->results,
            'columns'         => $this->columns,
            'orderBy'         => $this->qb->getOrderBy(),
            'orderDirection'  => $this->qb->getOrderDirection(),
            'globalSearch'    => $this->qb->getGlobalSearch(),
            'firstResult'     => $offset + 1,
            'lastResult'      => $offset + $nrResults,
            'nrResults'       => $nrResults,
            'totalResults'    => $this->totalResults,
            'totalPages'      => $totalPages,
            'currentPage'     => $currentPage,
            'previousPage'    => ($currentPage > 1) ? $currentPage - 1 : 0,
            'nextPage'        => ($currentPage < $totalPages) ? $currentPage + 1 : 0,
            'firstPage'       => ($currentPage != 1) ? 1: 0,
            'lastPage'        => ($currentPage < $totalPages) ? $totalPages : 0,
            'pageSize'        => $this->qb->getPageSize(),
            'pageSizeOptions' => [10, 25, 50, 100],
            'createURI'       => $config['createURI'],
            'updateURI'       => $config['updateURI'],
            'deleteURI'       => $config['deleteURI']
        ];

        return $this->fetch_html($data, $view);
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

    /**
     * sets only variables that the view may have
     */
    protected function fetch_html($data, $file)
    {
        // create the variables
        extract($data);

        ob_start();
        include($file);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
