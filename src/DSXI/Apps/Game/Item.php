<?php

namespace DSXI\Apps\Game;

//
// Item wrapper
//
class Item extends \DSXI\Handle
{
	public $id;
	public $quantity;
	public $location;
	public $slot;

	function __construct($item)
	{
		$this->id = $item['itemId'];
		$this->quantity = $item['quantity'];
		$this->location = $item['location'];
		$this->slot = $item['slot'];


		$this->name = 'dunno';

	}
}
