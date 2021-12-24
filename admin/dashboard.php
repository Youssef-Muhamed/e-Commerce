<?php 
session_start();
    
$pageTitle = 'Dashboard';

if (isset($_SESSION['Username'])){
    include 'ini.php';
    $numUsers = 5;
    $latestUsers = getLatest("*","users","UserID",$numUsers);

    $numItems = 5;
    $latestItems = getLatest("*","items","item_ID",$numUsers);

    $numComments = 4;
        /* Dashboard Page Start */
       
    ?>

    <div class="container text-center home-stats">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat shadow-sm st-member">
                    <i class='fa fa-users'></i>
                    <div class="info">
                        <a href="members.php"> 
                        Total Members <span> <?php echo countItem('UserID','users'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat shadow-sm st-pending">
                <i class='fa fa-user-plus'></i>
                    <div class="info">
                        <a href="members.php?do=Manage&page=Pending">
                            Pending Members <span><?php echo checkItem('RegStatus','users',0); ?></span>
                        </a> 
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat shadow-sm st-items">
                    <i class='fa fa-tag'></i>
                    <div class="info">
                        <a href="items.php"> 
                            Total Items <span> <?php echo countItem('item_ID','items'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat shadow-sm st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                    <a href="comments.php"> 
                            Total Comments <span> <?php echo countItem('c_id','comments'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="container latest">
    <div class="row my-3">

        <div class="col-sm-6">
           <div class="card shadow-sm">
                 <div class="card-header">
                    <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registerd Users
                 </div>
                    <div class="card-body">
                        <ul class="list-group latest-users">
                        <?php 
                            if (! empty($latestUsers)){
                                foreach ($latestUsers as $user){
                                    echo "<li class='list-group-item'>";
                                        echo   $user['Username'];
                                        echo  ' <a class="btn btn-success li-btn" href="members.php?do=Edit&userid= '. $user['UserID'] .'"> ' ;
                                        echo ' <i class="fa fa-edit"></i> Edit ';
                                        if ($user['RegStatus'] == 0){
                                            echo " <a href='members.php?do=Activate&userid=". $user['UserID'] ." ' class='btn btn-info text-white li-btn'><i class='fa fa-check'></i> Activate </a> ";
                                        }
                                        echo   " </a> ";
                                    echo "</li>";
                                }
                            } else {
                                echo "There's No Record To Show";
                            }
                        ?>
                        </ul>
                    </div>
                 
            </div>
        </div>

        <div class="col-sm-6">
           <div class="card shadow-sm">
                 <div class="card-header">
                    <i class="fa fa-tag"></i> Latest <?php echo $numItems ; ?> Items
                 </div>
                    <div class="card-body"><ul class="list-group latest-users">
                        <?php 
                            if (! empty($latestItems)){
                                foreach ($latestItems as $item){
                                    echo "<li class='list-group-item'>";
                                        echo   $item['Name'];
                                        echo  ' <a class="btn btn-success li-btn" href="items.php?do=Edit&itemid= '. $item['item_ID'] .'"> ' ;
                                        echo ' <i class="fa fa-edit"></i> Edit ';
                                        if ($item['Approve'] == 0){
                                            echo " <a href='items.php?do=Approve&itemid=". $item['item_ID'] ." ' class='btn btn-info text-white li-btn'><i class='fa fa-check'></i> Approve </a> ";
                                        }
                                        echo   " </a> ";
                                    echo "</li>";
                                }
                            } else {
                                echo "There's No Record To Show";
                            }
                        ?>
                        </ul>
                    </div>
                 
            </div>
        </div>
    </div>
            <!-- Start Latest Comment -->

    <div class="row my-3">
        <div class="col-sm-6">
           <div class="card shadow-sm">
                 <div class="card-header">
                    <i class="fa fa-comments"></i> Latest <?php echo $numComments ; ?>  Comments
                 </div>
                    <div class="card-body">
                    <?php
                        $stmt = $connect->prepare(" SELECT comments.*,users.Username AS member FROM comments
                                            INNER JOIN users ON comments.user_id = users.UserID ORDER BY c_id DESC LIMIT $numComments ");
                        $stmt->execute();
                        $comments = $stmt->fetchAll();
                        if (!empty($comments)){
                            foreach($comments as $comment){
                                echo "<div class='comment-box'>";
                                    echo '<span class="member-n"> <a href="members.php?do=Edit&userid=' . $comment['user_id'] . '">'
                                     . $comment['member'] . "</a></span>";
                                    echo "<p class='comment-c'>" . $comment['comment'] . "</p>";
                                echo "</div>";
                            }
                        } else {
                            echo "There's No Record To Show";
                        }
                    ?>
                    </div>
                 
            </div>
        </div>
    </div>
</div>
    <?php  /* Dashboard Page End */

    include $tpl ."footer.php";
} else {
        header('Location: index.php');
        exit();
}
