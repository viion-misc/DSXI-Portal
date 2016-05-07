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
                $storage->saveServerSettingsFile(ROOT .'/server/generated.settings.lua', '/home/vagrant/ffxi/scripts/globals/settings.lua', $settingsLua);
                $storage->saveServerSettingsFile(ROOT .'/server/generated.login_darkstar.conf', '/home/vagrant/ffxi/conf/login_darkstar.conf', $loginDarkstar);
                $storage->saveServerSettingsFile(ROOT .'/server/generated.map_darkstar.conf', '/home/vagrant/ffxi/conf/map_darkstar.conf', $mapDarkstar);
                $storage->saveServerSettingsFile(ROOT .'/server/generated.server_message.conf', '/home/vagrant/ffxi/conf/server_message.conf', $welcomeMessage);

                // Restart server
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

            return $this->redirect('/server/settings');
        });
    }
}
