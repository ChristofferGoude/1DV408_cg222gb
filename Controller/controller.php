<?php
namespace Controller;

require_once("View/view.php");
require_once("Model/model.php");

class controller{
	//TODO: Everything! 
	/**
	 * @var $view
	 * @var $model
	*/
	
	private $body;
	private $view;
	private $model;
	
	public function __construct(){
		//TODO: Initiation
		
		$this->view = new \View\view();
		$this->model = new \Model\model();
	}
	
	public function run(){
		//Begin with checking user session status through the model.
		$this->body = $this->sessionStatus();
		
		if($this->view->registerAttempt()){
			$userInfo = $this->view->getUserRegInfo();
			
			$this->body = $this->model->registerUser($userInfo[0], $userInfo[1]);
		}
		
		if($this->view->loginAttempt()){
			$userInfo = $this->view->getUserLoginInfo();	
				
			if(!($this->model->loginUser($userInfo[0], $userInfo[1]))){
				$this->body = $this->view->verificationFailed();
			}
			else{
				$this->body = $this->sessionStatus();
			}
		}
		
		if($this->view->logoutAttempt()){
			$this->body = $this->view->registerUser() . $this->view->notLoggedIn();
		}
		
		return $this->view->getMainPage($this->body);
	}
	
	public function sessionStatus(){
		if($this->model->checkSessionStatus() != false){
			$username = $this->model->checkSessionStatus();	
			$quizName = "";
			
			$returnstring = $this->view->getContent("Välkommen " . $username . $this->view->logoutButton());
				
			$quizArray = $this->model->getQuiz();
			
			foreach($quizArray as $quiz){
				$quizName = $quiz["quizname"]; 
				$returnstring .= $this->view->getContent($quizName);
			}
			
			if($this->model->checkAdminStatus($username)){
				$returnstring .= $this->view->getAdminInterface();
			}
			
			return $returnstring;
		}
		else{
			return $this->view->registerUser() . $this->view->notLoggedIn();
		}
	}
}
