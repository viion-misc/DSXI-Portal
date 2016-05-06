<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;
use Ivoba\Silex\Provider\ConsoleServiceProvider;

use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Application;

use DSXI\Apps\Account\User;
use DSXI\Apps\Misc\Cookie;

class SilexApp extends \DSXI\Handle
{
    use Home;
    use Account;
    use Server;

    // Silex Application!
    public $Silex;

    //
    // Init
    //
    function __construct()
    {
        // Initialize silex
        $this->init();

        // Initialize routes
        $methods = get_class_methods($this);

        // go through methods and call this classes methods
        foreach($methods as $route) {
            if ($route[0] == '_' && $route[1] != '_') {
                // run route
                $this->$route();
            }
        }
    }

    //
    // Run Silex
    //
    public function run()
    {
        $this->Silex->run();
    }

    //
    // Initialize silex application
    //
    protected function init()
    {
        // create Silex App
        $this->Silex = new Application();
        $this->Silex['debug'] = SILEX_DEBUG;
        $this->Silex->register(new TwigServiceProvider(), TWIG_CONFIG);
        $this->Silex->register(new UrlGeneratorServiceProvider());
        $this->Silex->register(new ConsoleServiceProvider(), CONSOLE_CONFIG);
        $this->Silex->before(function (Request $request)
        {
            $this->addGlobal('user', $this->getUser());
            $this->addGlobal('server_name', SERVER_NAME);
            $this->addGlobal('server_logo', SERVER_LOGO);
        });

        // boot
        $this->Silex->boot();
    }

    //
    // Attach a route onto silex
    //
    protected function route($path, $methods, $function)
    {
        $this->Silex
            ->match($path, $function)
            ->method($methods);
    }

    //
    // Respond with a twig template injected wiht some data
    //
    public function respond($template, $data = [])
    {
        // add alerts
        $session = $this->get('session');
        $data['alerts'] = [
            'success' => $session->get('success'),
            'error' => $session->get('error'),
        ];

        // remove sessions
        $session->remove('success');
        $session->remove('error');

        // response
        return $this->get('twig')->render($template, $data);
    }

    //
    // Redirect to another url
    //
    public function redirect($url)
    {
        return $this->Silex->redirect($url);
    }

    //
    // Respond with json
    //
    public function json($data)
    {
        // if data, encode it, else return null
        $data = $data ? $data : [];
        $data = json_encode($data);

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $headers['Access-Control-Allow-Origin'] = '*';
        $headers['Cache-Control'] = 'max-age=3600';
        return new Response($data, 200, $headers);
    }

    //
    // Send a file to the user
    //
    public function send($file, $name = 'Download')
    {
        // Append DSXI to the name
        $name = sprintf('%s - %s', SERVER_NAME, $name);

        // ensure file exists, else 404
        if (!file_exists($file)) {
            $this->get()->abort(404);
        }

        // if name, send a bit differently.
        if ($name) {
            return $this
                ->get()
                ->sendFile($file)
                ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name);
        }

        return $this->get()->sendFile($file);
    }

    //
    // Add a global twig variable
    //
    protected function addGlobal($index, $data)
    {
        $this->Silex['twig']->addGlobal($index, $data);
    }

    //
    // Add a filter to twig
    //
    protected function addFilter($filter)
    {
        $this->Silex['twig']->addFilter($filter);
    }

    //
    // Get the current user
    //
    protected function getUser()
    {
        return new User();
    }

    //
    // Ensure the user is online
    //
    protected function mustBeOnline()
    {
        $user = $this->getUser();
        if (!$user->isOnline()) {
            header('Location: /login');
            exit();
        }
    }
}
