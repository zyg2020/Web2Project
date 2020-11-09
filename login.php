<?php 
	require_once('db_connect.php');
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	if ($_SERVER["REQUEST_METHOD"] === "POST" ){
		
		$errorMsg = '';

		if (!empty($_POST['username']) && !empty($_POST['password'])) {
			$accountsQuery = "SELECT * FROM users WHERE username IS NOT NULL";
			$accountsStatement = $db->prepare($accountsQuery);
			$accountsStatement->execute();

			$loginSucceed = false;
			while ($row = $accountsStatement->fetch()){
				if (strcasecmp($_POST['username'], $row['username']) === 0 && 
	                  $_POST['password'] == $row['password']) {

	                  $_SESSION['valid'] = true;
	              	  $_SESSION['userId'] = $row['id'];
	                  $_SESSION['timeout'] = time();
	                  $_SESSION['username'] = $row['username'];

	                  if (strcasecmp($row['userType'], 'administrator') !== 0 ) {
	                  	$_SESSION['isAdministrator'] = false;
	                  }else{
	                  	$_SESSION['isAdministrator'] = true;
	                  } 

	                  $loginSucceed = true;
	                  break;     
				}
			}

			if (!$loginSucceed) {
				$errorMsg = 'Wrong username or password';
			}


			if (!$errorMsg) {
				header("Location: management.php");
        		exit;
			}
		}else{
			$errorMsg = 'Please fill out form.';
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
</head>
<body>
	<div class="container box">
		<?php require("./header.php") ?>
		<div id="login">
        <!-- <h3 class="text-center  pt-5">Login form</h3> -->
        <div class="container text-dark pt-5">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action = "login.php" method="post">
                            <h3 class="text-center text-info text-dark">Login</h3>
                            <div class="form-group">
                                <label for="username" class="text-info text-dark">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info text-dark">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="remember-me" class="text-info text-dark"><span>Remember me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                            <?php if(isset($errorMsg) && !empty($errorMsg)): ?>
                            <div class="form-group">
                                <div class="col-sm-12 alert alert-danger" role="alert">
									  <?= $errorMsg ?>
								</div>
                            </div>
                        	<?php endif ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</body>
</html>