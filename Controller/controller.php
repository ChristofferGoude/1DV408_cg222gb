<?php
namespace Controller;

require_once("View/view.php");
require_once("Model/model.php");

class controller{
	/**
	 * @var $body (contains the html body which is sent to the view)
	 * @var $view (an instance of the view class which contain all view-related code)
	 * @var $model (an instance of the model class, runs everything towards the database layer)
	*/
	
	private $body;
	private $view;
	private $model;
	private static $quizName = "";
	private static $questionIDs = array();
	
	//Construct initiates the View and Model classes
	public function __construct(){	
		$this->view = new \View\view();
		$this->model = new \Model\model();
	}
	
	public function run(){
		//The body is formed from the sessionStatus function.
		$this->body = $this->sessionStatus();
		
		/* 
		 * Checking if the user is sending a quiz for correction.
		 * The correct quiz is then selected in order to compare the correct answers
		 * with the user answers. The user is then informed of his/her score.
		 * This information is also saved in the database. 
		 */
		if($this->view->sendQuiz()){
			self::$quizName = $this->view->getQuizName();
			$quizID = $this->model->getQuizID(self::$quizName);
			$quiz = $this->model->getSpecificQuiz(self::$quizName);
			$score = $this->view->getUserScore($quiz);
			
			$this->model->insertUserScore($quizID, $score);
			
			$this->body = $this->view->showUserScore($score);
		}
		
		/*
		 * Checking if a visitor want to register a user profile
		 * The information is checked for duplicates and the user is informed
		 * wether or not the registration is successful.
		 */
		if($this->view->registerAttempt()){
			$userInfo = $this->view->getUserRegInfo();
			$this->body .= $this->view->insertMessage($this->model->registerUser($userInfo[0], $userInfo[1]));
		}
		
		/*
		 * Checks if user wants to log in
		 * The information is verified and if successful a session is saved.
		 */
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
		
		/*
		 * Checking if user wants to logout, takes user back to the registration and login forms.
		 */
		if($this->view->logoutAttempt()){
			$this->body = $this->view->registerUser() . $this->view->loginUser();
		}
		
		/*
		 * Checks if the admin wants to create a quiz.
		 * If the creation fails for some reason the information that has been entered is saved
		 * and the admin is informed what the error was.
		 * When the creation is successful the form is wiped clean and the admin is informed of the success.
		 */
		if($this->view->createQuizAttempt()){
			$newQuizName = $this->view->getQuizName();	
			$newQuiz = $this->view->getQuizInfo();
			$message = $this->model->createNewQuiz($newQuizName, $newQuiz);
			
			if($message == $this->view->checkQuizCreation()){
				$this->view->wipeForm();
			}

			$this->body = $this->sessionStatus() . $this->view->insertMessage($message);;
		}
		
		/*
		 * Checks if admin wants to update a quiz.
		 * TODO: Fix so the form is still shown after an update has failed.
		 */
		if($this->view->updateQuizAttempt()){	
			$quizName = $this->view->getQuizName();	
			$quiz = $this->view->getQuizInfo();
			$originalQuiz = $this->model->getSpecificQuiz($quizName);
			
			foreach($originalQuiz as $question){
				array_push(self::$questionIDs, $question["questionID"]);
			}
			$message = $this->model->updateQuiz($quiz, self::$questionIDs);
			$this->view->wipeForm();

			$this->body = $this->sessionStatus() . $this->view->insertMessage($message);
		}
		
		return $this->view->getMainPage($this->body);
	}
	
	//This function handles the state of the page, and handles the dynamic list of quizzes.
	public function sessionStatus(){
		if($this->model->checkSessionStatus() != false){
				
			//If user is logged in	
			$username = $this->model->checkSessionStatus();	
			$returnstring = $this->view->getContent("Välkommen " . $username . $this->view->logoutButton());
			$quizArray = $this->model->getAllQuiz();
			
			//The availible quizzes are shown
			foreach($quizArray as $quiz){
				self::$quizName = $quiz["quizname"]; 
				
				//Dynamic handling of answering, updating, and deleting quizzes.
				if($this->view->answerQuiz(self::$quizName)){
					$quiz = $this->model->getSpecificQuiz(self::$quizName);
					
					$returnstring = $this->view->getContent("Välkommen " . $username . $this->view->logoutButton());
					$returnstring .= $this->view->showUserQuiz(self::$quizName, $quiz);
					$returnstring .= $this->view->backButton();
					
					return $returnstring;
				}
				
				if($this->view->updateQuiz(self::$quizName)){
					//Function for updating a quiz
					$quiz = $this->model->getSpecificQuiz(self::$quizName);
					$returnstring = $this->view->getContent("Välkommen " . $username . $this->view->logoutButton());
					$returnstring .= $this->view->getUpdateInterface(self::$quizName, $quiz);
					$returnstring .= $this->view->backButton();
					
					return $returnstring;
				}
				
				if($this->view->deleteQuizAttempt(self::$quizName)){				
					//$returnstring .= $this->view->confirmationForm();
					//Function for removing a quiz!
					$this->model->deleteQuiz(self::$quizName);
					header('Location: '.$_SERVER['PHP_SELF']);
				}
				
				//If an admin is logged in, the admin-menu is shown, otherwise only the quizlist is shown.
				if($this->model->checkAdminStatus($username)){
					$returnstring .= $this->view->getAdminButtons(self::$quizName);
				}
				else{
					$returnstring .= $this->view->getQuizList(self::$quizName);
				}
			}
			
			//After everything else, the form for adding a quiz is shown if an admin is logged in.
			if($this->model->checkAdminStatus($username)){
				$returnstring .= $this->view->getAdminInterface();
			}
			
			return $returnstring;
		}

		//If no user is logged in, the visitor ir presented with the registration- and loginform.
		else{
			return $this->view->registerUser() . $this->view->loginUser();
		}
	}
}
