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
		$query = 'SELECT
			accounts.id, accounts.login, accounts.email, accounts.email2,
			accounts.timecreate, accounts.timelastmodify, accounts.content_ids,
			accounts.status, accounts.priv, portal_accounts_permissions.level
			FROM accounts
			LEFT JOIN portal_accounts_sessions ON portal_accounts_sessions.account_id = accounts.id
			LEFT JOIN portal_accounts_permissions ON portal_accounts_permissions.account_id = accounts.id
			WHERE portal_accounts_sessions.session = :session
			LIMIT 0,1';

		$user = $this->dbs->sql($query, [
			':session' => $session,
		]);

		$user = $user ? $user[0] : false;

		// if user, get characters
		if ($user) {
			$user['characters'] = (new CharacterStorage())->getCharactersByUserId($user['id']);
		}

		return $user;
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
		$this->dbs
			->sql('INSERT INTO portal_accounts_sessions (account_id, session) VALUES (:id, :session) ON DUPLICATE KEY UPDATE session=VALUES(session)', [
				':id' => $id,
				':session' => $session,
			]);

		$this->dbs
			->sql('INSERT INTO portal_accounts_permissions (account_id) VALUES (:id) ON DUPLICATE KEY UPDATE account_id=VALUES(account_id)', [
				':id' => $id,
			]);
	}
}
