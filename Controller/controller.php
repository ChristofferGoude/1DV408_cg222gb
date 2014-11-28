<?php
namespace Controller;

require_once("View/view.php");
require_once("Model/model.php");

class controller{
	/**
	 * @var $view
	 * @var $model
	*/
	
	private $body;
	private $view;
	private $model;
	private static $quizName = "";
	
	public function __construct(){	
		$this->view = new \View\view();
		$this->model = new \Model\model();
	}
	
	public function run(){
		//Begin with checking user session status through the model.
		$this->body = $this->sessionStatus();
		
		if($this->view->sendQuiz()){		
			$quizID = $this->model->getQuizID(self::$quizName);
			$quiz = $this->model->getSpecificQuiz(self::$quizName);
			$score = $this->view->getUserScore($quiz);
			
			$this->model->insertUserScore($quizID, $score);
			
			$this->body = $this->view->showUserScore($score);
		}
		
		if($this->view->registerAttempt()){
			$userInfo = $this->view->getUserRegInfo();
			
			//TODO: Ett eget fönster för denna information
			$this->body .= $this->view->insertMessage($this->model->registerUser($userInfo[0], $userInfo[1]));
		}
		
		if($this->view->loginAttempt()){
			$userInfo = $this->view->getUserLoginInfo();	

			if($this->model->loginUser($userInfo[0], $userInfo[1])){
				$this->body = $this->sessionStatus();
			}
			else{
				//Handle error message
				$this->body .= $this->view->insertMessage("Inloggningen misslyckades.");
			}
		}
		
		if($this->view->logoutAttempt()){
			$this->body = $this->view->registerUser() . $this->view->loginUser();
		}
		
		if($this->view->createQuizAttempt()){
			$newQuizName = $this->view->getNewQuizName();	
			$newQuiz = $this->view->getNewQuizInfo();
			$message = $this->model->createNewQuiz($newQuizName, $newQuiz);
			
			$this->body = $this->sessionStatus() . $this->view->insertMessage($message);
		}
		
		return $this->view->getMainPage($this->body);
	}
	
	public function sessionStatus(){
		if($this->model->checkSessionStatus() != false){
			$username = $this->model->checkSessionStatus();	
			
			$returnstring = $this->view->getContent("Välkommen " . $username . $this->view->logoutButton());
				
			$quizArray = $this->model->getAllQuiz();
			
			foreach($quizArray as $quiz){
				self::$quizName = $quiz["quizname"]; 

				if($this->view->answerQuiz(self::$quizName)){
					//TODO: Insert function here to take user to answering quiz

					$quiz = $this->model->getSpecificQuiz(self::$quizName);
					
					$returnstring = $this->view->getContent("Välkommen " . $username . $this->view->logoutButton());
					$returnstring .= $this->view->showUserQuiz(self::$quizName, $quiz);
					$returnstring .= $this->view->backButton();
					
					return $returnstring;
				}
				
				$returnstring .= $this->view->getQuizList(self::$quizName);
			}
			
			if($this->model->checkAdminStatus($username)){
				$returnstring .= $this->view->getAdminInterface();
			}
			
			return $returnstring;
		}
		else{
			return $this->view->registerUser() . $this->view->loginUser();
		}
	}
}
