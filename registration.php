<?php
	require('./db_connect.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="getCities.js"></script>
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
		         <div class="alert alert-danger" role="alert">
				  Required
				</div>
		         <div class="form-group">
		            <label for="password" >Password</label>
		            <input type="text" id="password" name="password" placeholder="Enter Password Here.." class="form-control">
		         </div>
		         <div class="form-group">
		            <label for="confirmedPassword">Confirmed Password</label>
		            <input type="text" id="confirmedPassword" name="confirmedPassword" placeholder="Enter Password Here Again.." class="form-control">
		         </div>
		         <div class="row">
		            <div class="col-sm-6 form-group">
		               <label for="name">Name</label>
		               <input type="text" id="name" name="name" placeholder="Enter Name Here.." class="form-control">
		            </div>
		         </div>
		         <div class="form-group">
		            <label for="address">Address</label>
		            <textarea id="address" name="address" placeholder="Enter Address Here.." rows="1" class="form-control"></textarea>
		         </div>
		         <div class="row">
		            <div class="col-sm-4 form-group">
		               <label class="label" for="city">City</label>
				       <!-- <select class="form-control" name="city" id="city">
				       		<option value="-1">Select your province first</option>
				       </select> -->
				       <input id="city" name="city" type="text" placeholder="e.g. Winnipeg" class="form-control">
		            </div>
		            <div class="col-sm-4 form-group">
		            	<label for="province">Province</label>
						<input id="province" name="province" type="text" placeholder="Enter Province Here.." class="form-control">
		            </div>
		            <div class="col-sm-4 form-group">
		               <label for="country">Country</label>
		               <input type="text" id="country" name="country" placeholder="Enter State Name Here.." class="form-control">
		            </div>
		         </div>
		         <div class="row">
		            <div class="col-sm-6 form-group">
		               <label for="title">Title</label>
		               <input id="title" name="title" type="text" placeholder="e.g. Developer" class="form-control">
		            </div>
		            <div class="col-sm-6 form-group">
		               <label for="branchOffice">Branch Office</label>
		               <input type="text" id="branchOffice" name="branchOffice" placeholder="Enter Company Name Here.." class="form-control">
		            </div>
		         </div>
		         <div class="form-group">
		            <label>Email Address</label>
		            <input type="text" placeholder="Enter Email Address Here.." class="form-control">
		         </div>
		         <button type="button" class="btn btn-lg btn-info">Submit</button>					
		      </div>
		   </form>
		</div>
	</div>
</body>
</html>