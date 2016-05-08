<?php

namespace DSXI\Apps\Game;

//
// Item wrapper
//
class Item extends \DSXI\Handle
{
	public $id;
	public $name;
	public $nation;

	function __construct($character)
	{
		$this->id = $character['charid'];
		$this->name = $character['charname'];

	}
}
