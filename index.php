<?php
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	require("./db_connect.php");

    
    $getProjectQuery = "SELECT * FROM projects";
    $projectsStatement = $db->prepare($getProjectQuery);
    $projectsStatement->execute();

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
	<title></title>
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
		<?php while ($row = $projectsStatement->fetch()): ?> 
		<section>
			<h1><?= $row['title'] ?></h2>
			<h6 class="font-italic text-success">
				<?php $CorrespondingCategoryQuery = "SELECT c.name FROM projects p INNER JOIN projectscategories pc ON p.id = pc.projectId INNER JOIN categories c ON c.id = pc.categoryId WHERE p.id = " . $row['id'];
					$CategorySatement = $db->prepare($CorrespondingCategoryQuery);
					$CategorySatement->execute(); 
					$categoryRows = $CategorySatement->fetchAll(); 
					for($i=0; $i< count($categoryRows); $i++ ) {
						echo $categoryRows[$i]['name'];
						if($i != count($categoryRows)-1){
							echo ', ';
						} 
					}
				?>
			</h6>
			<p>
                <small>
                  <?= date("F d, Y, g:i a",strtotime($row['createdTimestamp'])) ?> -
                  <a href="edit.php?id=<?= $row['id'] ?>">editssssss</a>
                </small>
            </p>
            <?php if(strlen($row['description']) > 200 ): ?>
            <p><?= substr($row['description'],0,200) ?> ...
                <a href="show.php?id=<?= $row['id'] ?>">Read more</a>
            </p>
            <?php else: ?>
            <p><?= $row['description']?></p> 

            <?php endif ?>
            <?php if($row['url']): ?>
            <a href="<?= $row['url'] ?>">Link</a>
        	<?php endif ?>

			<button type="button" class="btn btn-primary showButton" id="<?= $row['id'] ?>">Add Comment</button>
				 
			<form class="commentForm" action="processComments.php" method="post" id="form_<?= $row['id'] ?>">
				<?php if(!isset($_SESSION['username'])): ?>
				<div class="form-group">
					<label for="name">Name</label>
				    <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" />
				<?php endif ?>
				<div class="form-group">
					<label for="comment">Comment</label>
			        <textarea class="form-control summernote" rows="10" name="comment" id="comment"></textarea>
			        <input type="hidden" name="id" value="<?= $row['id'] ?>" />
			  	</div>
				<button type="submit" class="btn btn-primary" value="create" name="command">submit</button>
			</form>	

		</section>
		<?php endwhile ?>
	</div>
</body>
</html>