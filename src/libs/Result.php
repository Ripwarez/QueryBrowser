<?php

namespace QueryBrowser;

/**
 * class Result
 */
class Result {

	/**
	 * @var  string  unique id
	 */
	protected $id;

	/**
	 * @var  string  string to search for
	 */
	protected $searchString;

	/**
	 * @var  int  current page number
	 */
	protected $currentPage;

	/**
	 * @var  int  how many rows to show per page
	 */
	protected $rowsPerPage;

	/**
	 * @var  array  columns
	 */
	protected $columns;

	/**
	 * @var  array  resultsrows
	 */
	protected $results;

	/**
	 * @var  int  total row count
	 */
	protected $totalRows;

	/**
	 * @var  int  total page count
	 */
	protected $totalPages;

	/**
	 * @var  int  number of rows on current page
	 */
	protected $rowsOnCurrentPage;

	/**
	 * @var  int  previous page number; FALSE if the current page is the first one
	 */
	protected $previousPage;

	/**
	 * @var  int  next page number; FALSE if the current page is the last one
	 */
	protected $nextPage;

	/**
	 * @var  int  first page number; FALSE if the current page is the first one
	 */
	protected $firstPage;

	/**
	 * @var  int  last page number; FALSE if the current page is the last one
	 */
	protected $lastPage;

	/**
	 * @var  int  rows offset
	 */
	protected $offset;

	/**
	 * @var  string  ordered by column
	 */
	protected $orderColumn;

	/**
	 * @var  string  order direction ('asc', 'desc' or empty)
	 */
	protected $orderDirection;

	/**
	 * @var  array
	 */
	protected $hiddenColumns = [];

	/**
	 * @var  array
	 */
	protected $staticColumns = [];

	/**
	 * Construct a new QueryBrowserResult.
	 *
	 * @param
	 * @return  void
	 */
	public function __construct(&$qb, &$columns, &$results)
	{
		// get stuff from qb
		$this->id = $qb->getId();
		$this->currentPage = $qb->getPage();
		$this->rowsPerPage = $qb->getRowsPerPage();
		$this->searchString = $qb->getSearchString();
		$this->totalPages = $qb->getTotalPages();
		$this->totalRows = $qb->getTotalRows();
		$this->orderColumn = $qb->getOrderColumn();
		$this->orderDirection = $qb->getOrderDirection();

		$this->columns = $columns;
		$this->results = $results;

		$this->rowsOnCurrentPage = sizeof($results);

		// calculate pagination
		$this->previousPage = ( $this->currentPage > 1 ) ? $this->currentPage - 1 : FALSE;
		$this->nextPage = ( $this->currentPage < $this->totalPages ) ? $this->currentPage + 1 : FALSE;
		$this->firstPage = ( $this->currentPage === 1 ) ? FALSE : 1;
		$this->lastPage= ( $this->currentPage >= $this->totalPages ) ? FALSE : $this->totalPages;
		$this->offset = ($this->currentPage - 1) * $this->rowsPerPage;

	}

	/**
	 * Hides a column.
	 *
	 * @param   string  $column  column
	 * @return  void
	 */
	public function hideColumn($column)
	{
		if ( !$this->isHiddenColumn($column) )
		{
			$this->hiddenColumns[] = $column;
		}
	}

	/**
	 * Checks if column is hidden.
	 *
	 * @param   string  $column  column.
	 * @return  boolean
	 */
	public function isHiddenColumn($column)
	{
		return in_array($column, $this->hiddenColumns);
	}

	/**
	 * Adds a (static) column.
	 *
	 * @param   string  $f       column
	 * @param   int     $offset  offset, 0 is first, -1 is last
	 * @param   string  $dv      default value
	 * @return  void
	 */
	public function addColumn($column, $offset = -1, $value = '')
	{
		if ( isset($this->columns[$column]) || $this->isStaticColumn($column) )
		{
			return FALSE;
		}
		else
		{
			$this->addValueToArray($this->columns, $column, $column, $offset);
			foreach ( $this->results as $k => $v )
			{
				$this->addValueToArray($this->results[$k], $column, $value, $offset);
			}
			$this->staticColumns[$column] = $column;
		}
	}

	/**
	 * Check if column is extra.
	 *
	 * @param   string  $column  column
	 * @return  boolean
	 */
	public function isStaticColumn($column)
	{
		return in_array($column, $this->staticColumns);
	}

	/**
	 * Call an user-defined function on a column.
	 *
	 * The first parameter of the user function will contain the value of the record.
	 * Extra parameters needed for the user function can be added to this function parameters.
	 *
	 * @param   string  $column        column
	 * @param   string  $callback      callback function
	 * @param   bool    $rowParameter  use $row as second parameter in callback function
	 * @return  void
	 */
	public function callFunctionOnColumn($column, $callback, $rowParameter = FALSE)
	{
		if ( isset($this->columns[$column]) )
		{
			$userArgs = array_slice(func_get_args(), 2);
			foreach ( $this->results as $i => $row )
			{
				if ( $rowParameter )
				{
					$callbackParams = array_merge(array($this->results[$i][$column]), array($row), $userArgs);
				}
				else
				{
					$callbackParams = array_merge(array($this->results[$i][$column]), $userArgs);
				}
				$this->results[$i][$column] = call_user_func_array($callback, $callbackParams);
			}
		}
	}

	/**
	 * Render
	 *
	 * @param   array
	 * @return  string  output (HTML)
	 */
	public function render($vars = [], $view = 'qbr')
	{
		$vars = array_merge([
				'createURI'   => '',
				'updateURI'   => '',
				'sortURI'     => '',
				'deleteURI'   => '',
				'deleteVar'   => '',
				'hideColumns' => [],
			],
			$vars
		);

		// hide columns
		foreach ( $vars['hideColumns'] as $column )
		{
			$this->hideColumn($column);
		}

		// add first column for creating update-link
		if ( !empty($vars['updateURI']) )
		{
			$firstColumn = '';
			foreach ( array_keys($this->columns) as $column )
			{
				if ( !$this->isHiddenColumn($column) )
				{
					$firstColumn = $column;
					break;
				}
			}
			$vars['firstColumn'] = $firstColumn;
		}

		$ignore = ['hiddenColumns', 'staticColumns'];
		foreach ( get_object_vars($this) as $k => $v )
		{
			if ( !array_search($k, $ignore) )
			{
				$vars[$k] = $v;
			}
		}

		// include qbr
		$vars['qbr'] = $this;

		// party time!
		return View::make($view, $vars);
	}

	/**
	 * Replace placeholders within the uri using data from the current row.
	 *
	 * @param  string  $uri
	 * @param  array   $row
	 * @return string
	 */
	public function rewriteUriPlaceholders($uri, &$row)
	{
		// find placeholders
		preg_match_all('#<([a-zA-Z0-9_]++)>#', $uri, $matches);

		if ( isset($matches[1]) )
		{
			foreach ( $matches[1] as $i => $columnKey )
			{
				// <key>
				$key = $matches[0][$i];

				// get the column value
				if ( isset($this->columns[$columnKey]) && isset($row[$columnKey]) )
				{
					$uri = str_replace($key, $row[$columnKey], $uri);
				}
			}
		}

		return url($uri);
	}

	/**
	 * Highlight the searchString.
	 *
	 * @param  string  $haystack
	 * @return string
	 */
	public function highlightSearchString($haystack)
	{
		if ( !empty($this->searchString) && substr($haystack, 0, 1) != '<' )
		{
			return preg_replace(sprintf('/(%s)/i', str_replace('/', '\/', $this->searchString)), '<span class="highlight">\\1</span>', $haystack);
		}

		return $haystack;
	}

	/**
	 * Get total rows.
	 *
	 * @return int
	 */
	public function getTotalRows()
	{
		return $this->totalRows;
	}

	/**
	 * Get the number of rows on this page.
	 *
	 * @return int
	 */
	public function getRowsOnCurrentPage()
	{
		return $this->rowsOnCurrentPage;
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
		switch ( $offset )
		{
			case 0: // first
				$arr = array($key => $value) + $arr;
				break;

			case -1: // last
				$arr[$key] = $value;
				break;

			default:
				$part1 = array_slice($arr, 0, $offset, TRUE);
				$part2 = array_slice($arr, $offset, sizeof($arr), TRUE);
				$arr = array_merge($part1, array($key => $value), $part2);
				break;
		}
	}
}