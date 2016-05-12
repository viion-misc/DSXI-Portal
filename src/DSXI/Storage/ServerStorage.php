<?php

namespace DSXI\Storage;

//
// Server Storage
//
class ServerStorage extends \DSXI\Handle
{
	private $dbs;

	function __construct()
	{
		$this->dbs = $this->get('database');
	}

	//
	// Get server settings
	//
	public function getServerSettings()
	{
		$array = [];
		$settings = $this->dbs->sql('SELECT * FROM portal_server_settings ORDER BY category ASC, name ASC');
		foreach($settings as $option) {
			$array[$option['category']][] = $option;
		}

		return $array;
	}

	//
	// Set server settings
	//
	public function setServerSettings($settings)
	{
		$values = [];
		$binds = [];

		foreach($settings as $variable => $value)
		{
			// moar dirty code...
			$rand1 = mt_rand(0,99999999);
			$rand2 = mt_rand(0,99999999);

			$values[] = sprintf("(:r%s, :r%s)", $rand1, $rand2);
			$binds[sprintf(':r%s', $rand1)] = (string)$variable;
			$binds[sprintf(':r%s', $rand2)] = (string)$value;
		}

		$sql = 'INSERT INTO portal_server_settings (variable, set_value) VALUES %s ON DUPLICATE KEY UPDATE set_value=VALUES(set_value)';
		$sql = sprintf($sql, implode(',', $values));
		$this->dbs->sql($sql, $binds);
	}

	//
	// Populare auction house
	//
	public function populateAuctionHouse()
	{
		$insert = [];

		// get characters
		$characters = $this->dbs->sql('SELECT * FROM chars');

		// begin making queries
		foreach(['item_armor', 'item_basic', 'item_furnishing', 'item_puppet', 'item_usable', 'item_weapon'] as $table) {
			foreach($this->dbs->sql('SELECT * FROM '. $table) as $item) {
				$character = $characters[array_rand($characters)];

				$arr = [
					'itemid' => isset($item['itemId']) ? $item['itemId'] : $item['itemid'],
					'stack' => (isset($item['stackSize']) && $item['stackSize'] > 1) ? 1 : 0,
					'seller_name' => sprintf("'%s'", $character['charname']),
					'seller' => $character['charid'],
					'date' => time(),
					'price' => AUCTION_HOUSE_DEFAULT_PRICE,
				];

				// number of times to add it
				for ($i=0; $i < mt_rand(1, 5); $i++) {
					$insert[] = sprintf('(%s)', implode(",", $arr));
				}
			}
		}

		$insert = $this->insertIntoAuctionHouse($insert);
	}

	//
	// Insert some data into auction house
	//
	public function insertIntoAuctionHouse($insert)
	{
		// insert
		$sql = 'INSERT INTO auction_house (itemid, stack, seller_name, seller, date, price) VALUES %s';
		$sql = sprintf($sql, implode(',', $insert));
		$this->dbs->sql($sql);
		return [];
	}
}
