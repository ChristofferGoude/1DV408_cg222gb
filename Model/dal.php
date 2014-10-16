<?php
namespace Model;

class dal{
	//TODO: Everything
	
	private static $dbh = "";
	private static $hostname = "quiz-166006.mysql.binero.se";
	private static $localhost = "127.0.0.1";
	private static $dbname = "166006-quiz";
	private static $user = "166006_bz22041";
	private static $pass = "Lillstrumpa1";
	private static $session = "";
	
	public function __construct(){
		//TODO: Data access
	}

	public function createConnection(){	
		try {
		    self::$dbh = new \PDO("mysql:host=" . self::$localhost . ";dbname=" . self::$dbname . "", self::$user, self::$pass);
			self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);		
			
			return self::$dbh;
		} 
		catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function checkAdminStatus($username){
		try{
			if($username == ""){
				throw new \PDOException("Du måste ange både användarnamn och lösenord!");
			}
			
			$this->createConnection();	
			
			$sql = "SELECT usertype FROM users WHERE username = :username";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":username", $username);
			$query->execute();
							  
			self::$dbh = null;
							  
			$usertype = $query->fetchColumn(0);

			if($usertype == "admin"){
				return true;
			}
			else{
				return false;
			}
		}
		catch (\PDOException $e){
			return "Databasqueryn misslyckades. " . $e->getMessage();
		}
	}
	
	public function registerUser($username, $password){		
		try{
			if($username == "" || $password == ""){
				throw new \PDOException("Du måste ange både användarnamn och lösenord!");
			}
			
			$this->createConnection();	
			
			$sql = "INSERT INTO users (username,password) VALUES (:username,:password)";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":username", $username);
		  	$query->bindParam(":password", $password);
			$query->execute();
							  
			self::$dbh = null;
							  
			return "Din användare skapades!";
		}
		catch (\PDOException $e){
			return "Det gick inte att registrera dig. " . $e->getMessage();
		}
	}
	
	public function loginUser($username, $password){
		try{
			if($username == "" || $password == ""){
				throw new \PDOException("Du måste ange både användarnamn och lösenord!");
			}	
					
			$this->createConnection();	
			
			$sql = "SELECT username FROM users WHERE username = :username AND password = :password";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":username", $username);
		  	$query->bindParam(":password", $password);
			$query->execute();
			
			self::$dbh = null;
							  
			if($query->rowCount() > 0){
				return $username;
			}	
			else{
				return false;
			}	
		}		
		catch (\PDOException $e){
			return "Det gick inte att logga in dig. " . $e->getMessage();
		}	  
	}
	
	public function getQuiz(){
		try{		
			$this->createConnection();
			
			$sql = "SELECT quizID, quizname FROM quiz";	
			$query = self::$dbh->prepare($sql);
			$query->execute();
		
			$quiz = $query->fetchAll();
			
			self::$dbh = null;
			
			return $quiz;
		}
		catch (PDOException $e){
			return "Databasqueryn misslyckades. " . $e->getMessage();
		}
	}
}
