<?php

namespace App\Libraries;

class PassportAPI {

	protected $apiurl;
	protected $header = [
		"Content-Type: application/json"
	];
	protected $request_type = 'json';

	public function __construct(){

	}

	public function post( $endpoint, $params ){
		return $this->send( "post", $endpoint, $params );
	}

	public function get($endpoint){
		return $this->send( "get", $endpoint );
	}

	public function send( $method, $point, $param = false ){

		switch( $method ){

			case "post":

				$ch = curl_init();
				$query = $this->request_type == 'json' ? json_encode( $param ) : http_build_query( $param );

				if( !empty( $this->header ) ) curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header );
				
			    curl_setopt($ch, CURLOPT_URL, $point );
			    curl_setopt($ch, CURLOPT_POST, 1 );
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_USERAGENT, "Website Curl");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			    $return = curl_exec ($ch);
			    curl_close ($ch);
			    
			    // dd( $return );

			    return json_decode( $return );

			break;

			case "get":

				$ch = curl_init();

				if( !empty( $this->header ) ) curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header );

			    if( $param ){

			    	if( is_array( $param ) )
			    		curl_setopt($ch, CURLOPT_URL, $point . "/?" . http_build_query( $param ) );
			    	else
			    		curl_setopt($ch, CURLOPT_URL, $point . "/" . $param );

			    } else
			    	curl_setopt($ch, CURLOPT_URL, $point );

			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_USERAGENT, "Website Curl");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				// curl_setopt($ch, CURLOPT_VERBOSE, 1);
				// curl_setopt($ch, CURLOPT_HEADER, 1);

			    $response = curl_exec ($ch);
                // dd( $response );

			    // $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
				// $header = substr($response, 0, $header_size);
				// $body = substr($response, $header_size);

			    curl_close ($ch);
			    return json_decode( $response );

			break;

		}

	}
}