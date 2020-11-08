<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="url">URL</label>
    <input type="text" name="url" class="form-control" id="url">
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
        <textarea class="form-control summernote" rows="10" name="description" id="description"></textarea>
  </div>
	<div class="form-group">
		<label for="categories">Categories</label>
		<select id="categories" name="categories[]" class="form-control" multiple>
			<option value="" disabled selected>Choose corresponding categories</option>
			<?php foreach($categories as $category): ?>
        	<option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
      		<?php endforeach ?>
		</select>	  	
	</div>