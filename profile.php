<?php

    session_start();
    $pageTitle = 'Profile';
    include "ini.php"; 
    if(isset($_SESSION['user'])){
        $getUser = $connect->prepare("SELECT * FROM users WHERE Username = ? AND UserID = ?");
        $getUser->execute(array($sessionUser,$_SESSION['uid']));
        $info = $getUser->fetch();

        $userid = $info['UserID'];
    ?>
    
    <h1 class="text-center">My Profile</h1>
   <div class="information block">
       <div class="container">
             
            <div class="card my-1 shadow-sm">
            
                <div class="card-header bg-primary text-light">
                    <h5 class="card-title">My Information</h5>
                </div>
                <div class="card-body">
                <?php
                    if(empty($info['avatar'])){
                        echo '<img src="./admin/uploads/avatar/download.png" alt="" title="" class="card-img-top img-thumbnail pro-img" >';
                       } else {

                        echo '<img src="./admin/uploads/avatar/'.$info['avatar'].'" alt="" title="" class="card-img-top img-thumbnail pro-img" >';
                       }
               ?>
                    <ul class="list-unstyled">
                    
                        <li><i class="fa fa-unlock-alt fa-fw"></i> <span>Login Name </span> : <?php echo $info['Username']; ?> </li>
                        <li><i class="fa fa-envelope fa-fw"></i> <span> Email </span> : <?php echo $info['Email']; ?> </li>
                        <li> <i class="fa fa-user fa-fw"></i> <span> Full Name </span> : <?php echo $info['FullName']; ?> </li>
                        <li> <i class="fa fa-user fa-fw"></i> <span> Phone </span> : <?php echo $info['phone']; ?> </li>
                        <li> <i class="fa fa-calendar fa-fw"></i> <span>  Registerd Date </span> : <?php echo $info['Date']; ?> </li>
                    </ul>
                    <a href='edit.php?do=Edit&userid=<?php echo $_SESSION['uid'] ?>' class="btn btn-primary"> Edit Information </a>
                </div>
            </div>
       </div>
   </div>

   <div id="my-ads" class="my-ads block">
       <div class="container">
            <div class="card my-1 shadow-sm">
                <div class="card-header bg-primary text-light">
                    <h5 class="card-title">My Ads</h5>
                </div>
                <div class="card-body">
                
        <?php 
         $myItems = getAllFrom("*", "items", "WHERE Member_ID  = $userid","","item_ID");

         if (! empty($myItems )){
           echo '<div class="row">';
                foreach($myItems as $items) {
                    echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="card my-1 item-box"> ';
                        if ($items['Approve'] == 0){ echo '<span class="approve-status">Waiting Approve</span>'; }
                        echo '<p class="card-text price-tag"><span class="p-2 my-3">' . $items['Price'] . '</span></p>';
                        if(empty($items['avatar'])){
                            echo '<img src="./admin/uploads/avatar/download.png" alt="" title="" class="card-img-top  " width="400px" height="200px" >';
                           } else {
                            echo '<img src="./admin/uploads/avatar/'.$items['avatar'].'" alt="" title="" class="card-img-top " width="400px" height="200px">';
                           }
                            echo '<div class="card-body">';
                                echo '<h5 class="card-title"><a href="items.php?itemid='. $items['item_ID'] .'">'. $items['Name'] .'</a></h5>';
                                echo ' <p class="card-text">'. $items['Description'] .'</p>';
                                echo ' <div class="date">'. $items['Add_Date'] .'</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo "Thers's No Ads To Show Create <a href='newad.php'>New Ad</a>";
            }
        ?>
    </div>
                </div>
            </div>
       </div>
   </div>

   <div class="my-comments block">
       <div class="container">
            <div class="card my-1 shadow-sm">
                <div class="card-header bg-primary text-light">
                    <h5 class="card-title">Latest Comments</h5>
                </div>
                <div class="card-body">
             <?php
                $myComments = getAllFrom("comment", "comments", "WHERE user_id  = $userid","","c_id");

                if (! empty($myComments)){
                    foreach($myComments as $comment){
                        echo '<p>' . $comment['comment'] . '</p>';
                    }
                } else {
                    echo "Thers's No Comments To Show";
                }
            ?>
                </div>
            </div>
       </div>
   </div>

   
<?php 
    } else {
        header('Location: login.php');
        exit();
    }
    include $tpl ."footer.php";

;
?>