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
	private static $back ="back";
	
	// Variables used when creating a quiz
	
	private static $quizname = "quizname";
	private static $question1 = "question1";
	private static $question2 = "question2";
	private static $question3 = "question3";
	private static $q1a1 = "q1a1";
	private static $q1a2 = "q1a2";
	private static $q1a3 = "q1a3";
	private static $q2a1 = "q2a1";
	private static $q2a2 = "q2a2";
	private static $q2a3 = "q2a3";
	private static $q3a1 = "q3a1";
	private static $q3a2 = "q3a2";
	private static $q3a3 = "q3a3";
	private static $addQuiz = "addQuiz";
	
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
						<div class='wrapper'>
						
							<div class='content'>
								<img src='Images/banner2.png' />
							</div>
							" . $body . "
						</div>
					</body>
				</html>";
				
		return $html;
	}
	
	public function getLoggedInPage($body){
		$html = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
				    <head>
				    	<link rel='stylesheet' type='text/css' href='CSS/normalize.css'>
				    	<link rel='stylesheet' type='text/css' href='CSS/css.css'>
				        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
				        <title>Quiz</title>
				    </head> 	
					<body>
						<div class='wrapper'>
							<img src='Images/banner2.png' />
							<div>Välkommen " . $body . "</div>
						</div>
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
	
	public function getAdminInterface(){
		$html = "<div id='admin'>
				 	<h2>Admininterface</h2>
				 	<form action='?' method='post'>		
						<label for='Quizname' >Quiznamn :</label><br />
						<input type='text' size='30' name='" . self::$quizname . "' id='Quizname' value='' /><br />
						<label for='Question1' >Fråga 1 :</label><br />
						<input type='text' size='30' name='" . self::$question1 . "' id='Question1' value='' /><br />		 
							<div class='answers'>
								<label for='Q1A1' >Svarsalternativ 1 :</label><br />
								<input type='text' size='30' name='" . self::$q1a1 . "' id='Q1A1' value='' /><br />
								<label for='Q1A2' >Svarsalternativ 2 :</label><br />
								<input type='text' size='30' name='" . self::$q1a2 . "' id='Q1A2' value='' /><br />
								<label for='Q1A3' >Svarsalternativ 3 :</label><br />
								<input type='text' size='30' name='" . self::$q1a3 . "' id='Q1A3' value='' /><br />
							 </div>
						<label for='Question1' >Fråga 2 :</label><br />
						<input type='text' size='30' name='" . self::$question2 . "' id='Question2' value='' /><br />
							<div class='answers'>
							 	<label for='Q2A1' >Svarsalternativ 1 :</label><br />
							 	<input type='text' size='30' name='" . self::$q2a1 . "' id='Q2A1' value='' /><br />
								<label for='Q2A2' >Svarsalternativ 2 :</label><br />
								<input type='text' size='30' name='" . self::$q2a2 . "' id='Q2A2' value='' /><br />
								<label for='Q2A3' >Svarsalternativ 3 :</label><br />
								<input type='text' size='30' name='" . self::$q2a3 . "' id='Q2A3' value='' /><br />
							</div>
						 <label for='Question1' >Fråga 3 :</label><br />
						 <input type='text' size='30' name='" . self::$question3 . "' id='Question3' value='' /><br />
						 	<div class='answers'>
							 	<label for='Q3A1' >Svarsalternativ 1 :</label><br />
							 	<input type='text' size='30' name='" . self::$q3a1 . "' id='Q3A1' value='' /><br />
								<label for='Q3A2' >Svarsalternativ 2 :</label><br />
								<input type='text' size='30' name='" . self::$q3a2 . "' id='Q3A2' value='' /><br />
								<label for='Q3A3' >Svarsalternativ 3 :</label><br />
								<input type='text' size='30' name='" . self::$q3a3 . "' id='Q3A3' value='' /><br />
							</div>
						<input type='submit' name='" . self::$addQuiz . "' value='Skapa Quiz!' />
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
	
	public function getNewQuizName(){
		return $_POST[self::$quizname];
	}
	
	public function getNewQuizInfo(){
		$newQuiz = array(array($_POST[self::$question1],
						 	   $_POST[self::$q1a1],
						 	   $_POST[self::$q1a2],
						 	   $_POST[self::$q1a3]),
						 array($_POST[self::$question2],
 						 	   $_POST[self::$q2a1],
						 	   $_POST[self::$q2a2],
						 	   $_POST[self::$q2a3]),
						 array($_POST[self::$question3],
 						 	   $_POST[self::$q3a1],
						 	   $_POST[self::$q3a2],
						 	   $_POST[self::$q3a3]));
						 
		return $newQuiz;
	}
	
	public function createQuizAttempt(){
		return isset($_POST[self::$addQuiz]);
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