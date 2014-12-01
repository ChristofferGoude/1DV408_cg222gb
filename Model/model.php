<?php
namespace Model;

require_once("Model/dal.php");

class model{
	
	/*
	 * @var $db (the variable used for database layer)
	 * @var $session (Variable used for saving the session)
	 */
	private $db;
	private static $session = "";
	
	// construct initiation the data access layer
	public function __construct(){
		$this->db = new \Model\dal();
	}
	
	/*
	 * This function checks if a session is set.
	 * If it is, returns the session value, otherwise returns false.
	 */ 
	public function checkSessionStatus(){		
		if(isset($_SESSION[self::$session])){
			return $_SESSION[self::$session];
		}
		else {
			return false;
		}
	}
	
	// Checking admin status on user via DAL.
	public function checkAdminStatus($username){
		return $this->db->checkAdminStatus($username);
	}
	
	// Registring user in the database.
	public function registerUser($username, $password){
		return $this->db->registerUser($username, $password);
	}
	
	// Checking user credentials via database and sets session if login is successful.
	public function loginUser($username, $password){
		$loginResult = $this->db->loginUser($username, $password);
		if($loginResult == true){
			$_SESSION[self::$session] = $username;
		}
		
		return $loginResult;	
	}
	
	// Returns all availible quizzes.
	public function getAllQuiz(){
		return $this->db->getAllQuiz();
	}
	
	// Returns a quizID from an existing quizname.
	public function getQuizID($quizname){
		return $this->db->getQuizID($quizname);
	}
	
	// Returns the questions and answers for a specific quiz from the quizname.
	public function getSpecificQuiz($quizname){
		return $this->db->getSpecificQuiz($quizname);
	}
	
	//Inserts a new quiz into the database.
	public function createNewQuiz($newQuizName, $newQuiz){
		return $this->db->createNewQuiz($newQuizName, $newQuiz);
	}
	
	// Removes all information regarding a quiz in the database.
	public function deleteQuiz($quizname){
		return $this->db->deleteQuiz($quizname);
	}
	
	// Updating a specific quiz.
	public function updateQuiz($quiz, $questionIDs){
		return $this->db->updateQuiz($quiz, $questionIDs);
	}
	
	// Inserting a users quiz score into the database.
	public function insertUserScore($quizID, $points){
		return $this->db->insertUserScore($quizID, $points);
	}
}
