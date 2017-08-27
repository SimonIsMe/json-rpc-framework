<?php

namespace JsonRpcFramework;

use JsonToDto\JsonToDtoParser;
use JsonRpcFramework\RemoteMethods\JsonRequestBodyParser;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class Server
{
	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var App
	 */
	private $app;

	public function __construct()
	{
		$container = new Container(
			array_merge(
				[
					'settings' => [
						'displayErrorDetails' => true,
					]
				]
			)
		);

		$this->app = new App($container);
	}

	public function setConfig(array $config)
	{
		$this->config = $config;

		$this->app->get('/', function (Request $request, Response $response) use ($config) {
			return $response->withJson($config);
		});

		$requestParser = new RequestParser(
			$config,
			new JsonToDtoParser(),
			new JsonRequestBodyParser()
		);
		$this->app->post('/', function (Request $request, Response $response) use ($requestParser) {
			return $requestParser->parse($request, $response);
		});
	}

	public function run()
	{
		$this->app->run();
	}
}
