<?php

class Account
{

    private $con;
    private $errorArray;

    public function __construct($con)
    {
        $this->con = $con;//sets database connection from config.php
        $this->errorArray = array();
    }

    public function login($un, $pw){
        //compare same encrypted password. outputs same md5 password
        $pw = md5($pw);

        $query = mysqli_query($this->con, "SELECT * FROM users WHERE username = '$un' AND password = '$pw'");

        //if 1 result is found that matches
        if(mysqli_num_rows($query) == 1){
            return true;

        } else {
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }

    public function register($un, $fn, $ln, $em, $em2, $pw, $pw2)
    {
        //validates all inputs
        $this->validateUsername($un);
        $this->validateFirstName($fn);
        $this->validateLastname($ln);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        //If there are no errors
        if (empty($this->errorArray)){
            //insert into db
            return  $this->insertUserDetails($un, $fn, $ln, $em, $pw); // gets parameters from user input via register()
        }else {
            return false;
        }
    }

    public function getError($error) {
        //checks if error given is in the errorArray
        if(!in_array($error, $this->errorArray)){
            $error = "";
        }
        return "<span class='errorMessage'>$error</span>";
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw){
        $encryptedPW = md5($pw); //encrypts password
        $profilePic = "assets/images/profile-pics/car.jpg";
        $date = date("Y-m-d");//google php date formats
        $result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptedPW', '$date', '$profilePic')"); // has to be in order as the database

        return $result;
    }

    private function validateUsername($un)
    {
        //inserts error message in array if don't meet requirements
        if (strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }
        //check if user exists
        $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username = '$un'");
        if(mysqli_num_rows($checkUsernameQuery) != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
            return;
}
    }

    private function validateFirstName($fn)
    {
        //inserts error message in array if don't meet requirements
        if (strlen($fn) > 25 || strlen($fn) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
            return;
        }

    }

    private function validateLastName($ln)
    {
        //inserts error message in array if don't meet requirements
        if (strlen($ln) > 25 || strlen($ln) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
            return;
        }
    }

    private function validateEmails($em, $em2)
    {
        //checks if emails match
        if ($em != $em2) {
            array_push($this->errorArray, Constants::$emailsDoNotMatch);
            return;
        }
        //checks if the email is in correct format
        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }
        //check if email exists
        $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email = '$em' ");
        if(mysqli_num_rows($checkEmailQuery) != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
            return;
        }

    }



    private function validatePasswords($pw, $pw2)
    {
        if ($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDoNotMatch);
            return;
        }
        //checks if password has characters a-z and 1-9
        if(preg_match('/[^A-Za-z0-9]/', $pw)) {
            array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
            return;
        }
        if (strlen($pw) > 30 || strlen($pw) < 5) {
            array_push($this->errorArray, Constants::$passwordCharacters);
            return;
        }
    }

}

?>