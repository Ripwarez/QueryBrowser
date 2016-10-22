<?php

namespace Ripwarez\QueryBrowser;

class QueryBrowser
{

    public function setQuery($query)
    {
        var_dump(gettype($query));
        var_dump(get_class($query));
    }
}