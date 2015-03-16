<?php

namespace Blipfoto\Api;

use Blipfoto\Exceptions\ApiResponseException;
use Blipfoto\Exceptions\OAuthException;
use Blipfoto\Traits\Helper;

class Client {

	use Helper;

	protected $id;
	protected $secret;
	protected $access_token;
	protected $instance;

	// Endpoint constants
	const URI_API_ENDPOINT 		= 'https://api.polaroidblipfoto.com/4/';
	const URI_AUTHORIZE 		= 'https://www.polaroidblipfoto.com/oauth/authorize/';

	// scope constants
	const SCOPE_READ 			= 'read';
	const SCOPE_READ_WRITE		= 'read,write';

	// misc constants
	const SESSION_PREFIX 		= 'polaroidblipfoto_';

	/**
	 * Create new Client instance.
	 *
	 * @param string $id
	 * @param string $secret
	 * @param string $access_token (optional)
	 */
	public function __construct($id, $secret, $access_token = null) {
		$this->id($id);
		$this->secret($secret);
		$this->accessToken($access_token);
	}

	/**
	 * Get and optionally set the id.
	 *
	 * @param string $id (optional)
	 * @return string
	 */
	public function id() {
		return $this->getset('id', func_get_args());
	}

	/**
	 * Get and optionally set the secret.
	 *
	 * @param string $secret (optional)
	 * @return string
	 */
	public function secret() {
		return $this->getset('secret', func_get_args());
	}

	/**
	 * Get and optionally set the user access token.
	 *
	 * @param string $access_token (optional)
	 * @return string
	 */
	public function accessToken() {
		return $this->getset('access_token', func_get_args());
	}

	/**
	 * Convenience method for sending a request and returning a response.
	 *
	 * @return Response
	 * @throws OAuthException|ApiResponseException
	 */
	protected function request($method, $args) {
		$request = new Request($this, $method, $args[0], count($args) > 1 ? $args[1] : []);
		$response = $request->send();
		$error = $response->error();
		if ($error !== null) {
			if ($error['code'] >= 30 && $error['code'] <= 35) {
				throw new OAuthException($error['message'], $error['code']);
			} else {
				throw new ApiResponseException($error['message'], $error['code']);
			}
		}
		return $response;
	}

	/**
	 * Convenience method for creating and sending a new GET request.
	 */
	public function get() {
		return $this->request('GET', func_get_args());
	}

	/**
	 * Convenience method for creating and sending a new POST request.
	 */
	public function post() {
		return $this->request('POST', func_get_args());
	}

	/**
	 * Convenience method for creating and sending a new PUT request.
	 */
	public function put() {
		return $this->request('PUT', func_get_args());
	}

	/**
	 * Convenience method for creating and sending a new DELETE request.
	 */
	public function delete() {
		return $this->request('DELETE', func_get_args());
	}

}