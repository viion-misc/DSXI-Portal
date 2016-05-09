<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;

use DSXI\Storage\ServerStorage;

trait Server
{
    protected function _server()
    {
        //
        // Server Settings
        //
        $this->route('/server', 'GET', function(Request $request)
        {
            $storage = new ServerStorage();
            $settings = $storage->getServerSettings();

            return $this->respond('Server/index.html.twig', [
                'settings' => $settings
            ]);
        });

        //
        // Server Settings
        //
        $this->route('/server/settings', 'GET|POST', function(Request $request)
        {
            $this->mustBeOnline();

            $storage = new ServerStorage();
            $settings = $storage->getServerSettings();

            // if post
            if ($request->isMethod('POST')) {
                // organize submitted
                $submitted = [];
                foreach($request->request->all() as $key => $value) {
                    $submitted[$key] = $value == 'on' ? 1 : $value;
                }

                // Save settings to database
                $storage->setServerSettings($submitted);

                // generate find and replace variables
                $findAndReplace = [];
                foreach($settings as $category => $options) {
                    foreach($options as $set) {
                        $value = isset($submitted[$set['variable']]) // does a submitted value exist?
                            ? $submitted[$set['variable']] // set to user submitted value
                            : $set['default_value']; // else set to default

                        $submitted[$set['variable']] = $value;
                        $findAndReplace[sprintf('{{ %s }}', $set['variable'])] = $value;
                    }
                }

                // A notice for the files
                $findAndReplace['{{ PORTAL_NOTICE }}'] = "-- This file was generated using the Web Portal, \n-- please use that tool for changes so that \n-- everything loads correctly. \n--\n-- To recover, use the Server Settings form.";

                // Set all values in template files
                $settingsLua = str_ireplace(array_keys($findAndReplace), $findAndReplace, file_get_contents(ROOT .'/server/template.settings.lua'));
                $loginDarkstar = str_ireplace(array_keys($findAndReplace), $findAndReplace, file_get_contents(ROOT .'/server/template.login_darkstar.conf'));
                $mapDarkstar = str_ireplace(array_keys($findAndReplace), $findAndReplace, file_get_contents(ROOT .'/server/template.map_darkstar.conf'));
                $welcomeMessage = str_ireplace(array_keys($findAndReplace), $findAndReplace, file_get_contents(ROOT .'/server/template.server_message.conf'));

                // Store all the settings
                $storage->saveServerSettingsFile(ROOT .'/server/generated.settings.lua', '/home/'. SERVER_USER .'/ffxi/scripts/globals/settings.lua', $settingsLua);
                $storage->saveServerSettingsFile(ROOT .'/server/generated.login_darkstar.conf', '/home/'. SERVER_USER .'/ffxi/conf/login_darkstar.conf', $loginDarkstar);
                $storage->saveServerSettingsFile(ROOT .'/server/generated.map_darkstar.conf', '/home/'. SERVER_USER .'/ffxi/conf/map_darkstar.conf', $mapDarkstar);
                $storage->saveServerSettingsFile(ROOT .'/server/generated.server_message.conf', '/home/'. SERVER_USER .'/ffxi/conf/server_message.conf', $welcomeMessage);

                // Restart server
                $this->get('session')->add('success', 'Settings have been saved and the server has been restarted.');
                $this->get('server')->restart();

                // get server settings again
                $settings = $storage->getServerSettings();

                // redirect to prevent post form refresh issues
                return $this->redirect('/server/settings');
            }

            return $this->respond('Server/settings/index.html.twig', [
                'settings' => $settings
            ]);
        });

        //
        // Home!
        //
        $this->route('/server/recover', 'GET', function(Request $request)
        {
            $data = file_get_contents('https://raw.githubusercontent.com/DarkstarProject/darkstar/master/scripts/globals/settings.lua');
            $savefile = ROOT .'/server/settings.generated.lua';

            // save
            $storage = new ServerStorage();
            $storage->saveServerSettingsFile($savefile, $data, true);

            $this->get('session')->add('success', 'Server settings have been recovered from the project repository source code. You will need to manually restart the server.');

            return $this->redirect('/server');
        });

        //
        // Server Actions
        //
        $this->route('/server/action/{action}', 'GET', function(Request $request, $action)
        {
            switch($action)
            {
                case 'start':
                    $this->get('server')->start();
                    $this->get('session')->add('success', 'Server has been started');
                    break;

                case 'stop':
                    $this->get('server')->stop();
                    $this->get('session')->add('success', 'Server has been stopped');
                    break;

                case 'restart':
                    $this->get('server')->restart();
                    $this->get('session')->add('success', 'Server has been restarted.');
                    break;
            }

            return $this->redirect('/server');
        });

        //
        // Server game data
        //
        $this->route('/server/gamedata', 'GET', function(Request $request)
        {
            return $this->respond('Server/gamedata/index.html.twig');
        });

        //
        // Server game data process
        //
        $this->route('/server/gamedata/process', 'GET', function(Request $request)
        {
            $storage = new ServerStorage();

            $folder = ROOT .'/data/';
            $files = array_diff(scandir($folder), ['..', '.']);

            foreach($files as $file)
            {
                $xml = simplexml_load_file($folder . $file);
                $data = json_decode(json_encode((array) $xml), 1);

                foreach($data['thing'] as $i => $item)
                {
                    $name = $item['field'][6];

                    show($name);
                }

                die;
            }


            show($files);



            die;
            $this->get('session')->add('success', 'Game data has been imported');
            return $this->redirect('/server/gamedata');
        });
    }
}
