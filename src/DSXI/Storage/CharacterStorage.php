<?php

namespace DSXI\Storage;

//
// Character Storage
//
class CharacterStorage extends \DSXI\Handle
{
	private $dbs;

	function __construct()
	{
		$this->dbs = $this->get('database');
	}

	//
	// Get characters for user
	//
	public function getCharactersByUserId($accId)
	{
		return $this->dbs->sql('SELECT * FROM chars WHERE accid = :accid', [
			':accid' => $accId,
		]);
	}
}
