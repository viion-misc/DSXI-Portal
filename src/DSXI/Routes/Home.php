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
            return $this->respond('Home/index.html.twig');
        });

        //
        // Home!
        //
        $this->route('/characters', 'GET', function(Request $request)
        {
            $db = $this->get('database');
            $characters = $db->sql('SELECT * FROM chars');

            show($characters);

            return $this->respond('Home/index.html.twig');
        });
    }
}