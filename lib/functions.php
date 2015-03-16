<?php



class blipfoto_importer {



	public static $version = 1.0;



	static function version() {

		return blipfoto_importer::$version;

	}



	static function option( $opt ) {

		$opts = blipfoto_importer::options();

		if ( isset( $opts[$opt] ) )
			return $opts[$opt];

		return false;

	}



	static function options_saved() {

		return ( blipfoto_importer::option( 'username' ) and blipfoto_importer::option( 'client-id' ) and blipfoto_importer::option( 'client-secret' ) and blipfoto_importer::option( 'access-token' ) );

	}



	static function options() {

		global $blipfoto_importer_settings;

		return $blipfoto_importer_settings->get();

	}



}