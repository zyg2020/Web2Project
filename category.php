<?php
	require_once("./db_connect.php");
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}

	if(!isset($_SESSION['username'])) {
		header("Location: index.php");
    	exit;
	}
	$errorMessage = [];
	$hasId = isset($_GET['id']) && !empty($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT);

	if ($hasId) {
		try {
			$id = $_GET['id'];
			$catagoryNameQuery= "SELECT * FROM categories WHERE id = $id LIMIT 1";
			$statement = $db->prepare($catagoryNameQuery);
		    $statement->execute();
		    $categoryRow = $statement->fetch();
		} catch (Exception $e) {
			array_push($errorMessage, $e->getMessage());
		}
		
	}

	
	if ($_SERVER["REQUEST_METHOD"] === "POST"){
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
		if (!$id && $_POST['command'] !='create') {
			array_push($errorMessage, 'Invalid Id');
		}else{
			$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
		}

		if ($name) {
			try {
				$catagoryNameQuery= "SELECT * FROM categories";
				$statement = $db->prepare($catagoryNameQuery);
	    		$statement->execute();

	    		$catagoryNameAvaiable = true;
	    		while ($row = $statement->fetch()) {
	    			if (strtolower($name) == strtolower($row['name'])) {
	    				$catagoryNameAvaiable = false;
	    			}
	    		}

	    		if ($_POST['command']=='create') {
	    			if ($catagoryNameAvaiable) {
	    				$query = "INSERT INTO categories (name) VALUES (:name)";
						$statement = $db->prepare($query);
						$statement->bindValue(':name',$name);
						$statement->execute();
	    			}else{
						array_push($errorMessage, 'Category name is not available');
					}	
				}

				if (!$errorMessage && $_POST['command']=='update') {
					if ($catagoryNameAvaiable) {
						$updateQuery = "UPDATE categories SET name=:name WHERE id=:id LIMIT 1";
						$statement = $db->prepare($updateQuery);
						$statement->bindValue(':name',$name);
						$statement->bindValue(':id',$id);
						$statement->execute();
					}else{
						array_push($errorMessage, 'Change a category name');
					}
				}

				if ($_POST['command']=='delete') {
					$deleteQuery = "DELETE FROM categories WHERE id = :id LIMIT 1";
			        $statement = $db->prepare($deleteQuery);
			        $statement->bindValue(':id', $id, PDO::PARAM_INT);

			        $statement->execute();
				}

				$success = true;
			} catch (Exception $e) {
				array_push($errorMessage, $e->getMessage());
			}

    	}else{
			array_push($errorMessage, 'Please input a name');
    	}

	}

	if (!$errorMessage && isset($success)) {
		header("Location: management.php");
        exit;	
	}
	// print_r($errorMessage);
	// echo "<br>";
	// print_r($_POST);
	// echo "<br>";
	// print_r($_GET);
	// echo "<br>";
	// print_r($id);
	// echo "<br>";
	// print_r($name);
?>

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
	<form action="" method="post" >
	  	<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">Category Name</span>
			</div>
			<input type="text" id="name" name="name" aria-label="Name" class="form-control" <?php if($hasId): ?>
							value="<?= $categoryRow['name'] ?>"  
							<?php endif ?> >
			<?php if($hasId): ?>
			<input type="hidden" name="id" value="<?= $id ?>" />
			<?php endif ?>
		</div>
		<?php if($hasId): ?>
		<button type="submit" class="btn btn-primary" value="update" name="command">Update</button>
		<button type="submit" class="btn btn-danger" value="delete" name="command">Delete</button>
		<?php else: ?>	
	  	<button type="submit" class="btn btn-primary" value="create" name="command">Submit</button>
	  	<?php endif ?>

		<?php if(isset($errorMessage)): 
		   	      foreach($errorMessage as $value): ?>
				<div class="col-sm-12 alert alert-danger" role="alert">
					  <?= $value ?>
				</div>
			<?php endforeach;
			endif ?>
	</form>		
	</div>
</body>
</html>


