<?php

namespace JsonRpcFramework;

use JsonToDto\JsonToDtoParser;
use ReflectionMethod;
use JsonRpcFramework\RemoteMethods\JsonRequestBodyParser;
use Slim\Http\Request;
use Slim\Http\Response;

class RequestParser
{
	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var JsonToDtoParser
	 */
	private $jsonToDtoParser;

	/**
	 * @var JsonRequestBodyParser
	 */
	private $jsonRequestBodyParser;

	/**
	 * @param array $config
	 * @param JsonToDtoParser $jsonToDtoParser
	 * @param JsonRequestBodyParser $jsonRequestBodyParser
	 */
	public function __construct(
		array $config,
		JsonToDtoParser $jsonToDtoParser,
		JsonRequestBodyParser $jsonRequestBodyParser
	)
	{
		$this->jsonToDtoParser = $jsonToDtoParser;
		$this->jsonRequestBodyParser = $jsonRequestBodyParser;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function parse(Request $request, Response $response) : Response
	{
		$validateResponse = $this->validateInputData($request, $response);
		if ($validateResponse !== null) {
			return $validateResponse;
		}

		$json = $request->getParsedBody();
		$jsonRequestBody = $this->jsonRequestBodyParser->parse($json);

		$className = $this->findClassName($jsonRequestBody->getMethod());
		if ($className === '') {
			return $response->withJson([
				'jsonrpc' => '2.0',
				'error' => [
					'code' => -32601,
					'message' => 'Method not found'
				],
				'id' => $jsonRequestBody->getId()
			]);
		}

		$remoteMethod = new $className;

		$params = null;
		if ($jsonRequestBody->hasParams()) {
			$parameterClassName = $this->getParameterClassName($className);
			$params = $this->jsonToDtoParser->parseToObject(
				$parameterClassName,
				$jsonRequestBody->getParams()
			);
		}

		$result = $remoteMethod->run($params);

		return $response->withJson([
			'jsonrpc' => '2.0',
			'result' => $result,
			'id' => $jsonRequestBody->getId()
		]);
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 *
	 * @return null|Response
	 */
	private function validateInputData(Request $request, Response $response)
	{
		$json = $request->getParsedBody();
		if ($json === null) {
			return $response->withJson([
				'jsonrpc' => '2.0',
				'error' => [
					'code' => -32700,
					'message' => 'Parse error'
				],
			]);
		}

		$errors = $this->jsonRequestBodyParser->isValid($json);
		if (empty($errors) === false) {
			return $response->withJson([
				'jsonrpc' => '2.0',
				'error' => [
					'code' => -32600,
					'message' => 'Invalid Request'
				],
			]);
		}

		return null;
	}

	public function findClassName(string $method): string
	{
		foreach ($this->config as $configMethod => $className) {
			if ($configMethod === $method) {
				return $className;
			}
		}

		return '';
	}

	public function getParameterClassName(string $className): string
	{
		$reflectionMethod = new ReflectionMethod($className, 'run');
		$parameters = $reflectionMethod->getParameters();
		if (empty($parameters)) {
			return '';
		}

		return $parameters[0]->getType()->__toString();
	}
}
