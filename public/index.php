<?php
use JsonRpcFramework\RemoteMethods\Example;
use JsonRpcFramework\Server;

require_once __DIR__ . '/../vendor/autoload.php';

$server = new Server();
$server->setConfig([
	'methodName' => Example::class
]);
$server->run();
