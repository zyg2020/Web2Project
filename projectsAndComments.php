<?php
	$path = explode('?', $_SERVER['REQUEST_URI']);
	$currentFile = basename($path[0]);

	if ($currentFile == "management.php") {
		$isManagementPage = true;
	}else {
		$isManagementPage = false;
	}

	$getProjectQuery = "SELECT * FROM projects";

	if (isset($categoryName) && !empty($categoryName)) {
		$validCategory = false;
		foreach($categories as $oneCategory){
			if (strtolower($oneCategory['name']) == strtolower($categoryName) ) {
			 	$validCategory = true;
			 	$categoryId = $oneCategory['id'];
			 } 
		}

		if ($validCategory && $categoryId) {
			$getProjectQuery = "SELECT * FROM projects p INNER JOIN projectscategories pc ON p.id = pc.projectId WHERE pc.categoryId = " . $categoryId;
		}
	}

	if (isset($sort) && !empty($sort)) {
		$getProjectQuery = $getProjectQuery . " ORDER BY " . $sort;

		if ($sort != 'title') {
			$getProjectQuery = $getProjectQuery . " DESC";
		}
	}
    $projectsStatement = $db->prepare($getProjectQuery);
    $projectsStatement->execute();
?>

<?php while ($row = $projectsStatement->fetch()): ?> 
<section style="overflow: hidden;">
<?php 
	require_once('functions.php');
?>
	<?php if(isset($row['imagePath']) && !empty($row['imagePath'])): 
				$threeImages = changeToThreeRelativePath($row['imagePath']);?>
	<div style="float: right;">
		<a href="<? $threeImages['origin'] ?>" alt="aaaa" class = "large">
		<?php if($isManagementPage): ?>
			<img src="<?= $threeImages['thumbnail'] ?>" alt="<?= $row['title'] ?>" >
		<?php else: ?>
			<img src="<?= $threeImages['medium'] ?>" alt="<?= $row['title'] ?>" >
		<?php endif; ?>">
		</a>
	</div>
	<?php endif ?>
	<h2><a style="color: inherit;" href="showProject.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></a></h2>
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
          <?= date("F d, Y, g:i a",strtotime($row['createdTimestamp'])) ?>
        </small>
    </p>
    <?php if(strlen($row['description']) > 200 ): ?>
    <p><?= substr($row['description'],0,200) ?> ...
        <a href="showProject.php?id=<?= $row['id'] ?>">Read more</a>
    </p>
    <?php else: ?>
    <p><?= $row['description']?></p> 
    <?php endif ?>

    <?php if($row['url']): ?>
    <div><a href="<?= $row['url'] ?>">Link</a></div>
	<?php endif ?>
	<a href="editProject.php?id=<?= $row['id'] ?>" class="btn btn-primary .btn-xs  <?php if($isManagementPage) {
				echo 'active showButton';
			}else{
				echo 'disabled';
			} ?>" role="button" aria-pressed="true">Edit</a>
	<button type="button" class="btn btn-primary .btn-xs showButton" id="<?= $row['id'] ?>">Comment</button>
		 
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
<div >
	<?php 
	    $getComments = "SELECT c.id id, c.content content, c.createdTimestamp createdTimestamp, u.id userId, u.name name FROM comments c INNER JOIN users u ON u.id = c.userId WHERE projectId = :projectId ORDER BY c.createdTimestamp DESC";
		$statement = $db->prepare($getComments);
		$statement ->bindValue(':projectId', $row['id'], PDO::PARAM_INT);
	    $statement->execute();
	?>
	<?php while($commentRow = $statement->fetch()): ?>
	<div class="border border-dark m-3">
		<h4><a style="color: inherit;" href="showComment.php?id=<?= $commentRow['id'] ?>"><?= $commentRow['name'] ?></a></h4>
		<p>
            <small>
              <?= date("F d, Y, g:i a",strtotime($commentRow['createdTimestamp'])) ?>
            </small>
        </p>
        <?php if(strlen($commentRow['content']) > 200 ): ?>
        <p><?= substr($commentRow['content'],0,200) ?> ...
            <a href="showComment.php?id=<?= $commentRow['id'] ?>">Read more</a>
        </p>
        <?php else: ?>
        <p><?= $commentRow['content']?></p> 
        <?php endif ?>
	</div>
	<?php endwhile ?>
</div>
<?php endwhile ?>
<script>
	new LuminousGallery(document.querySelectorAll("div a.large"));
</script>
