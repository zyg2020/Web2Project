<?php
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	require("./db_connect.php");
	if (isset($_GET['category']) && !empty($_GET['category'])) {
		$categoryName = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);			
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?= $categoryName ?></title>
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
	</script>
</head>
<body>
	<div class="container box">
		<?php require("./header.php") ?>
		<?php require('projectsAndComments.php') ?>
	</div>
</body>
</html>