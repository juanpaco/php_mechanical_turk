<?php

/* 

Mechanical Turk HIT

Class for HIT data structure

(c) 2009 Ethan Garofolo (http://www.e-thang.net)
Distributed under the MIT license

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

class MechanicalTurkHit {
	static public function fromSimpleXML($xml) {
		$returnval = new MechanicalTurkHit();
		
		$my_xml = $xml->Question;
		$xml = simplexml_load_string($my_xml);
		
		$returnval->setDescription("".$xml->Overview->Text);
		
		return $returnval;
	}
	
	private $description = NULL;
	
	public function getDescription() {return $this->description;}
	public function setDescription($value) {$this->description = $value;}
	
}

?>