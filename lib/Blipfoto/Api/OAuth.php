<?php

namespace Blipfoto\Api;

use Blipfoto\Api\Client;
use Blipfoto\Exceptions\OAuthException;

class OAuth {

	protected $client;
	protected $authorize_uri;
	protected $oauth_key;

	/**
	 * Construct a new oauth instance.
	 *
	 * @param Client $client
	 * @param string $authorize_url
	 */
	public function __construct(Client $client, $authorize_uri) {
		$this->client = $client;
		$this->authorize_uri = $authorize_uri;
		$this->oauth_key = Client::SESSION_PREFIX . 'params';

		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
	}

	/**
	 * Begin authorization.
	 *
	 * @param string $redirect_uri
	 * @param string $scope
	 * @redirect
 	 */
	public function authorize($redirect_uri, $scope) {

		$state = sha1(mt_rand());

		$_SESSION[$this->oauth_key] = [
			'redirect_uri'	=> $redirect_uri,
			'scope'			=> $scope,
			'state'			=> $state,
		];

		$url = $this->authorize_uri . '?' . http_build_query([
			'response_type'	=> 'code',
			'client_id'		=> $this->client->id(),
			'client_secret' => $this->client->secret(),
			'redirect_uri'	=> $redirect_uri,
			'scope'			=> $scope,
			'state'			=> $state,
		]);
		header('Location: ' . $url);
		exit;
	}

	/**
	 * Obtain an authorization code.
	 *
	 * @return string
	 * @throws OAuthException
	 */
	public function getAuthorizationCode() {

		if (isset($_GET['error'])) {
			throw new OAuthException($_GET['error'], 1);	
		} elseif (!isset($_GET['code']) || !isset($_GET['state'])) {
			throw new OAuthException('Invalid parameters', 2);
		} elseif (!isset($_SESSION[$this->oauth_key]['state'])) {
			throw new OAuthException('No state found', 3);
		} elseif ($_GET['state'] != $_SESSION[$this->oauth_key]['state']) {
			throw new OAuthException('State invalid', 4);
		}

		return $_GET['code'];
	}

	/**
	 * Swap an authorization code for a token.
	 *
	 * @param string $authorization_code
	 * @return array
	 */
	public function getToken($authorization_code) {

		$params = $_SESSION[$this->oauth_key];
		unset($_SESSION[$this->oauth_key]);

		$response = $this->client->post('oauth/token', [
			'client_id'		=> $this->client->id(),
			'grant_type'	=> 'authorization_code',
			'code'			=> $authorization_code,
			'scope'			=> $params['scope'],
			'redirect_uri'	=> $params['redirect_uri'],
		]);
		return $response->data('token');
	}

}