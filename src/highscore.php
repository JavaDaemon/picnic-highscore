<?php
/*
 * Connects to the database, so we're ready to query
 */
function connectDB() {
	$db = array(
		# Edit database connection information only
		'host' 		=>	'example.com.mysql', 	// MySQL host
		'name' 		=> 	'example_com', 		// Database name
		'username' 	=> 	'username', 		// Database Username
		'password' 	=> 	'password', 		// Database password
	);
	mysql_connect(
		$db["host"], 
		$db["username"], 
		$db["password"]);
	$selected_db = mysql_select_db($db["name"]);  
	if ($selected_db == false) {  
		die ('Error: ' . mysql_error());  
	}  
}

/*
 * Used to sanitize all the input, before DB entry
 */
function sanitizeInput($input) {
	// First un-qoute, then trim spaces, and then remove illegals.
	return preg_replace('[^ \w \s \' " ]', '', trim(stripslashes($input)));
}

/*
 * Checks if the string can be converted into JSON.
 */
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

/*
 * Convenience for sending HTTP response codes.
 */
function sendError($error) {
	switch ($error) {
		case 400:
			header("HTTP/1.0 400 Bad Request");
			break;
		default:
			die("Function sendError(int) used incorrectly.");
			break;
		}
}

/*
 * Posts a score to the database.
 */
function postScore($name, $score) {
	connectDB();
	$query = "
		INSERT 
			INTO highscores 
				(name, score)
				VALUES 
				('".$name."', ".$score.")";
	mysql_query($query);
}

// First, we identify what request has been made.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Great! Somebody wants to submit their score.
	// We check if they use the correct content mime-type.
	if ($_SERVER["CONTENT_TYPE"] == "application/json") {
		// We get the submission they sent
		$data = file_get_contents('php://input');
		if (isJson($data)) {
			// Convert the input to an associative array.
			$jsonData = json_decode($data, true);
			
			// Check for irregularities
			if (!is_numeric($jsonData["score"])) {
				sendError(400);
				exit();
			}
			
			// Extract and sanitize the data from the request
			$name = sanitizeInput($jsonData["name"]);
			$score = intval($jsonData["score"]);
			
			// Enter the data into the database
			postScore($name, $score);
			// We're done! Score submitted to the highscore table.
		} else {
			// The submission could not be translated to JSON.
			// Send the client a Bad Request error.
			sendError(400);
			exit();
		}
	} else {
		// Client sent the wrong mime type.
		sendError(400);
		exit();
	}
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
	// Somebody wants to receive the highscore list.
	connectDB();
	$query = "
		SELECT *
		FROM highscores
		ORDER BY score
		DESC";
	$result = mysql_query($query);
	$returning = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$returning[] = array(
			"name" 	=> $row["name"], 
			"score"	=> $row["score"]);
	}
	echo json_encode($returning);
}
?>