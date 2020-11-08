<?php
	require_once('db_connect.php');

  define('ADMIN_LOGIN','wally');

  define('ADMIN_PASSWORD','mypass');
  $accountsQuery = "SELECT username, password FROM users WHERE username IS NOT NULL"
  $accountsStatement = $db->prepare($accountsQuery);
  $accountsStatement->execute();

  while ($row = $accountsStatement->fetch()) {
  	if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) 
  		|| ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)
		|| ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD))
  }
  

  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])

      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)

      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) {

    header('HTTP/1.1 401 Unauthorized');

    header('WWW-Authenticate: Basic realm="Our Blog"');

    exit("Access Denied: Username and password required.");

  }

   

?>