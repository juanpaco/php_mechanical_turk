<?php

/* 

Mechanical Turk Price

Class for Price data structure

(c) 2009 Ethan Garofolo (http://www.e-thang.net)
Distributed under the MIT license

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

class MechanicalTurkPrice {
	private $amount = 0.0;
	private $currency_code = 'USD';	// currently the only supported currency (http://docs.amazonwebservices.com/AWSMechanicalTurkRequester/2008-04-01/)
	
	private $formatted_price = NULL;
	
	public function __construct($amount=0.0, $currency_code = 'USD') {
		$this->amount = $amount;
		$this->currency_code = $currency_code;		
	}
	
	public function __toString() {
		return "&Reward.1.Amount=" . urlencode($this->amount) .
			   "&Reward.1.CurrencyCode=" . urlencode($this->currency_code);
	}
	
	public function getAmount() {return $this->amount;}
	public function setAmount($amount) {$this->amount = $amount;}
	
	public function getCurrencyCode() {return $this->currency_code;}
	// TODO: validate $currency_code
	public function setCurrencyCode($currency_code) {$this->currency_code = $currency_code;}
	
	public function getFormattedPrice() {return $this->formatted_price;}
	public function setFormattedPrice($formatted_price) {$this->formatted_price = $formatted_price;}
}

?>