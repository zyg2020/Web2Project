<?php 
    /*
     * Purpose: Common header used for every page.
     *          Display corresponding data for different pages.
     * Author:  Yange Zhu
     * Date:    Nov 6, 2020
     */
    require_once("./db_connect.php");
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $path = explode('?', $_SERVER['REQUEST_URI']);
    $currentFile = basename($path[0]);

   // if ($currentFile=="show.php") {
   //  $headerTitle = $row['Title'];
   // }elseif ($currentFile=="edit.php"){
   //  $headerTitle = 'Edit Post';
   // }elseif ($currentFile=="create.php") {
   //  $headerTitle = 'New Post';
   // }elseif ($currentFile=="fullContent.php"){
   //  $headerTitle = 'Full Blogs';
   // }else{
   //  $headerTitle = 'Index';
   // }
    $getCategoryQuery = "SELECT * FROM categories";
    $categoriesStatement = $db->prepare($getCategoryQuery);
    $categoriesStatement->execute();
    $categories = $categoriesStatement->fetchAll();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">ABC Interactive</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">History</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php foreach($categories as $category): ?>
            <a class="dropdown-item" href="#"><?= $category['name'] ?></a>
          <?php endforeach ?>
          <!-- <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a> -->
        </div>
      </li>
 <!--      <li class="nav-item">
        <a class="nav-link 
         " href="#" tabindex="-1" aria-disabled="true">Management</a>
      </li> -->

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php if(!isset($_SESSION['username'])){ echo 'disabled';} ?>" href="management.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Management
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="management.php">Manage All</a>
            <a class="dropdown-item" href="newProject.php">New Project</a>
            <a class="dropdown-item" href="category.php">New Category</a>
          <!-- <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a> -->
        </div>
      </li>

    </ul>
<!--     <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form> -->
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <?php if(isset($_SESSION['username'])): ?>
                <span class="nav-link"><?= $_SESSION['username'] ?></span>
                <?php else: ?>
                <a class="nav-link" href="registration.php">Register</a>
                <?php endif ?>
            </li>
            <li class="nav-item">
                <?php if(isset($_SESSION['username'])): ?>
                <a class="nav-link" href="logout.php">Log Off</a>
                <?php else: ?>
                <a class="nav-link" href="login.php">Log in</a>
                <?php endif ?>
            </li>
        </ul>
    </div>
  </div>
</nav>