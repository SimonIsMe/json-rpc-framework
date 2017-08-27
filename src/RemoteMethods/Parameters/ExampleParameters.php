<?php

namespace JsonRpcFramework\RemoteMethods\Parameters;

class ExampleParameters
{
	/**
	 * @var string
	 */
	private $a;

	/**
	 * @param string $a
	 */
	public function __construct(string $a)
	{
		$this->a = $a;
	}

	/**
	 * @return string
	 */
	public function getA(): string
	{
		return $this->a;
	}




}
