<?php

/* 

Mechanical Turk Request

Handles the encoding for requests made by MechanicalTurkProvider

(c) 2009 Ethan Garofolo (http://www.e-thang.net)
Distributed under the MIT license

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

class MechanicalTurkRequest {
	private $url = "http://mechanicalturk.sandbox.amazonaws.com/onca/xml";
	private $params = null;
	private $operation = null;
	
	static public $show_queries = false;
	
	public function __construct($operation, $params, $sandbox = true) {
		if (!$sandbox) $this->url = "http://mechanicalturk.amazonaws.com/onca/xml";
		$this->operation = $operation;
		$this->params = $params;
	}
	
	function go($for_real=true) {
		$timestamp = $this->generate_timestamp(time());
		$signature = $this->generate_signature(	MechanicalTurkProvider::SERVICE_NAME, 
											   	$this->operation, 
												$timestamp, 
												MechanicalTurkProvider::AWS_SECRET_ACCESS_KEY);
		
		$request_url = 	$this->url . 
					   	"?Service=" . urlencode(MechanicalTurkProvider::SERVICE_NAME) .
					   	"&Operation=" . urlencode($this->operation) .
						"&Version=" . urlencode(MechanicalTurkProvider::SERVICE_VERSION) .
						"&Timestamp=" . urlencode($timestamp) .
						"&AWSAccessKeyId=" . urlencode(MechanicalTurkProvider::AWS_ACCESS_KEY_ID) .
						"&Signature=" . urlencode($signature);
		
		foreach ($this->params as $key => $value) {
			if (is_object($value))
				$request_url = $request_url . $value;
			else
				$request_url = $request_url . "&" . $key . "=" . $value;
		}
		
		if (MechanicalTurkRequest::$show_queries) {
			//printf("sending $operation:\n");
			printf("%s\n", $request_url);
		}
		
		if ($for_real)
			return simplexml_load_file($request_url);
		else 
			return null;
	}
	
	// Define authentication routines
	function generate_timestamp($time) {
	  return gmdate("Y-m-d\TH:i:s\\Z", $time);
	}

	function hmac_sha1($key, $s) {
	  return pack("H*", sha1((str_pad($key, 64, chr(0x00)) ^ (str_repeat(chr(0x5c), 64))) .
	                         pack("H*", sha1((str_pad($key, 64, chr(0x00)) ^ (str_repeat(chr(0x36), 64))) . $s))));
	}

	function generate_signature($service, $operation, $timestamp, $secret_access_key) {
	  $string_to_encode = $service . $operation . $timestamp;
	  $hmac = $this->hmac_sha1($secret_access_key, $string_to_encode);
	  $signature = base64_encode($hmac);
	  return $signature;
	}
}

?>