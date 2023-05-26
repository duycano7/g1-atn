<?php
session_start();
require "../connect_server.php";
require "functions.php"; // Bao gồm file chứa hàm login_empty()
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
} else {
    $check_table_exists = "SELECT 1 FROM signup LIMIT 1";
    $table_exists = $connect->query($check_table_exists);
    if (!$table_exists) {
        $create_table = "CREATE TABLE signup (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            username VARCHAR(30) NOT NULL,
            email VARCHAR(250) NOT NULL,
            password VARCHAR(250) NOT NULL,
            profile_pic VARCHAR(250) NOT NULL
        )";
        if (!$connect->query($create_table)) {
            echo("SOME ERROR WHILE CREATING TABLE SIGNUP");
        };
    }

    $check_table_exists = "SELECT 1 FROM songs LIMIT 1";
    $table_exists = $connect->query($check_table_exists);
    if (!$table_exists) {
        $create_table = "CREATE TABLE songs(
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            uploader VARCHAR(250) NOT NULL,
            song_name VARCHAR(250) NOT NULL,
            song_link VARCHAR(250),
            singer VARCHAR(250),
            song_location VARCHAR(250),
            category VARCHAR(250),
            views INT
        )";
        if (!$connect->query($create_table)) {
            echo("SOME ERROR WHILE CREATING TABLE SONGS");
        };
    }
}

$login_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // check if method request is POST
    if (login_isset() && !login_empty()) { // call _isset and _empty to check if all the input fields are filled and set
        $username = $_POST['username'];
        $password1 = md5($_POST['password']);
        $password = $password1;
        $check_duplicate = "SELECT username FROM signup WHERE username='$username' AND password='$password'";
        $run_query = $connect->query($check_duplicate);

        if ($run_query->num_rows > 0) {
            $_SESSION['mm_username'] = $username;
            $login_message = "login successfully";
            unset($_POST);
            $str = "<meta http-equiv=\"refresh\" content=\"0; URL=../index.php\">";
            echo($str);
        } else { // call insert_user to insert user in database
            $login_message = "username or password not correct";
            $str = "<meta http-equiv=\"refresh\" content=\"0; URL=../login_signup.php?login_message=".$login_message."\">";
            echo($str);
        }
    }
}

////////////////////////////////////////////////////////////////////
//// function _isset check if all the input fields are filled or not
////////////////////////////////////////////////////////////////////
function login_isset() {
    if (isset($_POST['username']) && isset($_POST['password'])) {


		 	return true;
		 }

		 else{
		 	$GLOBALS['signup_message']="Fields are not set";
		 	return false;
		 }
			
	}

	////////////////////////////////////////////////////////////////////
	////// function _empty check if all the input fields are filled or not
	////////////////////////////////////////////////////////////////////

	function _empty()
	{
		  if(										
			 	(
			 	  !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])
			  	  && !empty($_POST['name']) 
			  	)
		   )
		 {

		 	return false;
		 }

		 else{
			$GLOBALS['signup_message']="Fields are not filled";
		 	
			return true;
		}
	}


	////////////////////////////////////////////////////////////////////
	// function to insert user into the database
	////////////////////////////////////////////////////////////////////

	function insert_user($name,$username,$password,$email,$connect,$signup_message)  				
	{		
		
		$sql="INSERT INTO signup (name, username, email, password, profile_pic) VALUES 
('".mysqli_real_escape_string($connect,$name)."', '".mysqli_real_escape_string($connect,$username)."', '".mysqli_real_escape_string($connect,$email)."', '".mysqli_real_escape_string($connect,$password)."', 'http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?s=40&d=identicon&r=G')
	  '".mysqli_real_escape_string($conn, $email)."', '".mysqli_real_escape_string($conn, $password)."', 
	  'icon-user-default.png')";
	
		
		if($connect->query($sql))
		{
			
			$_SESSION['mm_username']=$username;
			unset($_POST);
			return "You have been successfully registered to this web site";

		}

		else
		{
			// die("database insertion problem function insert_user");
			return "database insertion problem function insert_user";

		}
						 	 		
	}




?>

