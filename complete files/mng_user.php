<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include("dbconnect.inc.php");

$action = $_GET['action'];

$callback = $_GET['callback'];

echo $callback . "(";

$response = "";



if($action=="register") {

	//For students
	//TO-DO: Add hashing / salting / encryption
	//TO-DO: Add checking for confirming password etc.
	//TO-DO: Add checking for existing username to be unique.

	//flags for validation checking
	$isValid = true;
	$validMsg = "";

	//get vars from POST
	$username = $_GET['r_username'];
	$password = $_GET['r_password'];
	$firstName = $_GET['r_firstname'];
	$lastName = $_GET['r_lastname'];

	//get vars and check if valid
	if($username=="") {
		$isValid = false;
		$validMsg.="<p>Please enter a username</p>";
	}

	if($password=="") {
		$isValid = false;
		$validMsg.="<p>Please enter a password</p>";
	}

	if($firstName=="") {
		$isValid = false;
		$validMsg.="<p>Please enter a first name</p>";
	}

	if($lastName=="") {
		$isValid = false;
		$validMsg.="<p>Please enter a last name</p>";
	}

	if($isValid) {

		//insert user with details
		$result = mysqli_query($dbconnection,
					"INSERT INTO `USER`
					(`username`,`password`,`first_name`,`last_name`)
					VALUES
					('{$username}','{$password}','{$firstName}','{$lastName}')");
		
		//check if inserted or not
		if($result) {
			$response = "<p>Thank you for registering.  Please click the login button to login.</p>";
		} else {
			$response = "<p>There has been a problem with registering.  Please try again.</p>";
		}

	} else {
		$response = $validMsg;
	}

}

if($action=="login") {	

	//TO-DO: Encryption support

	//flags for validation checking
	$isValid = true;
	$validMsg = "";

	//get vars from POST
	$username = $_GET['l_username'];
	$password = $_GET['l_password'];

	//get vars and check if valid
	if($username=="") {
		$isValid = false;
		$validMsg.="<p>Please enter a username</p>";
	}

	if($password=="") {
		$isValid = false;
		$validMsg.="<p>Please enter a password</p>";
	}

	if($isValid) {

		//insert user with details
		$result = mysqli_query($dbconnection,
								"SELECT *
								FROM `USER`
								WHERE  `username`='{$username}' 
								AND `password`='{$password}'");
		
//Prefixes for return messages in all-caps followed by a colon
//EXAMPLE:
//we use string operations in javascript to determine what actions
//to perform based on the prefix

		echo mysqli_error($dbconnection);

		//check if inserted or not
		if(mysqli_num_rows($result)>0) {

			$userid = "";

			while($row = mysqli_fetch_array($result)) {
					$userid = $row['user_id'];
			}

			$response = "LOGGEDIN:" . $userid;
		} else {
			$response = "NOTFOUND:" .  "<p>Username or password incorrect.</p>";
		}

	} else {
		$response = "INVALID:" . $validMsg;
	}

}

$array = array("response" => $response);

echo json_encode($array);

echo ")";