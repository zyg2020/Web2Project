<?php 
	require_once('./db_connect.php');

	$usernameQuery = "SELECT * FROM users";
	$usernameStatement = $db->prepare($usernameQuery);
    $usernameStatement->execute();

	$response = [
    	'success' => false,
    	'usernameAvailable' => true
  	];

	if (isset($_GET['username']) && (strlen($_GET['username']) !== 0)) {
	    while ($row = $usernameStatement->fetch()) {
	    	if (strtolower($_GET['username'] == strtolower($row['username']))  {
	    	 	$response['usernameAvailable'] = false;
	    	 }elseif (condition) {
	    	 	$response['success'] = true;
	    	 } 
	    }
	} 

	header('Content-Type: application/json');

	// Encode the $response into JSON and echo.
	echo json_encode($response);
?>