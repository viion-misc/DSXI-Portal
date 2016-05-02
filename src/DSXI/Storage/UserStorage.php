<?php

namespace DSXI\Storage;

//
// User Storage
//
class UserStorage extends \DSXI\Handle
{
	private $dbs;

	function __construct()
	{
		$this->dbs = $this->get('database');
	}

	//
	// Get a user via their session
	//
	public function getUserViaSession($session)
	{
		$query = 'SELECT * FROM accounts
			LEFT JOIN accounts_sessions_web ON accounts_sessions_web.account_id = accounts.id
			WHERE accounts_sessions_web.session = :session
			LIMIT 0,1';

		$user = $this->dbs->sql($query, [
			':session' => $session,
		]);

		return $user ? $user[0] : false;
	}

	//
	// Get a user via their password
	//
	public function getUserViaPassword($user, $pass)
	{
		$user = $this->dbs->sql('SELECT * FROM accounts WHERE login = :user AND password = PASSWORD(:pass)', [
			':user' => $user,
			':pass' => $pass,
		]);

		return $user ? $user[0] : false;
	}

	//
	// Update users session
	//
	public function updateUserSession($id, $session)
	{
		return $this->dbs
			->sql('INSERT INTO accounts_sessions_web (account_id, session) VALUES (:id, :session) ON DUPLICATE KEY UPDATE session=VALUES(session)', [
				':id' => $id,
				':session' => $session,
			]);
	}
}
