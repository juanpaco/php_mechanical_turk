<?php

/* 

Mechanical Turk Assignment

Class for Assignment data structure

(c) 2009 Ethan Garofolo (http://www.e-thang.net)
Distributed under the MIT license

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

class MechanicalTurkAssignment {
	private $assignment_id = NULL;
	private $worker_id = NULL;
	private $hit_id = NULL;
	private $assignment_status = NULL;
	private $auto_approval_time = NULL;
	private $accept_time = NULL;
	private $submit_time = NULL;
	private $approval_time = NULL;
	private $rejection_time = NULL;
	private $deadline = NULL;
	private $answer = NULL;
	private $requester_feedback = NULL;
		
	static public function fromSimpleXML($xml) {
		$returnval = new MechanicalTurkAssignment();
		$returnval->setAssignmentId("".$xml->AssignmentId);
		$returnval->setWorkerId("".$xml->WorkerId);
		$returnval->setHITId("".$xml->HITId);
		$returnval->setAssignmentStatus("".$xml->AssignmentStatus);
		$returnval->setAutoApprovalTime("".$xml->AutoApprovalTime);	// TODO: use an actual Date object
		$returnval->setAcceptTime("".$xml->AcceptTime);
		$returnval->setSubmitTime("".$xml->SubmitTime);
		$returnval->setApprovalTime("".$xml->ApprovalTime);
		
		$returnval->answer = MechanicalTurkQuestionFormAnswers::fromSimpleXML(simplexml_load_string($xml->Answer));		
				
		return $returnval;
	}	
	
	public function getAssignmentId() {return $this->assignment_id;}
	public function setAssignmentId($value) {$this->assignment_id = $value;}

	public function getWorkerId() {return $this->worker_id;}
	public function setWorkerId($value) {$this->worker_id = $value;}

	public function getHitId() {return $this->hit_id;}
	public function setHitId($value) {$this->hit_id = $value;}

	public function getAssignmentStatus() {return $this->assignment_status;}
	public function setAssignmentStatus($value) {$this->assignment_status = $value;}

	public function getAutoApprovalTime() {return $this->auto_approval_time;}
	public function setAutoApprovalTime($value) {$this->auto_approval_time = $value;}

	public function getAcceptTime() {return $this->accept_time;}
	public function setAcceptTime($value) {$this->accept_time = $value;}

	public function getSubmitTime() {return $this->submit_time;}
	public function setSubmitTime($value) {$this->submit_time = $value;}

	public function getApprovalTime() {return $this->approval_time;}
	public function setApprovalTime($value) {$this->approval_time = $value;}

	public function getRejectionTime() {return $this->rejection_time;}
	public function setRejectionTime($value) {$this->rejection_time = $value;}

	public function getDeadline() {return $this->deadline;}
	public function setDeadline($value) {$this->deadline = $value;}

	public function getAnswer($answer) {return $this->answer->getAnswer($answer);}

	public function getRequesterFeedback() {return $this->requester_feedback;}
	public function setRequesterFeedback($value) {$this->requester_feedback = $value;}

}

?>