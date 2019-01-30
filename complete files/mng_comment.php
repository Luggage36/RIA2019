<?php
header('Access-Control-Allow-Origin: *');
include("dbconnect.inc.php");

$action = $_GET['action'];

$callback = $_GET['callback'];

echo $callback . "(";

$response = "";

if($action=="select") {


	//TO-DO: Format date / time

	//get vars
	$userId = $_GET['user_id'];
	$rssId = $_GET['rss_id'];

	//get all comments
	$result = mysqli_query($dbconnection,
							"SELECT *
							FROM `COMMENT`
							WHERE `rss_id`={$rssId}
							ORDER BY `date_posted` DESC");

	if($result) {
		//prefix for checking
		$response.= "SUCCESS:";
		//loop through comment rows
		while($row = mysqli_fetch_array($result)) {

			//display comment content
			$response.=  "<div class='comment' comuser='" . $row['user_id'] . "' comid='" . $row['comment_id'] . "'>";
			$response.= $row['content'];
			$response.= "</div>";
			//display date / time
			$response.=  "<div class='comdate'>";
			$response.=  $row['date_posted'];
			$response.=  "</div>";
						//if comment by current user, show delete and update link
			if($userId==$row['user_id']) {
				$response.=  "<div class='comlinks'>";
				$response.=  "<a href='#' class='updatecom' action='update' comid='" . $row['comment_id'] . "'>Update?</a> | ";
				$response.=  "<a href='#' class='deletecom' action='delete' comid='" . $row['comment_id'] . "''>Delete?</a>";
				$response.=  "</div>";
			}

		}

	} else {
		$response.=  "FAIL:<p>Problem loading comments</p>";
	}

}

if($action=="update") {

	//TO-DO: validation checks
	//get vars
	$commentId = $_GET['comment_id'];
	$commentContent = urldecode($_GET['comment_content']);

	$result = mysqli_query($dbconnection,
							"UPDATE `COMMENT`
							SET `content`='{$commentContent}'
							WHERE `comment_id`={$commentId}");

	if($result) {
		$response.=  "SUCCESS:<p>Comment updated</p>";
	} else {

		$response.=  "FAIL:<p>Problem updating comment</p>";
	}

}

if($action=="insert") {

	//TO-DO: validation checks

	//get vars
	$userId = $_GET['user_id'];
	$rssId = $_GET['rss_id'];
	$commentContent = urldecode($_GET['comment_content']);

	$result = mysqli_query($dbconnection,
							"INSERT INTO `COMMENT`
							(`user_id`,`rss_id`,`content`,`date_posted`)
							VALUES
							({$userId},{$rssId},'{$commentContent}',NOW())");
	if($result) {
		$response.=  "SUCCESS:<p>Comment added</p>";
	} else {

		$response.=  "FAIL:<p>Problem adding comment</p>";
	}

}

if($action=="delete") {

	//TO-DO: validation checks

	//get vars
	$commentId = $_GET['comment_id'];

	$result = mysqli_query($dbconnection,
							"DELETE FROM `COMMENT`
							WHERE `comment_id`={$commentId}");

	if($result) {

		$response.=  "SUCCESS:<p>Comment deleted</p>";
	} else {

		$response.=  "FAIL:<p>Problem deleting comment</p>";
	}

}

$array = array("response" => $response);

echo json_encode($array);

echo ")";