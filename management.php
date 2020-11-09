<?php
	require("./db_connect.php");
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
    
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
</head>
<body>
	<div class="container box">
		<?php require("./header.php") ?>
		<ul class="list-group">
			<?php foreach($categories as $category): ?>
			<li class="list-group-item list-group-item-action"><?= $category['name'] ?></li>
			<?php endforeach ?>
		</ul>
		
<div class="row">
  <div class="col-4">
    <div class="list-group" id="list-tab" role="tablist">
      <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Home</a>
      <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Profile</a>
      <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Messages</a>
      <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Settings</a>
    </div>
  </div>
  <div class="col-8">
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">...</div>
      <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">...</div>
      <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div>
      <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">...</div>
    </div>
  </div>
</div>

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
                  <a href="editProject.php?id=<?= $row['id'] ?>">edit</a>
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
		</section>
		<?php endwhile ?>
	</div>
</body>
</html>