<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;

trait Account
{
    protected function _account()
    {
		//
        // Home!
        //
        $this->route('/login', 'GET', function(Request $request)
        {
            return $this->respond('Account/login.html.twig');
        });
    }
}
