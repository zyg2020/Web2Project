<?php 
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}

	$ErrorMessage = [];
	if ($_POST && !empty($_POST['command']) && !empty($_POST['comment']) && (isset($_SESSION['username']) || !empty($_POST['name'])) ) {
		require_once('db_connect.php');

		if (!isset($_SESSION['username'])) {
		 	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		 	if (!$name) {
			array_push($ErrorMessage, 'Please input a valid name');
			}
		} else {
			$userId = $_SESSION['userId'];
		}
		
		$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if (!$comment) {
			array_push($ErrorMessage, 'Please input valid comment');
		}

		if (!$ErrorMessage && isset($_POST['id'])) {

			$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
			if (!$id) {
				array_push($ErrorMessage, ['projectId' => 'Invalid project Id']);

			}else{
				$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);

				date_default_timezone_set('America/Winnipeg');

				if ($_POST['command']=='create') {
					if (!isset($_SESSION['username'])) {
						$newVisitorQuery = "INSERT INTO users (name,userType) VALUES (:name,:userType)";
						$statement = $db->prepare($newVisitorQuery);
						$bindValues = [
								'name'=>$name,
								'userType'=>'visitor'
								];
						$statement->execute($bindValues);
						$userId = $db->lastInsertId();
					}

					$commentQuery = "INSERT INTO comments (content,createdTimestamp,projectId,userId) 
										VALUES (:content,:createdTimestamp,:projectId,:userId)";
					$statement = $db->prepare($commentQuery);
					$bindValues = [
								'content'=>$comment,
								'createdTimestamp' => date('Y-m-d H:i:s',strtotime("now")),
								'projectId' => $id,
								'userId' => $userId
								];
					$statement->execute($bindValues);
				}

				header("Location: index.php");
    			exit;
			}
		}
	}


?>