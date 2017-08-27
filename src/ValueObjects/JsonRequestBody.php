<?php

namespace JsonRpcFramework\ValueObjects;

class JsonRequestBody
{
	/**
	 * @var string
	 */
	private $jsonrpc;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @var mixed
	 */
	private $id;

	/**
	 * @param string $jsonrpc
	 * @param string $method
	 * @param array $params
	 * @param mixed $id = null
	 */
	public function __construct(string $jsonrpc, string $method, $params = [], $id = null)
	{
		$this->jsonrpc = $jsonrpc;
		$this->method = $method;
		$this->params = $params;
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getJsonrpc(): string
	{
		return $this->jsonrpc;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @return array
	 */
	public function getParams(): array
	{
		return $this->params;
	}

	/**
	 * @return bool
	 */
	public function hasParams() : bool
	{
		return empty($this->params) === false;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function hasId() : bool
	{
		return $this->id !== null;
	}
}
