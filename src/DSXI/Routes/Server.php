<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;

use DSXI\Storage\ServerStorage;

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

            $storage = new ServerStorage();
            $settings = $storage->getServerSettings();

            // if post
            if ($request->isMethod('POST')) {
                // Get the settings file
                $data = file_get_contents(ROOT .'/server/settings.template.lua');

                // organize submitted
                $submitted = [];
                foreach($request->request->all() as $key => $value) {
                    $submitted[$key] = $value == 'on' ? 1 : $value;
                }

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

                // update server
                $storage->setServerSettings($submitted);

                // update settings file
                $data = str_ireplace(array_keys($findAndReplace), $findAndReplace, $data);
                $savefile = ROOT .'/server/settings.generated.lua';

                // save settings
                file_put_contents($savefile, $data);
                shell_exec("sudo cp $savefile /home/vagrant/ffxi/scripts/globals/settings.lua");
                shell_exec("sudo bash /dsxi/setup/server_restart.sh");
                $this->get('session')->add('success', 'Settings have been saved and the server has been restarted.');

                // get server settings again
                $settings = $storage->getServerSettings();
            }

            return $this->respond('Server/index.html.twig', [
                'settings' => $settings
            ]);
        });;
    }
}
