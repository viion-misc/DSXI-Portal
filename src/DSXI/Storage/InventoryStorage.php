<?php

namespace DSXI\Storage;

use DSXI\Apps\Game\Item;

//
// Inventory Storage
//
class InventoryStorage extends \DSXI\Handle
{
	private $dbs;

	function __construct()
	{
		$this->dbs = $this->get('database');
	}

	//
	// Get inventory for a specific character
	//
	public function getInventoryByCharId()
	{
		$sql = sprintf('SELECT * FROM char_inventory
			WHERE char_inventory.charid = :charid');

		$inventory = $this->dbs->sql($sql, [
			':charid' => $id,
		]);

		foreach($inventory as $i => $item) {
			$inventory[$i] = new Item($item);
		}



		return new Character($result[0]);
	}
}
