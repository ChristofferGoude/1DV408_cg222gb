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
	private static $yes = "yes";
	private static $no = "no";
	
	// Variables used when creating a quiz
	
	private static $quizname = "quizname";
	private static $addQuiz = "addQuiz";
	private static $updateQuiz = "updateQuiz";
	private static $numberOfQuestions = 5;
	
	/**
	 * @return String (Html)
	 */
	public function getMainPage($body){
		$html = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
				    <head>
				    	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
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
					 <input type='text' size='30' maxlength='20' name='" . self::$registerUsername . "' id='User' value='" .  $this->getUserRegName() . "' />
					 <label for='PassWord' >Lösenord  :</label>
					 <input type='password' size='30' maxlength='20' name='" . self::$registerPassword . "' id='PassWord' value='' />
					 <input type='submit' name='" . self::$register . "' value='Registrera' />
				 </form>
				 </div>";
				 
		return $html;
	}
	
	public function loginUser(){
		$html = "<div class='content'>
					 <h2>Logga in</h2>
					 <form action='?' method='post'>		
						 <label for='User' >Användarnamn :</label>
						 <input type='text' size='30' maxlength='20' name='" . self::$loginUsername . "' id='User' value='" . $this->getUserLoginName() . "' />
						 <?php echo 'TEST'; ?>
						 <label for='PassWord' >Lösenord  :</label>
						 <input type='password' size='30' maxlength='20' name='" . self::$loginPassword . "' id='PassWord' value='' />
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
	
	public function getAdminButtons($content){
		$buttons = "<form>
				 		<input type='submit' class='quiz' name='" . $content . "' value='" . $content . "' />
				    </form>
				    <form>
				 		<input type='submit' class='delete' name='delete" . $content . "' value='X' />
				    </form>
				    <form>
				 		<input type='submit' class='update' name='update" . $content . "' value='U' />
				    </form>";
		
		$html = $this->getContent($buttons);
		
		return $html;
	}
	
	public function showUserQuiz($quizname, $quiz){
		$_POST[self::$quizname] = $quizname;
		
		$html = "<h2>" . $quizname . "</h2><br />
				 <form action='?' method='post'>
				 <input type='hidden' size='30' name='" . self::$quizname . "' id='Quizname' value='" . $quizname . "' />";		
		$nr = 1;
		
		foreach($quiz as $question){
			
			$html .= "<p><b>Fråga " . $nr . ": </b>" . $question["question"] . "</p>
					  <p><b>Svarsalternativ:<br /><br /></b>
					  <input type='radio' name='" . $nr . "' value='" . $question["answer1"] . "' /> " . $question["answer1"] . "<br />
				  	  <input type='radio' name='" . $nr . "' value='" . $question["answer2"] . "' /> " . $question["answer2"] . "<br />
				  	  <input type='radio' name='" . $nr . "' value='" . $question["answer3"] . "' /> " . $question["answer3"] . "<br />";
			$nr++;
		}
		
		$html .= "<br />
				  	  <input type='submit' class='' name='" . self::$sendQuiz . "' value='Skicka svar!' />
			 	  </form>";
		
		return $html;
	}
	
	public function getUserScore($quiz){
		$score = 0;
		$nr = 1;

		foreach($quiz as $question){
			if(isset($_POST[$nr])){		
				if($question["correctAnswer"] == $_POST[$nr]){				
					$score++;
				}
			}
			$nr++;
		}
		
		return $score;
	}
	
	public function showUserScore($score){
		$content = "<p>Du fick <b>" . $score . "</b> poäng av " . self::$numberOfQuestions . " möjliga!</p>";
		
		if($score == 0){
			$content .= "<p>Du får nog tänka efter lite mer nästa gång!</p>";
		}
		else if($score > 0 && $score < (self::$numberOfQuestions*0.8)){
			$content .= "<p>Det där gick ju nästan bra! Klura lite mer och pröva igen!</p>";
		}
		else if($score == (self::$numberOfQuestions*0.8)){
			$content .= "<p>Riktigt bra! Nästan full pott!</p>";
		}
		else if($score == self::$numberOfQuestions){
			$content .= "<p>Fantastiskt, full pott!</p>";
		} 
		
		$content .= $this->backButton();
		$html = $this->getContent($content);
		
		return $html;
	}
	
	public function getAdminInterface(){
		$html = "<div id='admin'>
				 	<h2>Admininterface</h2>
				 	<form action='?' method='post'>		
						<label for='Quizname' >Quiznamn :</label><br />
						<input type='text' size='30' name='" . self::$quizname . "' id='Quizname' value='" . $this->getQuizName() . "' /><br />";
						
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

	public function getUserRegName(){
		if(isset($_POST[self::$registerUsername])){
			return $_POST[self::$registerUsername];
		}
		else{
			return "";
		}
	}
	
	//FUNCTIONS USED WHEN LOGGING IN A USER
		
	public function loginAttempt(){
		return isset($_POST[self::$login]);
	}
	
	public function getUserLoginInfo(){
		$userInfo = array($_POST[self::$loginUsername], $_POST[self::$loginPassword]);
		
		return $userInfo;	
	}
	
	public function getUserLoginName(){
		if(isset($_POST[self::$loginUsername])){
			return $_POST[self::$loginUsername];
		}
		else{
			return "";
		}
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
		return "<div class='extramargin'>
					<form action='?' method='post'>
						<input type='submit' name='" . self::$logout . "' value='Logga ut' />
					</form>
				</div>";
	}
	
	//FUNCTIONS USED WHEN CREATING A QUIZ
	
	public function createNewQuestion($i){				
		$html = "<label for='Question" . $i . "'>Fråga " . $i . ":</label><br />
				 <input type='text' size='30' name='Question" . $i . "' id='Question" . $i . "' value='" . $this->getQuestionInfo("Question" . $i) . "' /><br />		 
				 <div class='answers'>
					 <label for='Q" . $i . "AO1' >Svarsalternativ 1 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO1' id='Q" . $i . "AO1' value='" . $this->getQuestionInfo("Q" . $i . "AO1") . "' /><br />
					 <label for='Q" . $i . "AO2' >Svarsalternativ 2 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO2' id='Q" . $i . "AO2' value='" . $this->getQuestionInfo("Q" . $i . "AO2") . "' /><br />
					 <label for='Q" . $i . "AO3' >Svarsalternativ 3 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO3' id='Q" . $i . "AO3' value='" . $this->getQuestionInfo("Q" . $i . "AO3") . "' /><br />
					 <label for='Q" . $i . "A' >Korrekt svar :</label><br />
					 <input type='text' size='30' name='Q" . $i . "A' id='Q" . $i . "A' value='" . $this->getQuestionInfo("Q" . $i . "A") . "' /><br />
			 	 </div>";
				 
		return $html;
	}

	public function getQuizName(){
		if(isset($_POST[self::$quizname])){
			return $_POST[self::$quizname];
		}
		else{
			return "";
		}
	}
	
	public function getQuizInfo(){
		$quiz = array();		
		for($i = 1; $i <= self::$numberOfQuestions; $i++){
			$question = array($_POST["Question" . $i],
		 	      			  $_POST["Q" . $i . "AO1"],
		 	      			  $_POST["Q" . $i . "AO2"],
		 	      			  $_POST["Q" . $i . "AO3"],
		 	      			  $_POST["Q" . $i . "A"]);
		 	      			  
			array_push($quiz, $question);
		}
						 
		return $quiz;
	}
	
	public function getQuestionInfo($input){
		if(isset($_POST[$input])){
			return $_POST[$input];
		}
		else{
			return "";
		}
	}
	
	public function wipeForm(){
		if(isset($_POST[self::$quizname])){
			$_POST[self::$quizname] = "";
		}		
			
		for($i = 1; $i <= self::$numberOfQuestions; $i++){
			if(isset($_POST["Question" . $i])){
				$_POST["Question" . $i] = "";
			}
			if(isset($_POST["Q" . $i . "AO1"])){
				$_POST["Q" . $i . "AO1"] = "";
			}
			if(isset($_POST["Q" . $i . "AO2"])){
				$_POST["Q" . $i . "AO2"] = "";
			}
			if(isset($_POST["Q" . $i . "AO2"])){
				$_POST["Q" . $i . "AO2"] = "";
			}
			if(isset($_POST["Q" . $i . "AO3"])){
				$_POST["Q" . $i . "AO3"] = "";
			}
			if(isset($_POST["Q" . $i . "A"])){
				$_POST["Q" . $i . "A"] = "";
			}
		}
	}
	
	public function createQuizAttempt(){
		return isset($_POST[self::$addQuiz]);
	}
	
	public function checkQuizCreation(){
		return "Ditt quiz skapades!";
	}
	
	//FUNCTIONS USED WHEN ANSWERING A QUIZ
	
	public function answerQuiz($quizname){
		return isset($_GET[$quizname]);
	}
	
	public function sendQuiz(){
		return isset($_POST[self::$sendQuiz]);
	}
	
	// FUNCTIONS USED WHEN DELETING A QUIZ
	
	public function deleteQuizAttempt($quizname){
		return isset($_GET["delete" . $quizname]);
	}
	
	// FUNCTIONS USED WHEN UPDATING A QUIZ
	
	public function updateQuiz($quizname){
		return isset($_GET["update" . $quizname]);
	}
	
	public function updateQuizAttempt(){
		return isset($_POST[self::$updateQuiz]);
	}
	
	public function getUpdateInterface($quizname, $quiz){
		$i = 1;
		$html = "<div id='admin'>
				 	<h2>Uppdatera " . $quizname . "</h2>
				 	<form action='?' method='post'>	
						<input type='hidden' size='30' name='" . self::$quizname . "' id='Quizname' value='" . $quizname . "' /><br />";
						
						foreach($quiz as $question){	
							$html .= $this->updateQuestion($i, $question);
							$i++;
						}
						
			$html .= "<input type='submit' name='" . self::$updateQuiz . "' value='Uppdatera Quiz!' />
					      </form>
			 		  </div>";		
			
		return $html;
	}
	
	public function updateQuestion($i ,$question){
		$html = "<label for='Question" . $i . "'>Fråga " . $i . ":</label><br />
				 <input type='text' size='30' name='Question" . $i . "' id='Question" . $i . "' value='" . $question["question"] . "' /><br />		 
				 <div class='answers'>
					 <label for='Q" . $i . "AO1' >Svarsalternativ 1 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO1' id='Q" . $i . "AO1' value='" . $question["answer1"] . "' /><br />
					 <label for='Q" . $i . "AO2' >Svarsalternativ 2 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO2' id='Q" . $i . "AO2' value='" . $question["answer2"] . "' /><br />
					 <label for='Q" . $i . "AO3' >Svarsalternativ 3 :</label><br />
					 <input type='text' size='30' name='Q" . $i . "AO3' id='Q" . $i . "AO3' value='" . $question["answer3"] . "' /><br />
					 <label for='Q" . $i . "A' >Korrekt svar :</label><br />
					 <input type='text' size='30' name='Q" . $i . "A' id='Q" . $i . "A' value='" . $question["correctAnswer"] . "' /><br />
			 	 </div>";
				 
		return $html;
	}
	
	public function checkQuizUpdate(){
		return "Ditt quiz uppdaterades!";
	}
	
	//BACK-BUTTON
	
	public function back(){
		return isset($_POST[self::$back]);
	}
	
	public function backButton(){
		return "<div class='extramargin'>
					<form action='?' method='post'>
						<input type='submit' name='" . self::$back . "' value='Tillbaka' />
					</form>
				</div>";		
	}
	
	public function insertMessage($content){
		$html = "<div id='message'>
				 	" . $content . "
				 </div>";
				 
		return $html;
	}
	
	//CONFIRMATION FORM FOR DELETING AND UPDATING
	
	public function confirmationForm(){
		return "<p>Är du säker på detta?</p>
				<form action='?' method='post'>
					<input type='submit' name='" . self::$yes . "' value='Ja' />
					<input type='submit' name='" . self::$no . "' value='Nej' />
				</form>";
	}
	
	public function confirmed(){	
		return isset($_POST[self::$yes]);
	}
	
	public function denied(){
		return isset($_POST[self::$no]);
	}
}