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
	
	public function getAllQuiz(){
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
	
	public function getSpecificQuiz($quizname){
		try{		
			$this->createConnection();
			
			$sql = "SELECT quizID FROM quiz WHERE quizname = :quizname";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizname", $quizname);
			$query->execute();
		
			$quizID = $query->fetchColumn(0);
			
			$sql = "SELECT question, answer1, answer2, answer3, correctAnswer FROM questions WHERE quizID = :quizID";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizID", $quizID);
			$query->execute();
			
			$quiz = $query->fetchAll(\PDO::FETCH_ASSOC);
			
			self::$dbh = null;
			
			return $quiz;
		}
		catch (PDOException $e){
			return "Databasqueryn misslyckades. " . $e->getMessage();
		}
	}
	
	public function createNewQuiz($newQuizName, $newQuiz){
		$quizID = "";	
			
		try{
			if(false){
				throw new \PDOException("Du måste ange både användarnamn och lösenord!");
			}
			
			$this->createConnection();	
			
			$sql = "INSERT INTO quiz (quizname) VALUES (:quizname)";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizname", $newQuizName);
			$query->execute();
			$quizID = self::$dbh->lastInsertId();
			
			foreach($newQuiz as $question){  
				$sql = "INSERT INTO questions (quizID, question, answer1, answer2, answer3, correctAnswer) VALUES (:quizID, :question, :answer1, :answer2, :answer3, :correctAnswer)";	
				$query = self::$dbh->prepare($sql);
				$query->bindParam(":quizID", $quizID);
				$query->bindParam(":question", $question[0]);
				$query->bindParam(":answer1", $question[1]);
				$query->bindParam(":answer2", $question[2]);
				$query->bindParam(":answer3", $question[3]);
				$query->bindParam(":correctAnswerw", $question[4]);
				$query->execute();	  		
			}
			
			self::$dbh = null;
							  
			return "Ditt quiz skapades!";
		}
		catch (\PDOException $e){
			return "Det gick inte att registrera dig. " . $e->getMessage();
		}
	}
}
