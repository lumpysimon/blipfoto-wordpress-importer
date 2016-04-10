<?php

namespace Blipfoto\Api;

use Blipfoto\Api\Client;
use Blipfoto\Api\Response;
use Blipfoto\Exceptions\NetworkException;
//use Blipfoto\Traits\Helper;

class Request {

//	use Helper;

	protected $client;
	protected $method;
	protected $resource;
	protected $params;

	protected $curl;
	protected $headers;

	/**
	 * Create new Client instance.
	 *
	 * @param Client $client
	 * @param string $method (optional)
	 * @param string $resource (optional)
	 * @param array $params (optional)
	 */
	public function __construct(Client $client, $method = 'GET', $resource = null, $params = null) {
		$this->client = $client;
		$this->method($method);
		$this->resource($resource);
		$this->params($params);
		$this->headers = array();
	}

	/**
	 * Get and optionally set the method.
	 *
	 * @param string $method (optional)
	 * @return string
	 */
	public function method() {
		return $this->getset('method', func_get_args());
	}

	/**
	 * Get and optionally set the resource.
	 *
	 * @param string $resource (optional)
	 * @return string
	 */
	public function resource() {
		return $this->getset('resource', func_get_args());
	}

	/**
	 * Get and optionally set the params.
	 *
	 * @param array $params (optional)
	 * @return string
	 */
	public function params() {
		return $this->getset('params', func_get_args());
	}

	/**
	 * Get and optionally set a header.
	 *
	 * @param string $name
	 * @param mixed $value (optional)
	 * @return mixed
	 */
	public function header() {
		$args = func_get_args();
		$name = $args[0];
		if (count($args) == 2) {
			$this->headers[$name] = $args[1];
		}
		return $this->headers[$name];
	}

	/**
	 * Return the request url.
	 *
	 * @return string
	 */
	public function url() {
		$url = Client::URI_API_ENDPOINT . $this->resource . '.json';
		if ($this->method == 'GET' && $this->params !== null) {
			$url .= '?' . http_build_query($this->params);
		}
		return $url;
	}

	/**
	 * Make a request and return the response data if successful.
	 *
	 * @return Response
	 * @throws NetworkException
	 */
	public function send() {

		$url = $this->url();

		// Set fields for post or put requests.
		$this->curl = curl_init();
		if ($this->method == 'POST' || $this->method == 'PUT') {
			curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->method);
			if ($this->params !== null) {
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->params));
			}
		}
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

		// Capture rate limit response headers.
		$rate_limit = array();
		curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$rate_limit) {
			$parts = explode(':', trim($header), 2);
			if (isset($parts[1]) && preg_match('/^X-RateLimit-(.*)/', $parts[0], $match)) {
				$rate_limit[$match[1]] = (int) $parts[1];
			}
			return strlen($header);
		});

		// Set authorization.
		$access_token = $this->client->accessToken();
		if ($access_token === null) {
			$this->setBearerToken($this->client->id());
		} else {
			$this->setBearerToken($access_token);
		}

		// Add headers.
		$headers = array();
		foreach ($this->headers as $name => $value) {
			if ($value !== null) {
				$headers[] = $name . ': ' . $value;
			}
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
		}

		// Get response, status and error info before closing the handle.
		$raw_body = @curl_exec($this->curl);
		$http_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		$error_code = curl_errno($this->curl);
		$error_message = curl_error($this->curl);
		@curl_close($this->curl);

		// Check for curl error.
		if ($error_code > 0) {
			throw new NetworkException($error_message, $error_code);
		}

		return new Response($raw_body, $http_status, $rate_limit);
	}

	/**
	 * Set the bearer token for authorization.
	 *
	 * @param string $token
	 **/
	protected function setBearerToken($token) {
		$this->header('Authorization', 'Bearer ' . $token);
	}
	
	private function getset($property, $args) {
		if (count($args)) {
			$this->$property = $args[0];
		}
		return $this->$property;
	}

}