<?php
 
use QueryBrowser\QueryBrowserFactory;
 
class QueryBrowserTest extends PHPUnit_Framework_TestCase
{
    public function testQueryBrowserFactory()
    {
        QueryBrowserFactory::create([]);
        //$this->assertTrue($nacho->hasCheese());
    }
}