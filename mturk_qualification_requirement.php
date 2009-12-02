<?php

/* 

Mechanical Turk Qualification Requirement

Class for Qualification Requirement data structure

(c) 2009 Ethan Garofolo (http://www.e-thang.net)
Distributed under the MIT license

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

class MechanicalTurkSystemQualifications {
	const Worker_PercentAssignmentsSubmitted = "00000000000000000000";
	const Worker_PercentAssignmentsAbandoned = "00000000000000000070";
	const Worker_PercentAssignmentsReturned  = "000000000000000000E0";
	const Worker_PercentAssignmentsApproved  = "000000000000000000L0";
	const Worker_PercentAssignmentsRejected  = "000000000000000000S0";
	const Worker_Locale 					 = "00000000000000000071";
	const Worker_Adult						 = "00000000000000000060";
};

class MechanicalTurkQualificationRequirement {
	private $qualification_type_id = NULL;
	private $comparator = NULL;
	private $integer_value = NULL;
	private $locale_value = NULL;
	private $required_to_preview = NULL;
	
	// TODO: validate arguments, especially $comparator
	public function __construct($qualification_type_id, $comparator, $integer_value, $locale_value=NULL, $required_to_preview=false) {
		$this->qualification_type_id = $qualification_type_id;
		$this->comparator = $comparator;
		$this->integer_value = $integer_value;
		$this->locale_value = $locale_value;
		$this->required_to_preview = $required_to_preview;
	}
	
	// TODO: include stuff for locale
	public function toString($var_index) {
		return "&QualificationRequirement.$var_index.QualificationTypeId=" . urlencode($this->qualification_type_id) .
			   "&QualificationRequirement.$var_index.Comparator=" . urlencode($this->comparator) .
			   "&QualificationRequirement.$var_index.IntegerValue=" . urlencode($this->integer_value);
	}
	
	public function __toString() {
		return $this->toString(1);
	}
	
	public function getQualificationTypeId() {return $this->qualification_type_id;}
	public function setQualificationTypeId($value) {$this->qualification_type_id = $value;}
	
	public function getComparator() {return $this->comparator;}
	public function setComparator($value) {$this->comparator = $value;}
	
	public function getIntegerValue() {return $this->integer_value;}
	public function setIntegerValue($value) {$this->integer_value = $value;}
	
	public function getLocalValue() {return $this->locale_value;}
	public function setLocalValue($value) {$this->locale_value = $value;}
	
	public function getRequiredToPreview() {return $this->required_to_preview;}
	public function setRequiredToPreview($value) {$this->required_to_preview = $value;}
}

?>