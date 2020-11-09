<?php
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	$ErrorMessage = [];
	if ($_POST && !empty($_POST['command']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['categories'])) {
		require_once('db_connect.php');

		$categories = $_POST['categories'];
		if (!$categories) {
			array_push($ErrorMessage, 'Please select at least one category option');
		}else{
			for ($i=0; $i < count($categories); $i++) { 
				$categories[$i] = filter_var($categories[$i], FILTER_VALIDATE_INT);
				if (!$categories[$i]) {
					array_push($ErrorMessage, 'Invalid category value');
				}else{
					$categories[$i] = filter_var($categories[$i], FILTER_SANITIZE_NUMBER_INT);
				}
			}
		}

		if (!empty($_POST['url'])) {
			$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
			if (!$url) {
				array_push($ErrorMessage, 'Invalid URL');
			}else{
				$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
			}
		}

		$command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
		$image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if (!$title) {
			array_push($ErrorMessage, 'Please input valid title');
		}
		if (!$description) {
			array_push($ErrorMessage, 'Please input valid description');
		}


		date_default_timezone_set('America/Winnipeg');

		if (!$ErrorMessage && $_POST['command']=='create') {
			$insertQuery = "INSERT INTO projects (title,url, description,imagePath,createdTimestamp,userId) VALUES 	
								(:title,:url,:description,:imagePath,:createdTimestamp,:userId)";
			$insertStatement=$db->prepare($insertQuery);

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

		if (!$ErrorMessage && isset($_POST['id'])) {
			$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
			if (!$id) {
				array_push($ErrorMessage, ['projectId' => 'Invalid project Id']);
			}else{
				$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);

				if ($_POST['command']=='update'){
					$updateQuery = "UPDATE projects SET title = :title, url = :url, imagePath = :imagePath, description = :description, updatedTimestamp = :updatedTimestamp LIMIT 1";
					$statement = $db->prepare($updateQuery);
					$values = ['title' => $title,
							'url' => $url,
							'description' => $description,
							'imagePath' => $image,
							'updatedTimestamp' => date('Y-m-d H:i:s',strtotime("now"))];
					$statement->execute($values);

					$deleteCategory = "DELETE FROM projectscategories WHERE projectId = :projectId";
					$statement = $db->prepare($deleteCategory);
			        $statement->bindValue(':projectId', $id, PDO::PARAM_INT);
			        $statement->execute();

			        $insertProjectsCategories = "INSERT INTO projectscategories (projectId,categoryId) VALUES (:projectId,:categoryId)";
					$ProjectsCategoriesStatement=$db->prepare($insertProjectsCategories);

					foreach ($categories as $value) {
						$values = ['projectId' => $id,
								   'categoryId' => $value];
						$ProjectsCategoriesStatement->execute($values);
					}
				}

				if (!$ErrorMessage && $_POST['command']=='delete'){
					$deleteQuery = "DELETE FROM projects WHERE id = :id LIMIT 1";
			        $statement = $db->prepare($deleteQuery);
			        $statement->bindValue(':id', $id, PDO::PARAM_INT);

			        $statement->execute();
				}
			}
		}

		if (!$ErrorMessage) {
			header("Location: management.php");
        	exit;
		}else{
			print_r($ErrorMessage);
		}
	}elseif ($_POST && !empty($_POST['command'])) {
		if (empty($_POST['title'])) {
    		array_push($ErrorMessage, 'Title is required field');
    	}
    	if (empty($_POST['description'])) {
    		array_push($ErrorMessage, 'Description is required field');
    	}
    	if (empty($_POST['categories'])) {
    		array_push($ErrorMessage, 'Please select at least one category option');
    	}
	}
?>

<?php if($ErrorMessage): ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container box">
		<?php require("./header.php") ?>	
		<?php foreach ($ErrorMessage as $value): ?>
			<div class="alert alert-danger" role="alert">
			  <?= $value ?>
			</div>
		<?php endforeach ?>
	</div>
</body>
</html>
<?php endif ?>