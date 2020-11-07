<?php
	require('./db_connect.php');

  	// Query all the provinces from the database.
  	$query = "SELECT * FROM provinces ORDER BY name";
  	$statement = $db->prepare($query);
 	$statement->execute();

	// Fetch the returned provinces as an array of hashes.
	$provinces = $statement->fetchAll();

	// Add a dummy province to the front of the provinces array.
	// This data will be used as the first province select option.
	array_unshift($provinces, ["id" => -1, "name" => "Select your province..."]);
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
		<div class="row center">
		   <form  style="float:none;margin:auto;" action="" method="post">
		      <div class="col-sm-12">
		      	<div class="form-group">
		            <label for="username">Username</label>
		            <input type="text" id="username" name="username" placeholder="e.g. FirstInitialLastName+Number" class="form-control">
		         </div>
		         <div class="form-group">
		            <label for="password" >Password</label>
		            <input type="text" id="password" name="password" placeholder="Enter Phone Number Here.." class="form-control">
		         </div>
		         <div class="form-group">
		            <label for="confirmedPassword">Confirmed Password</label>
		            <input type="text" id="confirmedPassword" name="confirmedPassword" placeholder="Enter Phone Number Here.." class="form-control">
		         </div>
		         <div class="row">
		            <div class="col-sm-6 form-group">
		               <label>Name</label>
		               <input type="text" placeholder="Enter First Name Here.." class="form-control">
		            </div>
		         </div>
		         <div class="form-group">
		            <label>Address</label>
		            <textarea placeholder="Enter Address Here.." rows="3" class="form-control"></textarea>
		         </div>
		         <div class="row">
		            <div class="col-sm-4 form-group">
		            	<label for="province">Province</label>
						<select class="form-control" name="province" id="province">
						<?php foreach($provinces as $province): ?>
							<option value="<?= $province['id'] ?>">
						  	<?= $province['name'] ?>
							</option>
						<?php endforeach ?>
						</select>
		            </div>
		            <div class="col-sm-4 form-group">
		               <label class="label" for="city">City</label>
				       <select class="form-control" name="city" id="city">
				       		<option value="-1">Select your province first.</option>
				       </select>
		            </div>
		            <div class="col-sm-4 form-group">
		               <label>State</label>
		               <input type="text" placeholder="Enter State Name Here.." class="form-control">
		            </div>
		         </div>
		         <div class="row">
		            <div class="col-sm-6 form-group">
		               <label>Title</label>
		               <input type="text" placeholder="Enter Designation Here.." class="form-control">
		            </div>
		            <div class="col-sm-6 form-group">
		               <label>Company</label>
		               <input type="text" placeholder="Enter Company Name Here.." class="form-control">
		            </div>
		         </div>
		         <div class="form-group">
		            <label>Phone Number</label>
		            <input type="text" placeholder="Enter Phone Number Here.." class="form-control">
		         </div>
		         <div class="form-group">
		            <label>Email Address</label>
		            <input type="text" placeholder="Enter Email Address Here.." class="form-control">
		         </div>
		         <div class="form-group">
		            <label>Website</label>
		            <input type="text" placeholder="Enter Website Name Here.." class="form-control">
		         </div>
		         <button type="button" class="btn btn-lg btn-info">Submit</button>					
		      </div>
		   </form>
		</div>
	</div>
</body>
</html>