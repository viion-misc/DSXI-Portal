<?php

namespace DSXI\Apps\Server;

class Server
{
	//
	// Restart server
	//
	public function restart()
	{
		shell_exec("sudo -H -u vagrant bash /dsxi/setup/server_stop");
		sleep(SERVER_RESTART_DELAY);
		shell_exec("sudo -H -u vagrant bash /dsxi/setup/server_start");
	}
}
