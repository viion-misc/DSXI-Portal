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
	public function getInventoryByCharId($id)
	{
		$sql = sprintf('SELECT * FROM char_inventory
			WHERE char_inventory.charid = :charid');

		$results = $this->dbs->sql($sql, [
			':charid' => $id,
		]);

		$inventory = [];
		foreach($results as $i => $item) {
			if (isset($inventory[$item['location']][$item['itemId']])) {
				$inventory[$item['location']][$item['itemId']]->quantity += $item['quantity'];
				continue;
				
			}
			$inventory[$item['location']][$item['itemId']] = new Item($item);
		}

		return $inventory;
	}

	//
	// Increase size of storage
	//
	public function setStorageSize($charid)
	{
		$sql = 'UPDATE char_storage SET `inventory` = 80, `safe` = 80, `locker` = 80, `satchel` = 80, `sack` = 80, `case` = 80 WHERE charid = :charid';
		$this->dbs->sql($sql, [
			':charid' => $charid,
		]);
	}
}
