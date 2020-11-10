<?php 
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	$hasId = isset($_GET['id']) && !empty($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT);
	if ($hasId) {

		$getCategoriesForIdQuery = "SELECT c.id FROM projects p INNER JOIN projectscategories pc ON p.id = pc.projectId INNER JOIN categories c ON c.id = pc.categoryId WHERE p.id = " . $_GET['id'];
		$statement = $db->prepare($getCategoriesForIdQuery);
		$statement->execute();
		$categoryRows = $statement->fetchAll(); 

		$associatedCategories = [];
		foreach ($categoryRows as $key => $value) {
			array_push($associatedCategories, $value['id']); 
		}
		// print_r($categoryRows);
		// echo '<br>';
		// print_r($row);
		// echo '<br>';
		// print_r($associatedCategories);
	}
	
?>

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" <?php if($hasId): ?>
					 value="<?= $row['title'] ?>"
				<?php endif ?> />
	<input type="hidden" name="id" value="<?= $row['id'] ?>" />
  </div>
  <div class="form-group">
    <label for="url">URL</label>
    <input type="text" name="url" class="form-control" id="url" <?php if($hasId): ?>
										 value="<?= $row['url'] ?>"
									<?php endif ?> >
  </div>
  <div class="form-group pt-4">

    <div class="input-group mb-3">
	  <div class="input-group-prepend">
	    <span class="input-group-text" id="inputGroupFileAddon01">Upload Image</span>
	  </div>

	  <div class="custom-file">
	    <input type="file" class="custom-file-input" id="image" name="image" aria-describedby="inputGroupFileAddon01">
	    <label class="custom-file-label" for="image">Choose file</label>
	  </div>
	</div>
  </div>

  <div class="form-group">
		<label for="description">Description</label>
        <textarea class="form-control summernote" rows="10" name="description" id="description"><?php if($hasId){echo $row['description'];} ?></textarea>
  </div>
	<div class="form-group">
		<label for="categories">Categories</label>
		<select id="categories" name="categories[]" class="form-control" multiple>
			<option value="" disabled <?php if(!$hasId){echo 'selected';} ?> >Choose corresponding categories</option>
        	<?php foreach($categories as $category): ?>
            <option value="<?= $category['id'] ?>"<?php if($hasId && in_array($category['id'], $associatedCategories)){ echo 'selected'; } ?> ><?= $category['name'] ?></option>
          
      		<?php endforeach ?>

		</select>	  	
	</div>