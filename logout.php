<?php
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
   $_SESSION = [];
   
   header('Refresh: 0; URL = index.php');
?>