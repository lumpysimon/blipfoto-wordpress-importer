<?php



class blipWP {



	var $endpoint = 'https://api.polaroidblipfoto.com/4/';

	protected $id;
	protected $secret;
	protected $token;
	protected $username;



	function __construct( $id, $secret, $access_token, $username ) {

		$this->id           = $id;
		$this->secret       = $secret;
		$this->access_token = $access_token;
		$this->username     = $username;

	}



	function get_entry_by_id( $id ) {

		if ( ! $id = absint( $id ) )
			return;

		$args = array(
			'params' => array(
				'entry_id'          => $id,
				'return_location'   => 1,
				'return_exif'       => 1,
				'return_dimensions' => 1
				)
			);

		$url = $this->url( 'entry', $args );

		$json = $this->request( $url );

		if ( $data = $json->data ) {
			return $data;
		}

	}



	function get_latest_entry() {

		$args = array(
			'params' => array(
				'username' => $this->username
				)
			);

		$url = $this->url( 'entry', $args );

		$json = $this->request( $url );

		if ( $data = $json->data ) {
			return $data;
		}

	}



	// ------------------
	// internal functions
	// ------------------



	private function url( $resource, $args = array() ) {

		$defaults = array(
			'client_id'    => $this->id,
			'access_token' => $this->access_token,
			'format'       => 'json',
			'params'       => null
			);

		$args = wp_parse_args( $args, $defaults );

		extract( $args, EXTR_SKIP );

		$url  = $this->endpoint;
		$url .= $resource . '.' . $format;

		if ( $client_id ) {
			$url = add_query_arg( array( 'client_id' => $this->client_id ), $url );
		}

		if ( is_array( $params ) and !empty( $params ) ) {
			$url = add_query_arg( $params, $url );
		}

		if ( $auth_sig ) {

			$sig = $this->signature( $user_auth );
			$url = add_query_arg(
				array(
					'timestamp' => $sig['timestamp'],
					'nonce'     => $sig['nonce'],
					'token'     => $sig['token'],
					'signature' => $sig['signature']
					),
				$url
				);

		}

		// error_log('   -----   BLIPFOTO URL   -----   '.print_r($url,true));

		return $url;

	}



	private function signature( $user_auth ) {

		$sig = array();

		$sig['timestamp'] = $this->create_timestamp();
		$sig['nonce']     = str_shuffle( md5( uniqid( rand(), true ) ) );
		$sig['token']     = '';

		if ( $user_auth ) {
			$sig['token'] = $this->token;
		}

		$sig['signature'] = md5( $sig['timestamp'] . $sig['nonce'] . $sig['token'] . $this->secret );

		return $sig;

	}



	private function create_timestamp() {

		$transient = blippress::prefix( 'time' );
		$timeout   = 3600; // 1hr
		$now       = time();

		if ( false === $diff = get_transient( $transient ) ) {

			$url = $this->url( 'time', array( 'auth_sig' => false ) );

			$json = $this->request( $url );

			if ( isset( $json->data->timestamp) ) {
				$diff = intval( $json->data->timestamp ) - $now;
				set_transient( $transient, $diff, $timeout );
			}

		}

		return $now + $diff;

	}



	private function request( $url, $method = 'get', $postdata = null ) {

		if ( 'post' == $method and ! is_array( $postdata ) )
			return false;

		switch ( $method ) {
			case 'get' :
				$response = wp_remote_get( $url, array( 'sslverify' => false ) );
			break;
			case 'post' :
				$data = array(
					'sslverify' => false,
					'timeout'   => 60,
					'body'      => $postdata
					);
				$response = wp_remote_post(
					$url,
					$data
					);
			break;
		}

		// error_log('   -----   BLIPFOTO RESPONSE   -----   '.print_r($response,true));

		if ( !is_wp_error( $response ) and isset( $response['body'] ) and $response['body'] ) {
			return json_decode( $response['body'] );
		}

		return false;

	}



} // class
