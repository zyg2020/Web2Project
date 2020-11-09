<?php
	require_once("./db_connect.php");
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}

	if(!isset($_SESSION['username'])) {
		header("Location: index.php");
    	exit;
	}
	unset($errorMessage);
	if ($_SERVER["REQUEST_METHOD"] === "POST" ){
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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

	    		if ($catagoryNameAvaiable) {
	    			$query = "INSERT INTO categories (name) VALUES (:name)";
					$statement = $db->prepare($query);
					$statement->bindValue(':name',$name);
					$statement->execute();
				}
			} catch (Exception $e) {
				$errorMessage = $e->getMessage();
			}

    	}else{
			$errorMessage = 'Category name is not available';
    	}

	}else{
		$errorMessage = 'Please input a name';
	}

	if (!$errorMessage) {
		header("Location: management.php");
        exit;	
	}


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
			<input type="text" id="name" name="name" aria-label="Name" class="form-control">
		</div>

	  	<button type="submit" class="btn btn-primary" value="create" name="command">Submit</button>
		<?php if(isset($errorMessage)): ?>
		<div class="col-sm-12 alert alert-danger" role="alert">
			  <?= $errorMessage ?>
		</div>
		<?php endif ?>
	</form>		
	</div>
</body>
</html>


