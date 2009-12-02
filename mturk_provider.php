<?php

/* 

Mechanical Turk Provider

A PHP wrapper for Amazon's Mechanical Turk.

(c) 2009 Ethan Garofolo (http://www.e-thang.net)
Distributed under the MIT license

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

include('mturk_request.php');
include('mturk_price.php');
include("mturk_qualification_requirement.php");
include("mturk_assignment.php");
include("mturk_question_form_answers.php");
include("mturk_hit.php");

class MechanicalTurkProvider {
	
	const AWS_ACCESS_KEY_ID = 		"INSERT YOURS HERE";
	const AWS_SECRET_ACCESS_KEY = 	"INSERT YOURS HERE";
	const SERVICE_NAME = 			"AWSMechanicalTurkRequester";
	const SERVICE_VERSION = 		"2007-03-12";
	
	private $sandbox = true;
	private $options = array();
	
	public function __construct($options=array()) {
		$this->options = $options;
		
		if ($this->options['show_queries']) MechanicalTurkRequest::$show_queries = true;
	}
	
	// Check for and print results and errors
	function print_errors($error_nodes) {
		print "There was an error processing your request:\n";
		foreach ($error_nodes as $error) {
			print "  Error code:    " . $error->Code . "\n";
			print "  Error message: " . $error->Message . "\n";
		}
	}
	
	// TODO: Add length checking
	public function RegisterHITType($title, $description, $reward, $assignment_duration_in_seconds, $options=array()) {
		$params = array(
			'Title' => urlencode($title),
			'Description' => urlencode($description),
			'Reward' => $reward,
			'AssignmentDurationInSeconds' => urlencode($assignment_duration_in_seconds)
		);
		
		$this->setOption($options, $params, 'Keywords');
		$this->setOption($options, $params, 'AutoApprovalDelayInSeconds');
		$this->setOption($options, $params, 'QualificationRequirement', false);
		
		$request = new MechanicalTurkRequest(
			"RegisterHITType",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
		
		if ($res->OperationRequest->Errors) {
  			$this->print_errors($res->OperationRequest->Errors->Error);
		}
		
		if ($res->RegisterHITTypeResult->Request && $res->RegisterHITTypeResult->Request->Errors) {
			$this->print_errors($res->RegisterHITTypeResult->Request->Errors->Error);
		}

		$id = $res->RegisterHITTypeResult->HITTypeId;
				
		return $id;
	}
	
	public function CreateHIT($hit_type_id, $question, $lifetime_in_seconds, $options=array()) {
		$params = array(
			'HITTypeId' => urlencode($hit_type_id),
			'Question' => urlencode($question),
			'LifetimeInSeconds' => urlencode($lifetime_in_seconds)
		);
		
		$request = new MechanicalTurkRequest(
			"CreateHIT",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();

		if ($errors = $res->HIT->Request->Errors) {
  			$this->print_errors($errors->children());
		}
	}
	
	public function GetReviewableHITs($options=array()) {
		$params = array();
		
		$this->setOption($options, $params, 'HITTypeId');
		$this->setOption($options, $params, 'Status');
		$this->setOption($options, $params, 'SortProperty');
		$this->setOption($options, $params, 'SortDirection');
		$this->setOption($options, $params, 'PageSize');
		$this->setOption($options, $params, 'PageNumber');
		
		$request = new MechanicalTurkRequest(
			"GetReviewableHITs",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
		
		$returnval = array();
		
		if ($res->OperationRequest->Errors) {
  			$this->print_errors($res->OperationRequest->Errors->Error);
			return false;
		}
		
		if ($res->GetReviewableHITsResult) {
			$children = $res->GetReviewableHITsResult->children();
			
			$returnval['num_results'] = $res->GetReviewableHITsResult->NumResults;
			$returnval['total_num_results'] = $res->GetReviewableHITsResult->TotalNumResults;
			$returnval['page_number'] = $res->GetReviewableHITsResult->PageNumber;

			$returnval['hits'] = array();
			foreach ($children as $node) {
				$node_name = $node->getName();
				if ($node_name == 'HIT') {
					$returnval['hits'][] = $node->HITId;
				}
			}
		}
		
		return $returnval;
	}
	
	public function SetHITAsReviewing($hit_id, $options=array()) {
		$params = array('HITId' => urlencode($hit_id));
		
		$this->setOption($options, $params, 'Revert');
		
		$request = new MechanicalTurkRequest(
			"SetHITAsReviewing",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
		
		if ($res->OperationRequest->Errors) {
  			$this->print_errors($res->OperationRequest->Errors->Error);
			return false;
		} else {
			return (strcasecmp($res->SetHITAsReviewingResult->Request->IsValid, 'true') == 0);
		}
	}
	
	// Not implementing full functionality of this API call.  Current domain is limited to 1 result per HIT
	public function GetAssignmentsForHIT($hit_id) {
		$params = array('HITId' => urlencode($hit_id));
		
		// For full functionality, implement setOption for options
		
		$request = new MechanicalTurkRequest(
			"GetAssignmentsForHIT",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
		
		// TODO: implement paging for multiple answers.  This method should return an array of assignments.
		if ($res->OperationRequest->Errors) {
  			$this->print_errors($res->OperationRequest->Errors->Error);
			return false;
		} else {
			if (intval($res->GetAssignmentsForHITResult->NumResults) == 1) {
				return MechanicalTurkAssignment::fromSimpleXML($res->GetAssignmentsForHITResult->Assignment);
			} else {
				return NULL;
			}
		}
	}
	
	public function ApproveAssignment($assignment_id, $options=array()) {
		$params = array('AssignmentId' => urlencode($assignment_id));
		
		$this->setOption($options, $params, 'RequesterFeedback');
		
		$request = new MechanicalTurkRequest(
			"ApproveAssignment",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
	}
	
	public function RejectAssignment($assignment_id, $options=array()) {
		$params = array('AssignmentId' => urlencode($assignment_id));
		
		$this->setOption($options, $params, 'RequesterFeedback');
		
		$request = new MechanicalTurkRequest(
			"RejectAssignment",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
	}
	
	public function DisposeHit($hit_id) {
		$params = array('HITId' => urlencode($hit_id));
				
		$request = new MechanicalTurkRequest(
			"DisposeHIT",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
		
		if ($res->OperationRequest->Errors) {
  			$this->print_errors($res->OperationRequest->Errors->Error);
			return false;
		} else {
			return true;			
		}
	}
	
	public function ExtendHIT($hit_id, $options) {
		$params = array('HITId' => urlencode($hit_id));
		
		$this->setOption($options, $params, 'MaxAssignmentsIncrement');
		$this->setOption($options, $params, 'ExpirationIncrementInSeconds');
		
		$request = new MechanicalTurkRequest(
			"ExtendHIT",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
		
		if ($res->OperationRequest->Errors) {
  			$this->print_errors($res->OperationRequest->Errors->Error);
			return false;
		} else {
			return true;			
		}		
	}
	
	// TODO: finish out HIT object. Only retrieves description right now.
	public function GetHIT($hit_id) {
		$params = array('HITId' => urlencode($hit_id));
				
		$request = new MechanicalTurkRequest(
			"GetHIT",
			$params,
			$this->options['sandbox']
		);
		
		$res = $request->go();
		
		if ($res->OperationRequest->Errors) {
  			$this->print_errors($res->OperationRequest->Errors->Error);
			return false;
		} else {
			return MechanicalTurkHit::fromSimpleXml($res->HIT);
		}
	}
	
	private function setOption($options, &$params, $option_in_question, $needs_encoding=true) {
		if ($options[$option_in_question]) {
			if ($needs_encoding)
				$params[$option_in_question] = urlencode($options[$option_in_question]);
			else
				$params[$option_in_question] = $options[$option_in_question];
		}
	}
}

?>