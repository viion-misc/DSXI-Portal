<?php

namespace DSXI\Apps\Misc;

//
// Cookie class
//
class Cookie
{
    public function add($key, $value)
    {
        @setcookie($key, $value, time() + COOKIE_EXPIRE, '/');
    }

    public function get($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : false;
    }

    public function getAll()
    {
        return $_COOKIE;
    }

    public function remove($key)
    {
        @setcookie($key, '', time()-1000, '/', COOKIE_DOMAIN);
    }
}
