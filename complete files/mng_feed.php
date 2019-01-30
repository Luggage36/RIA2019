<?php
include("dbconnect.inc.php");

$callback = $_GET['callback'];

echo $callback . "(";

$response = "";

$rssid = $_GET['rssid'];

$result = mysqli_query($dbconnection,"SELECT * 
						FROM `RSS`
						WHERE `rss_id`={$rssid}");

$remoteFeed = "";

while($row = mysqli_fetch_array($result)) {
	$remoteFeed = $row['address'];
}

$feed = file_get_contents($remoteFeed);

if ($feed !== FALSE) {  
 	$response .=   $feed;
 }

$array = array("response" => $response);

echo json_encode($array);

echo ")";