<?php

namespace DSXI\Apps\Misc;

//
// Session class
//
class Session
{
    private $defaultExpireTime = (60*5);

    function __construct()
    {
        // Settings
        ini_set('session.name', 'dsxi');
        session_set_cookie_params($this->defaultExpireTime, '/', DOMAIN, false, false);

        // Start
        $this->setExpires($this->defaultExpireTime);
        @session_start();
    }

    public function add($index, $value)
    {
        $_SESSION[$index] = $value;
    }

    public function get($index)
    {
        return isset($_SESSION[$index]) ? $_SESSION[$index] : false;
    }

    public function getAll()
    {
        return $_SESSION;
    }

    public function remove($index)
    {
        unset($_SESSION[$index]);
    }

    public function removeAll()
    {
        session_destroy();
    }

    public function getID()
    {
        return session_id();
    }

    public function json()
    {
        return json_encode($_SESSION);
    }

    public function setExpires($seconds)
    {
        if ($seconds)
            session_cache_expire($seconds);
    }

    public function expires()
    {
        return session_cache_expire();
    }

    public function status()
    {
        return session_status();
    }
}
