<?php

namespace DSXI\Apps\Account;

use DSXI\Storage\UserStorage;

//
// User wrapper
//
class User extends \DSXI\Handle
{
	private $session;

	// Account info
	public $id;
	public $name;
	public $email;
	public $email2;
	public $created;
	public $updated;
	public $contentId;
	public $status;
	public $priv;
	public $accountId;
	public $level;
	public $characters = [];

	function __construct()
	{
		if ($this->session = $this->get('cookie')->get('sid'))
		{
			$us = new UserStorage();
			$user = $us->getUserViaSession($this->session);

			if ($user) {
				$this->id = $user['id'];
				$this->name = $user['login'];
				$this->email = $user['email'];
				$this->email2 = $user['email2'];
				$this->created = $user['timecreate'];
				$this->updated = $user['timelastmodify'];
				$this->contentId = $user['content_ids'];
				$this->status = $user['status'];
				$this->priv = $user['priv'];
				$this->level = $user['level'];

				foreach($user['characters'] as $chardata) {
					$this->characters[] = $chardata;
				}
			}
		}
	}

	//
	// Is online? (on the website, not the game)
	//
	public function isOnline()
	{
		return ($this->id && $this->name);
	}

	//
	// Is an admin?
	//
	public function isAdmin()
	{
		return $this->level > 0 ? true : false;
	}
}
