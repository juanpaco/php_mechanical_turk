<?php

/* 

Mechanical Turk Question Form Answers

Class for Question Form Answers data structure

(c) 2009 Ethan Garofolo (http://www.e-thang.net)
Distributed under the MIT license

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

// TODO: inherit from this class the get typed methods... sort of like ActiveRecord
class MechanicalTurkQuestionFormAnswers {
	private $answers = array();
	
	static public function fromSimpleXML($xml) {
		$returnval = new MechanicalTurkQuestionFormAnswers();
		
		$children = $xml->children();
		
		foreach ($children as $node) {
			// Only supports FreeText answers.
			$returnval->answers["".$node->QuestionIdentifier] = "".$node->FreeText;
		}
		
		return $returnval;
	}	
	
	public function getAnswer($answer) {return $this->answers[$answer];}
}

?>