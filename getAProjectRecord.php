<?php
    /*
     * Purpose: Get a record based on the id in GET.
     *          
     * Author:  Yange Zhu
     * Date:    Nov 8, 2020
     */
    // Build and prepare SQL String with :id placeholder parameter.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $query = "SELECT * FROM projects WHERE id = :id LIMIT 1";
    $statement = $db->prepare($query);
    
    // Sanitize $_GET['id'] to ensure it's a number.
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        header("Location: index.php");
        exit;
    }
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    // Bind the :id parameter in the query to the sanitized
    // $id specifying a binding-type of Integer.
    $statement->bindValue('id', $id, PDO::PARAM_INT);
    $statement->execute();
    
    // Fetch the row selected by primary key id.
    $row = $statement->fetch();
?>