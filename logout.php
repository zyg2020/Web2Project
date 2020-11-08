<?php
   session_start();
   $_SESSION = [];
   
   header('Refresh: 0; URL = index.php');
?>