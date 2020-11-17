<?php
	define("ROW_PER_PAGE",2);
    require_once("./db_connect.php");
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}	

	$search_keyword = '';
	$inputtedword = filter_input(INPUT_POST, 'searchWord', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	if ($_POST && !empty($inputtedword)) {
		$search_keyword = $inputtedword;
	}

	$search_categories = [];
	if ($_POST && isset($_POST['searchCategories']) && !empty($_POST['searchCategories'])) {
		$search_categories = $_POST['searchCategories'];
		foreach ($search_categories as &$value) {
			$value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
		//$_SESSION['searchCategories'] = $search_categories;
	}
	// else{
	// 	$search_categories = [];
	// 	if ($_POST && isset($_POST['getCategories']) && !empty($_POST['getCategories'])) {
	// 		$search_categories = $_SESSION['searchCategories'];
	// 	}
	// }



	$searchCondition = '%' . $search_keyword . '%';
	$keywordBind = [':keyword1' => $searchCondition,
					':keyword2' => $searchCondition,
					':keyword3' => $searchCondition];



	if ($search_categories) {
		$in = "";
		for ($i=0; $i < count($search_categories); $i++) { 
			$key = ":id" . $i;
			$in .= "$key,";
			$in_params[$key] = $search_categories[$i];
		}
		$in = rtrim($in,",");

		$selectQuery = "SELECT * FROM projects p INNER JOIN projectscategories pc ON p.id = pc.projectId WHERE pc.categoryId IN ($in) AND (p.title LIKE :keyword1 OR p.description LIKE :keyword2 OR p.createdTimestamp LIKE :keyword3) GROUP BY p.id";

		$hasCategoriesStm = $db->prepare($selectQuery);
		$hasCategoriesStm->execute(array_merge($keywordBind ,$in_params));

		$row_count = $hasCategoriesStm->rowCount();

		$data = $hasCategoriesStm->fetchAll();
	}else{
		$selectQuery = "SELECT * FROM projects WHERE title LIKE :keyword1 OR description LIKE :keyword2 OR createdTimestamp LIKE :keyword3";
		$hasCategoriesStm = $db->prepare($selectQuery);
		$hasCategoriesStm->execute($keywordBind);
		$row_count = $hasCategoriesStm->rowCount();
		$data = $hasCategoriesStm->fetchAll();
	}

	$page = 1;
	$start=0;
	if(!empty($_POST["page"]) && filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT)) {
		$postedPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
		if ($postedPage > ceil($row_count/ROW_PER_PAGE)) {
			$page = ceil($row_count/ROW_PER_PAGE);
		}elseif ($postedPage < 1) {
			$page = 1;
		}else{
			$page = $postedPage;
		}
		$start=($page-1) * ROW_PER_PAGE;
	}
	$limit=" LIMIT :start,:rowSize"; //  . ROW_PER_PAGE;

	if (!empty($row_count)) {
		$pagedQuery = $selectQuery . $limit;
		$pagedStatement = $db->prepare($pagedQuery);

		// if ($search_categories) {
		// 	$pagedStatement->execute(array_merge($keywordBind ,$in_params));
		// }else{
		// 	$pagedStatement->execute($keywordBind);
		// }
		foreach ($keywordBind as $key => $value) {
			$pagedStatement->bindValue($key,$value);
			//echo $key . '=>' . $value . '<br>';
		}

		$pagedStatement->bindValue(':start',$start,PDO::PARAM_INT);
		$pagedStatement->bindValue(':rowSize',(int)ROW_PER_PAGE,PDO::PARAM_INT);
		//echo $start . '=>' . ROW_PER_PAGE . '<br>';

		if ($search_categories) {
			foreach ($in_params as $key => $value) {
				$pagedStatement->bindValue($key,$value);
				//echo $key . '=>' . $value . '<br>';
			}
		}
// print_r($_POST);
// print_r($selectQuery);
// echo "<br>";
// print_r($data);
// echo "<br>";
// print_r($pagedQuery);
		$pagedStatement->execute();

		$data = $pagedStatement->fetchAll();
	}

//	exit('');

?>
<!DOCTYPE html>
<html>
<head>
	<title>aaaa</title>
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
		<?php if (!isset($data) || empty($data)): ?>
		<h2>No Data found.</h2>
		<?php else: ?>
			<?php foreach ($data as $row): ?>
			<section style="overflow: hidden;">
			<?php 
				require_once('functions.php');
			?>
				<?php if(isset($row['imagePath']) && !empty($row['imagePath'])): 
							$threeImages = changeToThreeRelativePath($row['imagePath']);?>
				<div style="float: right;">
					<a href="<? $threeImages['origin'] ?>" alt="aaaa" class = "large">
						<img src="<?= $threeImages['medium'] ?>" alt="<?= $row['title'] ?>" >
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
			<?php endforeach ?>
			<?php if (isset($row_count) && !empty($row_count)): ?>
				<form action="" method="post">
					<input type="hidden" name="searchWord" value="<?= $inputtedword ?>" />
					<?php foreach ($search_categories as $value): ?>
						<input type="hidden" name="searchCategories[]" value="<?= $value ?>" />	
					<?php endforeach ?>
				<nav aria-label="Page navigation example">
				  <ul class="pagination">
				    <button type="submit" name="page" value="<?= ($page-1) ?>" class="page-item" >Previous</button>	

				    <?php for ($i = 1; $i <= ceil($row_count/ROW_PER_PAGE) ; $i++): ?>
				    <button type="submit" name="page" value="<?= $i ?>" class="page-item <?php if ($i == $page): ?>
				        										btn btn-primary
				        									<?php endif ?>" ><?= $i ?></button>	
				    <?php endfor ?>
				    <button type="submit" name="page" value="<?= ($page+1) ?>" class="page-item" >Next</button>	
				  </ul>
				</nav>
				</form>
			<?php endif ?>
		<?php endif ?>
	</div>
</body>
</html>