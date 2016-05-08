<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;

trait Home
{
    protected function _home()
    {
        //
        // Home!
        //
        $this->route('/', 'GET', function(Request $request)
        {
            $this->mustBeOnline();

            return $this->respond('Home/index.html.twig');
        });
    }
}
