<?php 
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}

	$ErrorMessage = [];
	if ($_POST && !empty($_POST['command']) && !empty($_POST['comment']) && (isset($_SESSION['username']) || !empty($_POST['name'])) ) {
		require_once('db_connect.php');

		if (!isset($_SESSION['username'])) {
		 	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		 	if (!$name) {
			array_push($ErrorMessage, 'Please input a valid name');
			}
		} else {
			$userId = $_SESSION['userId'];
		}
		
		$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if (!$comment) {
			array_push($ErrorMessage, 'Please input valid comment');
		}

		if (!$ErrorMessage && isset($_POST['id'])) {

			$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
			if (!$id) {
				array_push($ErrorMessage, 'Invalid project Id');

			}else{
				$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);

				date_default_timezone_set('America/Winnipeg');

				try {
					if ($_POST['command']=='create') {
						if (!isset($_SESSION['username'])) {
							$newVisitorQuery = "INSERT INTO users (name,userType) VALUES (:name,:userType)";
							$statement = $db->prepare($newVisitorQuery);
							$bindValues = [
									'name'=>$name,
									'userType'=>'visitor'
									];
							$statement->execute($bindValues);
							$userId = $db->lastInsertId();
						}

						$commentQuery = "INSERT INTO comments (content,createdTimestamp,projectId,userId) 
											VALUES (:content,:createdTimestamp,:projectId,:userId)";
						$statement = $db->prepare($commentQuery);
						$bindValues = [
									'content'=>$comment,
									'createdTimestamp' => date('Y-m-d H:i:s',strtotime("now")),
									'projectId' => $id,
									'userId' => $userId
									];
						$statement->execute($bindValues);

						$email = "";
						$from_name = "";
						if (isset($_SESSION['username']) && !empty($_SESSION['username'])){
							$userEmailQuery = "SELECT email, name FROM users WHERE username = :username";
							$userEmailStm = $db->prepare($userEmailQuery);
							$username = filter_var($_SESSION['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
							$userEmailStm->bindValue(':username', $username);
							$userEmailStm->execute();
							$row =  $userEmailStm->fetch();
							$from_email = $row['email'];
							$from_name = $row['name'];
						}else{
							$from_email = 'zhuyange2018@gmail.com';
							$from_name = 'visitor';
						}

						$subject = '';
						$to_email = '';
						$to_name = '';
						$commentedProjectQuery = "SELECT p.title, u.email, u.name FROM projects p INNER JOIN users u ON p.userId = u.id WHERE p.id = :id";
						$commentedProjectStm = $db->prepare($commentedProjectQuery);
						$commentedProjectStm->bindValue(':id', $id);
						$commentedProjectStm->execute();
						$row = $commentedProjectStm->fetch();

						$subject = 'New Comment On ' . $row['title'];
						$to_email = $row['email'];
						$to_name = $row['name'];

						require_once('mailtrap.php');
					}					
				} catch (Exception $e) {
					array_push($ErrorMessage, $e->getMessage());
				}

				if (!$ErrorMessage) {
					header('Location: ' . $_SERVER["HTTP_REFERER"] );
					exit;
				}
				
			}
		}
	}

//print_r($ErrorMessage);
?>


<?php if (isset($ErrorMessage) && !empty($ErrorMessage)): ?>
<!DOCTYPE html>
<html>
<head>
	<title>Error Occured During Adding Comments</title>
</head>
<body>
	<?php foreach ($ErrorMessage as $value): ?>
		<h3><?= $value ?></h3>
	<?php endforeach ?>
</body>
</html>
	
<?php endif ?>