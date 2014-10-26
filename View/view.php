<?php
namespace View;

class view{
	
	private static $login = "login";
	private static $register = "register";
	private static $registerUsername = "registerUsername";
	private static $registerPassword = "registerPassword";
	private static $loginUsername = "loginUsername";
	private static $loginPassword = "loginPassword";
	private static $logout = "logout";
	private static $back = "back";
	private static $sendQuiz = "sendQuiz";
	
	// Variables used when creating a quiz
	
	private static $quizname = "quizname";
	private static $addQuiz = "addQuiz";
	private static $numberOfQuestions = 5;
	
	/**
	 * @return String (Html)
	 */
	public function getMainPage($body){
		$html = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
				    <head>
				    	<link rel='stylesheet' type='text/css' href='CSS/normalize.css'>
				    	<link rel='stylesheet' type='text/css' href='CSS/css.css'>
				        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
				        <title>Quiz</title>
				    </head> 	
					<body>
						<div class='header'>
							<div class='wrapper'>
								<a href='#main' rel=''><img class='center' src='Images/banner.png' /></a>
							</div>
						</div>
						<div class='maincontent'>
							<a name='main' id='main'></a>
							<div class='wrapper'>
								<div class='content'>
									" . $body . "
								</div>
							</div>
						</div>
					<!-- Scripts -->		
					<script src='//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>
					<script src='Javascript/js.js'></script>
					<!-- End scripts -->
					</body>
				</html>";
				
		return $html;
	}
	
	public function registerUser(){
		$html = "<div class='content'>
				 <h2>Registrera</h2>
				 <form action='?' method='post'>		
					 <label for='User' >Användarnamn :</label>
					 <input type='text' size='30' name='" . self::$registerUsername . "' id='User' value='' />
					 <label for='PassWord' >Lösenord  :</label>
					 <input type='password' size='30' name='" . self::$registerPassword . "' id='PassWord' value='' />
					 <input type='submit' name='" . self::$register . "' value='Registrera' />
				 </form>
				 </div>";
				 
		return $html;
	}
	
	public function notLoggedIn(){
		$html = "<div class='content'>
					 <h2>Logga in</h2>
					 <form action='?' method='post'>		
						 <label for='User' >Användarnamn :</label>
						 <input type='text' size='30' name='" . self::$loginUsername . "' id='User' value='' />
						 <label for='PassWord' >Lösenord  :</label>
						 <input type='password' size='30' name='" . self::$loginPassword . "' id='PassWord' value='' />
						 <input type='submit' name='" . self::$login . "' value='Logga In' />
					 </form>
				 </div>";
		
		return $html;
	}
	
	public function verificationFailed(){
		$html = "<p>Inloggning misslyckades</p>
				 <form action='?' method='post'>
				 	<input type='submit' name='" . self::$back . "' value='Tillbaka' />
				 </form>";
	}
	
	public function getContent($content){
		$html = "<div class='content'>
		 		 	" . $content . "
		 		 </div>";		
			
		return $html;
	}
	
	public function getQuizList($content){
		$button = "<form>
				 	<input type='submit' class='quiz' name='" . $content . "' value='" . $content . "' />
				 </form>";
				 
		$html = $this->getContent($button);
		
		return $html;
	}
	
	public function showUserQuiz($quizname, $quiz){
		self::$quizname = $quizname;
		
		$html = "<h2>" . $quizname . "</h2><br />";		
		$nr = 1;
		
		foreach($quiz as $question){
			$html .= "<p><b>Fråga " . $nr . ": </b>" . $question["question"] . "</p><br />
					  <p><b>Svarsalternativ:</b> " . $question["answer1"] . " " . $question["answer2"] . " " . $question["answer3"] . "</p>
					  <form>
					  	  <input type='text' size='30' name='" . $question["question"] . "' id='' value='' />
			 	  	  </form>";
			$nr++;
		}
		
		$html .= "<form action='?' method='post'>
					  <input type='submit' class='' name='" . self::$sendQuiz . "' value='Skicka svar!' />
			 	  </form>";
		
		return $html;
	}
	
	public function getAdminInterface(){
		$html = "<div id='admin'>
				 	<h2>Admininterface</h2>
				 	<form action='?' method='post'>		
						<label for='Quizname' >Quiznamn :</label><br />
						<input type='text' size='30' name='" . self::$quizname . "' id='Quizname' value='' /><br />";
						
						for($i = 1; $i <= self::$numberOfQuestions; $i++){
							$html .= $this->createNewQuestion($i);
						}
						
			$html .= "<input type='submit' name='" . self::$addQuiz . "' value='Skapa Quiz!' />
					      </form>
			 		  </div>";		
			
		return $html;
	}
	
	//FUNCTIONS USED WHEN REGISTRING NEW USER
	
	public function registerAttempt(){
		return isset($_POST[self::$register]);
	}
	
	public function getUserRegInfo(){
		$userInfo = array($_POST[self::$registerUsername], $_POST[self::$registerPassword]);
		
		return $userInfo;	
	}

	
	//FUNCTIONS USED WHEN LOGGING IN A USER
		
	public function loginAttempt(){
		return isset($_POST[self::$login]);
	}
	
	public function getUserLoginInfo(){
		$userInfo = array($_POST[self::$loginUsername], $_POST[self::$loginPassword]);
		
		return $userInfo;	
	}
	
	//FUNCTIONS USED WHEN LOGGING OUT
	
	public function logoutAttempt(){
		if(isset($_POST[self::$logout])){
			session_unset();
    		session_destroy();
		}
		
		return isset($_POST[self::$logout]);
	}
	
	public function logoutButton(){
		return "<div>
					<form action='?' method='post'>
						<input type='submit' name='" . self::$logout . "' value='Logga ut' />
					</form>
				</div>";
	}
	
	//FUNCTIONS USED WHEN CREATING A QUIZ
	
	public function createNewQuestion($i){				
		$html = "<label for='Question" . $i . "'>Fråga " . $i . ":</label><br />
				 <input type='text' size='30' name='Question" . $i . "' id='Question" . $i . "' value='' /><br />		 
				 <div class='answers'>
					 <label for='Q" . $i . "AO1' >Svarsalternativ 1 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO1' id='Q" . $i . "AO1' value='' /><br />
					 <label for='Q" . $i . "AO2' >Svarsalternativ 2 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO2' id='Q" . $i . "AO2' value='' /><br />
					 <label for='Q" . $i . "AO3' >Svarsalternativ 3 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO3' id='Q" . $i . "AO3' value='' /><br />
					 <label for='Q" . $i . "A' >Korrekt svar :</label><br />
					 <input type='text' size='30' name='Q" . $i . "A' id='Q" . $i . "A' value='' /><br />
			 	 </div>";
				 
		return $html;
	}

	public function getNewQuizName(){
		return $_POST[self::$quizname];
	}
	
	public function getNewQuizInfo(){
		$newQuiz = array();		
		for($i = 1; $i <= self::$numberOfQuestions; $i++){
			$question = array($_POST["Question" . $i],
		 	      			  $_POST["Q" . $i . "AO1"],
		 	      			  $_POST["Q" . $i . "AO2"],
		 	      			  $_POST["Q" . $i . "AO3"],
		 	      			  $_POST["Q" . $i . "A"]);
		 	      			  
			array_push($newQuiz, $question);
		}
						 
		return $newQuiz;
	}
	
	public function createQuizAttempt(){
		return isset($_POST[self::$addQuiz]);
	}
	
	//FUNCTIONS USED WHEN ANSWERING A QUIZ
	
	public function answerQuiz($quizname){
		return isset($_GET[$quizname]);
	}
	
	public function sendQuiz(){
		return isset($_POST[self::$sendQuiz]);
	}
	
	public function getAllAnswers($quiz){
		
	}
	
	//BACK-BUTTON
	
	public function back(){
		return isset($_POST[self::$back]);
	}
	
	public function backButton(){
		return "<div>
					<form action='?' method='post'>
						<input type='submit' name='" . self::$back . "' value='Tillbaka' />
					</form>
				</div>";		
	}
}