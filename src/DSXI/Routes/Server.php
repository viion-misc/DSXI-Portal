<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;

trait Server
{
    protected function _server()
    {
        //
        // Home!
        //
        $this->route('/server', 'GET|POST', function(Request $request)
        {
            $this->mustBeOnline();

            if ($request->isMethod('POST')) {

                // organize find and replace variables
                $settings = file_get_contents(ROOT .'/setup/resources/settings.template.lua');

                // this is dirty, trusting user...
                $far = [];

                foreach($request->request->all() as $key => $value) {
                    $far[sprintf('{{ %s }}', $key)] = $value == 'on' ? 1 : $value;
                }

                $settings = str_ireplace(array_keys($far), $far, $settings);

                show($settings);
                die;

                /*
                $find = []; $replace = [];
                foreach( as $key => $val) {
                    request
                }
                */
            }

            return $this->respond('Server/index.html.twig');
        });;
    }
}
