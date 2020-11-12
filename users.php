<?php
	require_once("./db_connect.php");
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	if(!isset($_SESSION['isAdministrator']) || !$_SESSION['isAdministrator']) {
		header("Location: index.php");
		exit;
	}


	$usersQuery = "SELECT * FROM users";
	$usersStatement = $db->prepare($usersQuery);
	$usersStatement->execute();
	$orderNum = 0;
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <style type="text/css">
    	.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
			background-color: #87CEEB;
		}
    </style>
</head>
<body>
	<div class="container box ">
		<?php require("./header.php") ?>
	<div class="table-responsive">
		<table class="table  table-striped table-hover" style=" white-space: nowrap;">
			<caption style="caption-side: top;">List of users</caption>
				<thead>
					<tr>
					  <th scope="col">#</th>
					  <th scope="col">Name</th>
					  <th scope="col">Email</th>
					  <th scope="col">userType</th>
					  <th scope="col">address</th>
					  <th scope="col">City</th>
					  <th scope="col">Province</th>
					  <th scope="col">Country</th>
					  <th scope="col">Title</th>
					  <th scope="col">BranchOfficeName</th>
					  <th scope="col">Username</th>
					  <th scope="col">Delete</th>
					  <th scope="col">Update</th>
					</tr>
				</thead>
				<tbody>
				<?php while ($userRow = $usersStatement->fetch()): ?>
					<tr>
					  <th scope="row"><?= ++$orderNum ?></th>
					  <td><?= $userRow['name'] ?></td>
					  <td><?= $userRow['email'] ?></td>
					  <td><?= $userRow['userType'] ?></td>
					  <td><?= $userRow['address'] ?></td>
					  <td><?= $userRow['city'] ?></td>
					  <td><?= $userRow['province'] ?></td>
					  <td><?= $userRow['country'] ?></td>
					  <td><?= $userRow['title'] ?></td>
					  <td><?= $userRow['branchOfficeName'] ?></td>
					  <td><?= $userRow['username'] ?></td>
					  <td><a href="" class="badge badge-danger categoryDelete">Delete</a></td>
					  <td><a href="editUsers.php?id=<?= $userRow['id'] ?>" class="badge badge-primary categoryUpdate r-5">Update</a></td>
					</tr>
				<?php endwhile ?>
				</tbody>
		</table>
	</div>
	</form>		
	</div>
</body>
</html>


