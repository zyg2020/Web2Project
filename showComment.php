<?php 
	require_once("./db_connect.php");
	$errorMessage = [];
	$hasId = isset($_GET['id']) && !empty($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT);

	if ($hasId) {
		try {
			$id = $_GET['id'];
			$query= "SELECT c.id id, c.content content, c.createdTimestamp createdTimestamp, u.id userId, u.name name FROM comments c INNER JOIN users u ON u.id = c.userId WHERE c.id = :id LIMIT 1";
			$statement = $db->prepare($query);
			$statement->bindValue(':id',$id);
		    $statement->execute();
		    $commentRow = $statement->fetch();

		} catch (Exception $e) {
			array_push($errorMessage, $e->getMessage());
		}
	}else{
		array_push($errorMessage, 'Not valid Id');
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
		<div>
			<h4><?= $commentRow['name'] ?><a href="showComment.php?id=<?= $commentRow['id'] ?>"></a> </h4>
			<p>
	            <small>
	              <?= date("F d, Y, g:i a",strtotime($commentRow['createdTimestamp'])) ?>
	              <!-- <a href="edit.php?id=<?= $commentRow['id'] ?>">editssssss</a> -->
	            </small>
	        </p>
	        <p><?= $commentRow['content']?></p> 
		</div>
	</div>
</body>
</html>
