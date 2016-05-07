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
            case 'database': return new \DSXI\Apps\DB\Database(DB_HOST, DB_NAME, DB_USER, DB_PASS); break;
            case 'cookie': return new \DSXI\Apps\Misc\Cookie(); break;
            case 'session': return new \DSXI\Apps\Misc\Session(); break;
            case 'server': return new \DSXI\Apps\Server\Server(); break;
        }

        if (property_exists($this, 'Silex')) {
            return $this->Silex[$module];
        }

        return false;
    }

    //
    // Get a random hash
    //
    protected function getRandomHash($length = 128, $simplified = false)
    {
        $characters = $simplified
            ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
            : '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@$%^&*()_+-={}[],.<>;:';

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
