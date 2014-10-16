<?php
namespace Model;

require_once("Model/dal.php");

class model{
	//TODO: Everything
	
	private $db;
	private static $session = "";
	
	public function __construct(){
		//TODO: Initiation
		
		$this->db = new \Model\dal();
	}
	
	public function checkSessionStatus(){
		//TODO: Check if a session is in use
		
		if(isset($_SESSION[self::$session])){
			return $_SESSION[self::$session];
		}
		else {
			return false;
		}
	}
	
	public function checkAdminStatus($username){
		return $this->db->checkAdminStatus($username);
	}
	
	public function registerUser($username, $password){
		return $this->db->registerUser($username, $password);
	}
	
	public function loginUser($username, $password){
		$loginResult = $this->db->loginUser($username, $password);	
		
		if($loginResult != false){
			$_SESSION[self::$session] = $username;
		}
		
		return $loginResult;	
	}
	
	public function getQuiz(){
		return $this->db->getQuiz();
	}
	
	public function createNewQuiz($newQuizName, $newQuiz){
		return $this->db->createNewQuiz($newQuizName, $newQuiz);
	}
}
