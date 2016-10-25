<?php

namespace QueryBrowser\Sources;

class Doctrine_ORM_QueryBuilder
{

	private $querybuilder;

	public function __construct($querybuilder)
	{
		$this->querybuilder = $querybuilder;
	}
}