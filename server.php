<?php
if(!isset($argv[1])){
	echo "Please specify application root path as first argument\n";
	return 1;
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use pow\Socket;

require dirname( __FILE__ ) . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Socket($argv[1])
        )
    ),
    8080
);

$server->run();
