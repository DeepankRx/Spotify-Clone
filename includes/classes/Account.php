<?php
	class Account {
		//connection variable
		private $conn;
        //array for storing errors
		private $errorArray;
        //constructor
		public function __construct($conn) {
			$this->conn = $conn;
			$this->errorArray = array();
		} 

		//login function
		public function login($un,$pw)
		{
			$pw = md5($pw);
			$query = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$un' AND password='$pw'"); 
			if(mysqli_num_rows($query)==1)
			{
				return true;
			}
			else
			{
				array_push($this->errorArray,Constants::$loginFailed);
			}
		}

        //a function to check if any validation fails
		public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
            //this keywords is used to tell that the given variable/function is of same instance
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmails($em, $em2);
			$this->validatePasswords($pw, $pw2);
            //if errorArray is empty that means no error occurred while validation
			if(empty($this->errorArray) == true) {
				//Insert into db
				return $this->insertUserDetails($un,$fn,$ln,$em,$pw);
			}
			else {
				return false;
			}
		}

		//function to insert data into database
		private function insertUserDetails($un,$fn,$ln,$em,$pw)
		{
				$encryptedPw = md5($pw);
				$profilePic = "assets/images/profile-pic/profilePic.jpg";
				$date =date("Y-m-d");
				$query = "INSERT INTO users VALUES ('','$un','$fn','$ln','$em','$encryptedPw','$date','$profilePic')";
				$result = mysqli_query($this->conn,$query);
				return $result; 
		}

        //this function will check if a error is in array or not
		public function getError($error) {
			if(!in_array($error, $this->errorArray)) {
                //if the error in array is not present than make it "" i.e empty string
				$error = "";
			} //return error 
			return "<span class='errorMessage'>$error</span>";
		}

		private function validateUsername($un) {

			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constants::$usernameCharacters);
				return;
			}

			// check if username exists
			$query2 ="SELECT username FROM users WHERE username='$un'";
			$checkUsernameQuery = mysqli_query($this->conn,$query2); 
			if(mysqli_num_rows($checkUsernameQuery)!=0)
			{
				array_push($this->errorArray, Constants::$usernameTaken);
				return;
			}

		}
        //validation functions
		private function validateFirstName($fn) {
			if(strlen($fn) > 25 || strlen($fn) < 2) {
                //if validation occurs push the msg into the array
            //calling variable from class Constants
				array_push($this->errorArray, Constants::$firstNameCharacters);
				return;
			}
            
		}

		private function validateLastName($ln) {
			if(strlen($ln) > 25 || strlen($ln) < 2) {
				array_push($this->errorArray, Constants::$lastNameCharacters);
				return;
			}
		}

		private function validateEmails($em, $em2) {
			if($em != $em2) {
				array_push($this->errorArray, Constants::$emailsDoNotMatch);
				return;
			}
             //filter_var and FILTER_VALIDATE_EMAIL is used to validate the email as if it contains a valid email address
			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray, Constants::$emailInvalid);
				return;
			}

			// Check that email hasn't already been used
			$query3 ="SELECT email FROM users WHERE email='$em'";
			$checkUsernameQuery = mysqli_query($this->conn,$query3); 
			if(mysqli_num_rows($checkUsernameQuery)!=0)
			{
				array_push($this->errorArray, Constants::$emailTaken);
				return;
			}

		}

		private function validatePasswords($pw, $pw2) {
			
			if($pw != $pw2) {
				array_push($this->errorArray, Constants::$passwordsDoNoMatch);
				return;
			}
                  //preg_match is used for regular expressions 
        //if the password matches the given condition that is the password must be alpha numeric
			if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray, Constants::$passwordCharacters);
				return;
			}

		}


	}
?>