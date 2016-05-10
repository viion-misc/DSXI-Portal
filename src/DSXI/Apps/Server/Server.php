<?php

namespace DSXI\Apps\Server;

class Server
{
	public $start = [];
	public $stop = [];

	function __construct()
	{
		foreach(SERVER_START as $i => $command) {
			$this->start[] = sprintf($command, SERVER_USER, SERVER_USER);
		}

		foreach(SERVER_STOP as $i => $command) {
			$this->stop[] = sprintf($command, SERVER_USER, SERVER_USER);
		}
	}

	//
	// Start the server
	//
	public function start()
	{
		foreach($this->start as $i => $command) {
			shell_exec($command);
		}
	}

	//
	// Stop server
	//
	public function stop()
	{
		foreach($this->stop as $i => $command) {
			shell_exec($command);
		}
	}

	//
	// Restart server
	//
	public function restart()
	{
		$this->stop();
		sleep(SERVER_RESTART_DELAY);
		$this->start();
	}

	//
	// Save server settings file
	//
	public function setFile($savefile, $gamefile, $data)
	{
		// save settings
		file_put_contents($savefile, $data);
		shell_exec(sprintf("sudo cp %s %s", $savefile, $gamefile));
		shell_exec(sprintf("sudo chown %s:%s %s", SERVER_USER, SERVER_USER, $gamefile));
	}
}
