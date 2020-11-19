<?php 
    /*
     * Purpose: Common header used for every page.
     *          Display corresponding data for different pages.
     * Author:  Yange Zhu
     * Date:    Nov 6, 2020
     */
    require_once("./db_connect.php");
    require_once("functions.php");
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $getCategoryQuery = "SELECT * FROM categories";
    $categoriesStatement = $db->prepare($getCategoryQuery);
    $categoriesStatement->execute();
    $categories = $categoriesStatement->fetchAll();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">ABC Interactive</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php isActive('index.php') ?>">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">History</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php isActive('aTypeProject.php') ?>" href="#"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php foreach($categories as $category): ?>
            <a class="dropdown-item" href="aTypeProject.php?category=<?= urlencode($category['name']) ?>"><?= $category['name'] ?></a>
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
        <a class="nav-link dropdown-toggle <?php if(!isset($_SESSION['username'])){ echo 'disabled';} isActive(['management.php','users.php','newProject.php','category.php']); ?>" href="management.php" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Management
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="management.php">Manage All</a>
            <?php if (isset($_SESSION['isAdministrator']) && $_SESSION['isAdministrator']): ?>
            	<a class="dropdown-item" href="users.php">Manage Users</a>          
            <?php endif ?>
            <a class="dropdown-item" href="editUsers.php">New Users</a>
            <a class="dropdown-item" href="newProject.php">New Project</a>
            <a class="dropdown-item" href="category.php">New Category</a>
          <!-- <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a> -->
        </div>
      </li>

      <li class="nav-item <?php isActive('index.php') ?>">
        <form class="form-inline" method="post" action="search.php">
          <input class="form-control" type="search" id="searchWord" name="searchWord" placeholder="Search" aria-label="Search">
          <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" 
              id="sampleDropdownMenu" data-toggle="dropdown">
              Categories
              </button>
              <div class="dropdown-menu" >

                <?php foreach($categories as $category): ?>
                  <div>
                    <input style="display: inline-block;" type="checkbox" name="searchCategories[]" id="<?= $category['name'] . $category['id'] ?>" value="<?= $category['id'] ?>">
                      <label style="display: inline-block;" for="<?= $category['name'] . $category['id'] ?>"><?= $category['name'] ?></label>
                  </div>
                <?php endforeach ?>

              </div>
          </div>
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </li>

    </ul>
<!--     <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>               navbar-collapse collapse  dual-collapse2  w-100 navbar-right nav-item dropdown
    </form> -->
    <div>
        <ul class=" navbar-nav ml-auto">
            <li class="nav-item <?php isActive('registration.php') ?>">
                <?php if(isset($_SESSION['username'])): ?>
                <span class="nav-link"><?= $_SESSION['username'] ?></span>
                <?php else: ?>
                <a class="nav-link" href="editUsers.php">Register</a>
                <?php endif ?>
            </li>
            <li class="nav-item">
                <?php if(isset($_SESSION['username'])): ?>
                <a class="nav-link" href="logout.php">Log Off</a>
                <?php else: ?>
                <a class="nav-link <?php isActive('login.php') ?>" href="login.php">Log in</a>
                <?php endif ?>
            </li>
        </ul>
    </div>
  </div>
</nav>