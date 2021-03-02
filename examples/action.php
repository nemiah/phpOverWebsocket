<?php
if(isset($_SERVER["POW_SESSION_ID"]))
	session_id($_SERVER["POW_SESSION_ID"]);
	
session_start();

echo "Request received via ".(isset($_SERVER["POW_SESSION_ID"]) ? "websocket" : "fetch")."\n";

echo "\n";
echo "SESSION:\n";
print_r($_SESSION);

echo "\n";
echo "POST:\n";
print_r($_POST);

echo "\n";
echo "Current time:\n";
echo time();
