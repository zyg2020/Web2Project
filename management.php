<?php
	require("./db_connect.php");

	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}

	if(!isset($_SESSION['username'])) {
		header("Location: index.php");
    	exit;
	}

	$sort = "createdTimestamp";
	if(isset($_POST["sort"]) && !empty($_POST["sort"])){ 
		$sort = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}


	// $myPDO = MyPDO::getInstance();
	// function getCategories($projectId){
	// 	global $db;
	// 	$CorrespondingCategoryQuery = "SELECT c.name FROM projects p 
	// 							  	INNER JOIN projectscategories pc
	// 									ON p.id = pc.projectId
	// 								INNER JOIN categories c
	// 									ON c.id = pc.categoryId
	// 									WHERE p.id = :id";
	// 	$CategorySatement = $db->prepare($CorrespondingCategoryQuery);
	// 	$CategorySatement = $CategorySatement->bindValue('id', $projectId);
	// 	$CategorySatement->execute();
	// 	return $CategorySatement->fetchAll();
	// }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Management</title>
<!--     <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Alegreya" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/luminous-lightbox/2.0.1/luminous-basic.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/luminous-lightbox/2.0.1/Luminous.min.js"></script> -->
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script>

		$( document ).ready(function() {
		     $( ".commentForm" ).hide();

		     $("button.showButton").click(function(e){

		        $("#form_"+event.target.id).toggle();
		    });
		}); 
		function autoSubmit()
		{
		    var formObject = document.forms['theForm'];
		    formObject.submit();
		}
	</script>
	<style type="text/css">
		a.categoryDelete{
			float: right;
			position: relative;
		}
		a.categoryUpdate{
			float: right;
			position: relative;
			left: -5px;
		}
	</style>
</head>
<body>
	<div class="container box">
		<?php require("./header.php") ?>

		<form name='theForm' id='theForm' method="post">
			<div style="display: inline;">Sorted By</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="sort" id="orderByCreated" <?php if ($sort == 'createdTimestamp'): ?>checked='checked' <?php endif ?>value="createdTimestamp" onChange="autoSubmit();">
  				<label class="form-check-label" for="orderByCreated">Created Time</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="sort" id="inlineRadio2" <?php if ($sort == 'updatedTimestamp'): ?>checked='checked' <?php endif ?>value="updatedTimestamp" onChange="autoSubmit();">
  				<label class="form-check-label" for="inlineRadio2">Updated Time</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="sort" id="inlineRadio3" <?php if ($sort == 'title'): ?>checked='checked' <?php endif ?>value="title" onChange="autoSubmit();">
  				<label class="form-check-label" for="inlineRadio3">Title</label>
			</div>
		</form>

		<ul class="list-group">
			<?php foreach($categories as $category): ?>
			<li class="list-group-item list-group-item-action"> <span><?= $category['name'] ?></span>  <a href="" class="badge badge-danger categoryDelete">Delete</a><a href="category.php?id=<?= $category['id'] ?>" class="badge badge-primary categoryUpdate r-5">Update</a></li>
			<?php endforeach ?>
		</ul>
		<?php require('projectsAndComments.php') ?>
	</div>
</body>
</html>