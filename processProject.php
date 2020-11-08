<?php
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	$ErrorMessage = [];
	if ($_POST && !empty($_POST['command']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['categories'])) {
		require_once('db_connect.php');
		if (!empty($_POST['url'])) {
			$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
			if (!$url) {
				array_push($ErrorMessage, ['URL' => 'Invalid URL']);
			}else{
				$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
			}
		}

		$command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
		$image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$categories = $_POST['categories'];
		for ($i=0; $i < count($categories); $i++) { 
			$categories[$i] = filter_var($categories[$i], FILTER_VALIDATE_INT);
			if (!$categories[$i]) {
				array_push($ErrorMessage, ['categoryValue' => 'Invalid category value']);
			}else{
				$categories[$i] = filter_var($categories[$i], FILTER_SANITIZE_NUMBER_INT);
			}
		}

		if (!$ErrorMessage && $_POST['command']=='create') {
			$insertQuery = "INSERT INTO projects (title,url, description,imagePath,createdTimestamp,userId) VALUES 	
								(:title,:url,:description,:imagePath,:createdTimestamp,:userId)";
			$insertStatement=$db->prepare($insertQuery);

			//date_default_timezone_set('America/Winnipeg');

			$userId = $_SESSION['userId'];
			echo $userId;
			$values = [':title' => $title,
							':url' => $url,
							':description' => $description,
							':imagePath' => $image,
							':createdTimestamp' => date('Y-m-d H:i:s',strtotime("now")),
							':userId' => $userId ];
			$result = $insertStatement->execute($values);
			$newProjectId = $db->lastInsertId();

			$insertProjectsCategories = "INSERT INTO projectscategories (projectId,categoryId) VALUES (:projectId,:categoryId)";
			$ProjectsCategoriesStatement=$db->prepare($insertProjectsCategories);

			foreach ($categories as $value) {
				$values = ['projectId' => $newProjectId,
						   'categoryId' => $value];
				$ProjectsCategoriesStatement->execute($values);
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

</body>
</html>