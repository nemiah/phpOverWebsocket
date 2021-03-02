<?php

namespace pow;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface {

	private $rootDir = null;
    public function __construct($rootDir)
    {
    	if($rootDir == "examples")
    		$rootDir = realpath(__DIR__."/../")."/examples/";
    		
		$this->rootDir = $rootDir;
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection in $this->clients
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        foreach ( $this->clients as $client ) {

            if ( $from->resourceId != $client->resourceId )
                continue;
            
			$request = json_decode($msg);
			print_r($request);
			
			$url = $request->url;
			if(strpos($request->url, "?") !== false)
				$url = substr($request->url, 2, strpos($request->url, "?") - 2);
			
			$path = $this->rootDir.$url;
			
			echo "calling file $path...\n";
			
			$descriptorspec = array(
				0 => array("pipe", "r"),
				1 => array("pipe", "w"),
				2 => array("pipe", "w")
			);

			$cwd = '/tmp';
			$env = [
				"POW_SESSION_ID" => $request->sessionid,
				"CONTENT_TYPE" => "application/x-www-form-urlencoded",
				"REQUEST_METHOD" => strtoupper($request->type),
				"CONTENT_LENGTH" => strlen($request->data),
				"GATEWAY_INTERFACE" => "CGI/1.1",
				"SCRIPT_FILENAME" => $path,
				"REDIRECT_STATUS" => "true",
				"REQUEST_URI" => "/hello/world"
			];

			$process = proc_open('php-cgi', $descriptorspec, $pipes, $cwd, $env);

			if (is_resource($process)) {
				fwrite($pipes[0], $request->data);
				fclose($pipes[0]);

				$answer = stream_get_contents($pipes[1]);
				$errors = stream_get_contents($pipes[2]);
				
				print_r($errors);
				
				fclose($pipes[1]);
				fclose($pipes[2]);

				$return_value = proc_close($process);

				echo "php-cgi returned $return_value\n";
			}
			
			$request->answer = $answer;
			
            $client->send(json_encode($request, JSON_UNESCAPED_UNICODE));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        echo "Disconnected! ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
} 
