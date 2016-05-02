<?php

namespace DSXI\Routes;

use Symfony\Component\HttpFoundation\Request;
use DSXI\Apps\Account\Account;
use DSXI\Apps\Misc\Cookie;
use DSXI\Storage\UserStorage;

trait Account
{
    protected function _account()
    {
		//
        // Home!
        //
        $this->route('/login', 'GET|POST', function(Request $request)
        {
            $user = $request->get('user');
            $pass = $request->get('pass');

            if ($user && $pass) {
                $us = new UserStorage();

                // check if login ok
                $user = $us->getUserViaPassword($user, $pass);

                // if success
                if ($user) {
                    // create session
                    $session = $this->getRandomHash();
                    (new Cookie())->add('sid', $session);

                    // update user
                    $us->updateUserSession($user['id'], $session);

                    // redirect home
                    return $this->redirect('/');
                }
            }

            return $this->respond('Account/login.html.twig');
        });
    }
}
