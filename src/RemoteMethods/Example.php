<?php

namespace JsonRpcFramework\RemoteMethods;

use JsonRpcFramework\RemoteMethods\Parameters\ExampleParameters;

class Example
{
	/**
	 * @param ExampleParameters $parameters
	 *
	 * @return string
	 */
	public function run(ExampleParameters $parameters)
	{
		return 'ok';
	}
}
