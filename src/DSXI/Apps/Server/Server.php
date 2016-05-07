<?php

namespace DSXI\Apps\Server;

class Server
{
	//
	// Start the server
	//
	public function start()
	{
		shell_exec("sudo -H -u vagrant bash /dsxi/setup/server_start");
	}

	//
	// Stop server
	//
	public function stop()
	{
		shell_exec("sudo -H -u vagrant bash /dsxi/setup/server_stop");
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
}
