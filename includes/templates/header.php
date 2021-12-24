<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css;?>all.css">
    <link rel="stylesheet" href="<?php echo $css;?>front.css">
</head>
<body>
<div class="upper-bar ">
  <div class="container">
        <?php
              
              if (isset($_SESSION['user'])){ ?>

                <div class=" dropdown my-info">
                  <a href="profile.php">
                  <?php 

                  if(!isset($_SESSION['uimage'])){
                    echo '<img src="./admin/uploads/avatar/download.png" alt="" title="" class="card-img-top img-thumbnail my-image" width="400px" height="200px" >';
                  } else {

                    echo '<img src="./admin/uploads/avatar/'.$_SESSION['uimage'].'" alt="" title="" class="card-img-top img-thumbnail my-image" width="400px" height="200px">';
                  }
                  ?>
                  </a>

                    <span class=" dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                       Welcome  <?php echo $sessionUser ?>
                    </span>
                    <ul class="dropdown-menu" >
                      <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                      <li><a class="dropdown-item" href="profile.php#my-ads">My Ads</a></li>
                      <li><a class="dropdown-item" href="newad.php">New Item</a></li>
                      <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>

              <?php 
                
              } else {
          ?>
            <a href="login.php">
              <span class='right'>Login/SignUp</span> 
            </a>
            <?php } ?>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Online Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
    
      </ul>
      
      <ul class="navbar-nav">
        <?php  
        $myCats = getAllFrom("*", "categories", "WHERE parent = 0","","ID","ASC");
       
          foreach($myCats as $cat){ ?>

             <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"  href="categories.php?pageid=<?php echo $cat['ID'] ?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown" >
                  <?php echo $cat['Name'];?>
                </a>

                 <ul  class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php $myCats2 = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}","","ID","DESC"); ?>

                <?php  foreach($myCats2 as $cats) { ?>
                  <li><a class="dropdown-item"  href="categories.php?pageid=<?php echo $cats['ID'] ?>"> <?php echo $cats['Name'];?></a></li>
                  <?php
                } ?>
                </ul>

              </li>

        <?php
        
          }
        ?>
      </ul>
    </div>
  </div>
</nav>

