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
			$rand1 = mt_rand(0,9999);
			$rand2 = mt_rand(0,9999);

			$values[] = sprintf("(:r%s, :r%s)", $rand1, $rand2);
			$binds[sprintf(':r%s', $rand1)] = $variable;
			$binds[sprintf(':r%s', $rand2)] = $value;
		}

		$sql = 'INSERT INTO portal_server_settings (variable, set_value) VALUES %s ON DUPLICATE KEY UPDATE set_value=VALUES(set_value)';
		$sql = sprintf($sql, implode(',', $values));
		$this->dbs->sql($sql, $binds);
	}

	//
	// Save server settings file
	//
	public function saveServerSettingsFile($savefile, $gamefile, $data)
	{
		// save settings
		file_put_contents($savefile, $data);
		shell_exec("sudo cp $savefile $gamefile");

	}
}
