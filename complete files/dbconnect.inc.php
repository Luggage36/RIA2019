<?php

//set connectoion variables to match DB details
$host = "ChrisRSS.db.12784180.hostedresource.com";
$username = "ChrisRSS";
$password = "Eskarina36#";
$database = "ChrisRSS";

//connect to database
$dbconnection = mysqli_connect($host, $username, $password, $database);

//check if connected, exit if not
if(!$dbconnection) {
	exit("Database could not be connected.");
}