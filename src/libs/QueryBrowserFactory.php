<?php

namespace QueryBrowser;

class QueryBrowserFactory
{
	public static function create($object)
	{
		$connector = new \QueryBrowser\Sources\Doctrine_ORM_QueryBuilder($object);
		$connector = new \QueryBrowser\Sources\AssociativeArray($object);

		//return new QueryBrowser($connector);
	}
}