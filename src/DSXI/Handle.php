<?php

namespace DSXI;

//
// App Handler
//
class Handle
{
    //
    // Get a module
    //
    protected function get($module)
    {
        switch($module)
        {
            // database
            case 'database': return new \DSXI\Apps\DB\Database(DB_HOST, DB_NAME, DB_USER, DB_PASS); break;
        }

        if (property_exists($this, 'Silex')) {
            return $this->Silex[$module];
        }

        return false;
    }
}
