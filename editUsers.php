<?php
	require_once('./db_connect.php');
	require_once('functions.php');
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	$output = [];
	if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST) && !empty($_POST['command'])) {

		if (isset($_POST['id']) && $_POST['id'] && filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)) {
			$id=filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
			
			if ($_POST['command'] === 'delete') {
				$deleteQuery = "DELETE FROM users WHERE id = :id LIMIT 1";
				$statement = $db->prepare($deleteQuery);
				$statement->bindValue(':id', $id, PDO::PARAM_INT);
				$statement->execute();

				header("Location: users.php");
				exit;
			}
		}

		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		$confirmedPassword = filter_input(INPUT_POST, 'confirmedPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$branchOffice = filter_input(INPUT_POST, 'branchOffice', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		$email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

		$errorInfo = [
			'username' => [
				'value' => $username,
				'errorMessage'=> 'Username Required'],
			'name' => [
				'value' => $name,
				'errorMessage'=> 'Name Required'],
			'email' => [
				'value' => $email,
				'errorMessage'=> 'Email Required']];
		$setPassword = false;
		if ($_POST['command'] === 'create' || (isset($password) && !empty($password) && isset($confirmedPassword) && !empty($confirmedPassword))) {
			$passwordinfo =      ['password' => [
										'value' => $password,
										'errorMessage'=> 'Password Required'],

			'confirmedPassword' => [
											'value' => $confirmedPassword,
											'errorMessage'=> 'Confirmed Password Required']];
			$errorInfo = array_merge($errorInfo,$passwordinfo); 

			if (strlen($password) > 0 && strlen($confirmedPassword) > 0) {
				if ($password !== $confirmedPassword) {
					$errorInfo['confirmedPassword']['value'] = false;
					$errorInfo['confirmedPassword']['errorMessage'] = 'Please input the same password in the second time.';
				}else{
					$setPassword = true;
				}
			}else{
				$errorInfo['confirmedPassword']['errorMessage'] = 'Please fill out password fields';
			}
		}

		$hasError = false;
		foreach ($errorInfo as $key => $value) {
			if (!isset($value['value']) || empty($value['value'])) {
				array_push($output, $value['errorMessage']);
				$hasError = true;
			}
		}

		if (!$hasError) {
			if ($_POST['command'] === 'create') {
				$query = "INSERT INTO users (name,email,userType,address,province,city,country,title,branchOfficeName,username,password) VALUES 
							(:name,:email,:userType,:address,:province,:city,:country,:title,:branchOfficeName,:username,:password)";
				$statement = $db->prepare($query);
				$bindValues = [
					'name'=>$name,
					'email'=>$email,
					'userType'=>'user',
					'address'=>$address,
					'province'=>$province,
					'city'=>$city,
					'country'=>$country,
					'title'=>$title,
					'branchOfficeName'=>$branchOffice,
					'username'=>$username,
					'password'=>password_hash($password, PASSWORD_BCRYPT)
					];
				$statement->execute($bindValues);
			}elseif ($id) {
				if($_POST['command'] === 'update') {
					$query = "UPDATE users SET name=:name,email=:email,userType=:userType,address=:address,province=:province,city=:city,country=:country,title=:title,branchOfficeName=:branchOfficeName,username=:username";
					if ($setPassword) {
						$query .= ",password=:password";
					}
					$query .= " WHERE id = :id LIMIT 1";
					$statement = $db->prepare($query);
					$bindValues = [
						'name'=>$name,
						'email'=>$email,
						'userType'=>'user',
						'address'=>$address,
						'province'=>$province,
						'city'=>$city,
						'country'=>$country,
						'title'=>$title,
						'branchOfficeName'=>$branchOffice,
						'username'=>$username,
						'id'=>$id
						];
					if ($setPassword) {
						array_push($bindValues, ['password'=>password_hash($password, PASSWORD_BCRYPT)]);
					}
					$statement->execute($bindValues);
				}
			}
		}
		if (!$hasError) {
			header("Location: users.php");
        	exit;
		}
	}
	$selectedUserRow = [];
	$showUserInfo = false;
	if (count($_GET)>0) {
		if(!isset($_SESSION['isAdministrator']) || !$_SESSION['isAdministrator']) {
			header("Location: index.php");
			exit;
		}
	}
	
	if (count($_GET)>0 && filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) && $_SESSION['isAdministrator']) {

		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		try{
			$userQuery = "SELECT * FROM users WHERE id = :id";
			$userStatement = $db->prepare($userQuery);
			$userStatement->bindValue(':id', $id, PDO::PARAM_INT);
			$userStatement->execute();
			$selectedUserRow = $userStatement->fetch();
			$showUserInfo = true;
			print_r($selectedUserRow);
		}catch (Exception $e) {
			array_push($output, ['databaseError' => $e->getMessage()]);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script src="main.js"></script>
    <?php if ($showUserInfo): ?>
    <script>
		$( document ).ready(function() {
		     $(".passwordInput" ).hide();

		     $("button.showButton").click(function(e){

		        $(".passwordInput").toggle();
		    });
		}); 
		function autoSubmit()
		{
		    var formObject = document.forms['theForm'];
		    formObject.submit();
		}
	</script>
	<?php endif ?>
    <style type="text/css">
    	.form-group.required label:after { 
		   content:"*";
		   color:red;
		}
    </style>
</head>
<body>
	<div class="container box">
		<?php require("./header.php") ?>
		<div class="row center">
		   <form style="float:none;margin:auto;" action="" method="post">
		      <div class="col-sm-12">
		      	<div class="form-group required">
		            <label for="username">Username</label>
		            <input type="text" id="username" name="username" placeholder="e.g. FirstInitialLastName+Number" class="form-control" <?= ifShowUserInfo($selectedUserRow, "username", $showUserInfo) ?>>
		         </div>
		         <div class="alert alert-danger" role="alert" id="usernameRequired" style="display:none;">
				  Required
				</div>
				<div class="alert alert-success" role="alert" id="usernameAvailable" style="display:none;">
				  Username is available.
				</div>
				<div class="alert alert-danger" role="alert" id="usernameTaken" style="display:none;">
				  Sorry this username is taken. Please choose a different username.
				</div>
				<?php if ($showUserInfo): ?>
					<button type="button" class="btn btn-primary .btn-xs showButton">Change Password</button>
				<?php endif ?>

				<div class="passwordInput">
					<div class="form-group required">
						<label for="password" >Password</label>
						<input type="password" id="password" name="password" placeholder="Enter Password Here.." class="form-control">
					</div>
					<div class="form-group required">
						<label for="confirmedPassword">Confirmed Password</label>
						<input type="password" id="confirmedPassword" name="confirmedPassword" placeholder="Enter Password Here Again.." class="form-control">
					</div>
				</div>

				<?php if($showUserInfo): ?>
				<input type="hidden" name="id" value="<?= $selectedUserRow['id'] ?>" />
				<?php endif ?>

		         <div class="row form-group required">
		            <div class="col-sm-6 form-group">
		               <label for="name">Name</label>
		               <input type="text" id="name" name="name" placeholder="Enter Name Here.." class="form-control" <?= ifShowUserInfo($selectedUserRow, "name", $showUserInfo) ?>>
		            </div>
		         </div>
		         <div class="form-group">
		            <label for="address">Address</label>
		            <textarea id="address" name="address" placeholder="Enter Address Here.." rows="1" class="form-control"><?= ifShowUserInfo($selectedUserRow, "address", $showUserInfo) ?></textarea>
		         </div>
		         <div class="row">
		            <div class="col-sm-4 form-group">
		               <label class="label" for="city">City</label>
				       <!-- <select class="form-control" name="city" id="city">
				       		<option value="-1">Select your province first</option>
				       </select> -->
				       <input id="city" name="city" type="text" placeholder="e.g. Winnipeg" class="form-control" <?= ifShowUserInfo($selectedUserRow, "city", $showUserInfo) ?>>
		            </div>
		            <div class="col-sm-4 form-group">
		            	<label for="province">Province</label>
						<input id="province" name="province" type="text" placeholder="Enter Province Here.." class="form-control" <?= ifShowUserInfo($selectedUserRow, "province", $showUserInfo) ?>>
		            </div>
		            <div class="col-sm-4 form-group">
		               <label for="country">Country</label>
		               <input type="text" id="country" name="country" placeholder="Enter State Name Here.." class="form-control" <?= ifShowUserInfo($selectedUserRow, "country", $showUserInfo) ?>>
		            </div>
		         </div>
		         <div class="row">
		            <div class="col-sm-6 form-group">
		               <label for="title">Title</label>
		               <input id="title" name="title" type="text" placeholder="e.g. Developer" class="form-control" <?= ifShowUserInfo($selectedUserRow, "title", $showUserInfo) ?>>
		            </div>
		            <div class="col-sm-6 form-group">
		               <label for="branchOffice">Branch Office</label>
		               <input type="text" id="branchOffice" name="branchOffice" placeholder="Enter Company Name Here.." class="form-control" <?= ifShowUserInfo($selectedUserRow, "branchOffice", $showUserInfo) ?>>
		            </div>
		         </div>
		         <div class="form-group required">
		            <label for="email">Email Address</label>
		            <input type="text" id="email" name="email" placeholder="Enter Email Address Here.." class="form-control" <?= ifShowUserInfo($selectedUserRow, "email", $showUserInfo) ?>>
		         </div>
		         <?php if(!$showUserInfo): ?>
			    	<button type="submit" class="btn btn-lg btn-info" name="command" value="create">Register</button>	
				<?php else: ?>
					<button type="submit" class="btn btn-lg btn-info" name="command" value="update">Update</button>	
					<button type="submit" class="btn btn-lg btn-info" name="command" value="delete">Delete</button>	
				<?php endif ?>

		         				
		      </div>
		      <?php if ($output): ?>
		      		<?php foreach ($output as $value): ?>
		      			<div class="col-sm-12 alert alert-danger" role="alert">
							  <?= $value ?>
						</div>	
		      		<?php endforeach ?>	
		      <?php endif ?>
		   </form>
		</div>
	</div>
</body>
</html>