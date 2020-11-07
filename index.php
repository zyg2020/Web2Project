<?php
	require("./db_connect.php");

	$getCategoryQuery = "SELECT * FROM categories";
	$categoriesStatement = $db->prepare($getCategoryQuery);
	$categoriesStatement->execute();
	// $categories = $statement->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
	<div class="container box">
		<?php require("./header.php") ?>
	</div>
</body>
</html>