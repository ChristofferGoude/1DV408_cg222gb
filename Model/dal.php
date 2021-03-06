<?php
namespace Model;

class dal{
	private static $dbh = "";
	private static $hostname = "quiz-166006.mysql.binero.se";
	private static $localhost = "127.0.0.1";
	private static $dbname = "166006-quiz";
	private static $user = "166006_bz22041";
	private static $pass = "*";
	private static $session = "";
	
	public function __construct(){
		//TODO: Data access
	}

	public function createConnection(){	
		try {
		    self::$dbh = new \PDO("mysql:host=" . self::$hostname . ";dbname=" . self::$dbname . "", self::$user, self::$pass, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
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
			if(!($this->checkForTags($username)) || !($this->checkForTags($password))){
				throw new \PDOException("Användarnamnet eller lösenordet får inte innehåller taggar av säkerhetsskäl.");
			}
			
			if($username == "" || $password == ""){
				$errortext = "Följande information saknas: ";
				
				if($username == ""){
					$errortext .= "Användarnamn ";
				}
				if($password == ""){
					$errortext .= "Lösenord ";
				}
				
				throw new \PDOException($errortext);
			}
			
			if($this->checkUserNameExists($username)){
				throw new \PDOException("Detta användarnamn finns redan registrerat. Var god välj ett annat namn.");
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
			return $e->getMessage();
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
				return true;
			}	
		}		
		catch (\PDOException $e){
			return false;//"Det gick inte att logga in dig. " . $e->getMessage();
		}	  
	}
	
	public function checkUserNameExists($username){
		try{					
			$this->createConnection();	
			
			$sql = "SELECT username FROM users WHERE username = :username";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":username", $username);
			$query->execute();
			
			self::$dbh = null;
							  
			if($query->rowCount() > 0){
				return true;
			}	
			else{
				return false;
			}	
		}		
		catch (\PDOException $e){
			return "Ett oväntat fel uppstog.";
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
			return "Databasqueryn misslyckades.";
		}
	}
	
	public function getQuizID($quizname){
		try{		
			$this->createConnection();
			
			$sql = "SELECT quizID FROM quiz WHERE quizname = :quizname";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizname", $quizname);
			$query->execute();
		
			$quizID = $query->fetchColumn(0);
			
			self::$dbh = null;
			
			return $quizID;
		}
		catch (PDOException $e){
			return "Databasqueryn misslyckades. " . $e->getMessage();
		}
	}
	
	public function checkQuizNameExists($quizname){
		try{					
			$this->createConnection();	
			
			$sql = "SELECT quizname FROM quiz WHERE quizname = :quizname";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizname", $quizname);
			$query->execute();
			
			self::$dbh = null;
							  
			if($query->rowCount() > 0){
				return true;
			}	
			else{
				return false;
			}	
		}		
		catch (\PDOException $e){
			return "Ett oväntat fel uppstog.";
		}	
	}
	
	public function getSpecificQuiz($quizname){		
		try{			
			$quizID = $this->getQuizID($quizname);
			
			$this->createConnection();
			
			$sql = "SELECT questionID, question, answer1, answer2, answer3, correctAnswer FROM questions WHERE quizID = :quizID";	
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
			if($newQuizName == ""){
				throw new \PDOException("Du måste ange ett quiznamn!");
			}
			if(preg_match('/\s/', $newQuizName)){
				throw new \PDOException("Quiznamnet får inte innehålla mellanslag!");
			}
			if($this->checkQuizNameExists($newQuizName)){
				throw new \PDOException("Quiznamnet är redan använt! Välj ett annat namn.");
			}
			
			foreach($newQuiz as $question){
				foreach($question as $entry){
					if($entry == ""){
						throw new \PDOException("Du måste fylla i samtliga frågor, svar, och svarsalternativ!");
					}
					else if(!($this->checkForTags($entry))){
						throw new \PDOException("Av säkerhetsskäl får du inte ha taggar med i quizen!");
					}
					
					if($question[1] != $question[4] && $question[2] != $question[4] && $question[3] != $question[4]){	
						throw new \PDOException("Det rätta svaret måste vara samma som något av svarsalternativen på frågan '" . $question[0] . "'.");
					}
				}
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
				$query->bindParam(":correctAnswer", $question[4]);
				$query->execute();	  		
			}
			
			self::$dbh = null;
							  
			return "Ditt quiz skapades!";
		}
		catch (\PDOException $e){
			return "Det gick inte att skapa ditt quiz. " . $e->getMessage();
		}
	}

	public function updateQuiz($quiz, $questionIDs){		
		try{	
			foreach($quiz as $question){
				foreach($question as $entry){
					if($entry == ""){
						throw new \PDOException("Du måste fylla i samtliga frågor, svar, och svarsalternativ!");
					}
					else if(!($this->checkForTags($entry))){
						throw new \PDOException("Av säkerhetsskäl får du inte ha taggar med i quizen!");
					}
					
					if($question[1] != $question[4] && $question[2] != $question[4] && $question[3] != $question[4]){	
						throw new \PDOException("Det rätta svaret måste vara samma som något av svarsalternativen på frågan '" . $question[0] . "'.");
					}
				}
			}
			
			$this->createConnection();	
			$i = 0;
			
			foreach($quiz as $question){  
				$sql = "UPDATE questions 
						SET question = :question, 
							answer1 = :answer1, 
							answer2 = :answer2, 
							answer3 = :answer3, 
							correctAnswer = :correctAnswer
						WHERE questionID = :questionID";	
				$query = self::$dbh->prepare($sql);
				$query->bindParam(":questionID", $questionIDs[$i]);
				$query->bindParam(":question", $question[0]);
				$query->bindParam(":answer1", $question[1]);
				$query->bindParam(":answer2", $question[2]);
				$query->bindParam(":answer3", $question[3]);
				$query->bindParam(":correctAnswer", $question[4]);
				$query->execute();	  		
				
				$i++;
			}
			
			self::$dbh = null;
							  
			return "Ditt quiz uppdaterades!";
		}
		catch (\PDOException $e){
			return "Det gick inte att uppdatera ditt quiz. " . $e->getMessage();
		}
	}

	public function deleteQuiz($quizname){
		try{			
			$quizID = $this->getQuizID($quizname);
			
			$this->createConnection();
			
			$sql = "DELETE FROM questions WHERE quizID = :quizID";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizID", $quizID);
			$query->execute();
			
			$sql = "DELETE FROM scores WHERE quizID = :quizID";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizID", $quizID);
			$query->execute();
			
			$sql = "DELETE FROM quiz WHERE quizID = :quizID";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizID", $quizID);
			$query->execute();
			
			self::$dbh = null;
			
			return true;
		}
		catch (PDOException $e){
			return false;
		}
	}

	public function insertUserScore($quizID, $score){
		try{
			$this->createConnection();	
			
			$sql = "INSERT INTO scores (quizID, score) VALUES (:quizID, :score)";	
			$query = self::$dbh->prepare($sql);
			$query->bindParam(":quizID", $quizID);
			$query->bindParam(":score", $score);
			$query->execute();
							  
			self::$dbh = null;
							  
			return "Din användare skapades!";
		}
		catch (\PDOException $e){
			return "Det gick inte att registrera dig. " . $e->getMessage();
		}
	}
	
	public function checkForTags($string){
		if(strip_tags($string) == $string){
			return true;
		}
		else{
			return false;
		}
		
	}
}
