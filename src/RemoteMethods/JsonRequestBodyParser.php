<?php

namespace JsonRpcFramework\RemoteMethods;

use JsonRpcFramework\ValueObjects\JsonRequestBody;

class JsonRequestBodyParser
{
	/**
	 * @param array $json
	 *
	 * @return array - list of errors
	 */
	public function isValid(array $json) : array
	{
		$errors = [];
		if (array_key_exists('jsonrpc', $json) === false) {
			$errors[] = [
				'jsonrpc' => 'Is required.'
			];
		} else if (in_array($json['jsonrpc'], ['2.0']) === false) {
			$errors[] = [
				'jsonrpc' => 'It have to be "2.0"'
			];
		}

		if (array_key_exists('method', $json) === false) {
			$errors[] = [
				'method' => 'Is required.'
			];
		}

		if (array_key_exists('id', $json) && (is_float($json['id']) || is_double($json['id']))) {
			$errors[] = [
				'id' => 'It have to be integer, string or null'
			];
		}

		return $errors;
	}

	public function parse(array $json) : JsonRequestBody
	{
		$params = [];
		$id = null;
		if (array_key_exists('params', $json)) {
			$params = $json['params'];
		}
		if (array_key_exists('id', $json)) {
			$id = $json['id'];
		}

		return new JsonRequestBody(
			$json['jsonrpc'],
			$json['method'],
			$params,
			$id
		);
	}
}
