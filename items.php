<?php

    session_start();
    $pageTitle = 'Show Item';
    include "ini.php"; 
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']): 0;     
           
    $stmt = $connect->prepare("SELECT items.*,categories.Name AS cat_name, users.Username ,users.phone FROM items
                                INNER JOIN categories ON items.Cat_ID = categories.ID
                                INNER JOIN users ON items.Member_ID = users.UserID
                                WHERE  item_ID = ? AND Approve = 1");

    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();  
    if($count > 0) {  
    $item = $stmt->fetch();
      
    ?>
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
   <div class="container">
       <div class="row">
           <div class="col-md-3 ">
               <?php
                    if(empty($item['avatar'])){
                        echo '<img src="./admin/uploads/avatar/download.png" alt="" title="" class="card-img-top img-thumbnail " width="400px" height="200px" >';
                       } else {

                        echo '<img src="./admin/uploads/avatar/'.$item['avatar'].'" alt="" title="" class="card-img-top img-thumbnail " width="400px" height="200px">';
                       }
               ?>
           </div>
           <div class="col-md-9 item-info">
                <h2 class=""><?php echo $item['Name'] ?></h2>
                <p class=""><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li class=""><span>Added Date: </span><?php echo $item['Add_Date'] ?></li>
                    <li class=""><span>Price: </span> <?php echo $item['Price'] ?></li>
                    <li class=""><span>Made IN: </span> <?php echo $item['Country_Made'] ?></li>
                    <li class=""><span>Category:</span> <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['cat_name'] ?> </a></li>
                    <li class=""><span>Added By: </span>  <?php echo $item['Username'] ?></li>
                    <li class=""><span>Phone: </span>  <?php echo $item['phone'] ?></li>
                    <li class="tags-item"><span>Tags: </span>
                        <?php
                            $allTags = explode(",", $item['tags']);
                            foreach($allTags as $tag) {
                                $tag = str_replace(' ','',$tag);
                                $lowertag = strtolower($tag);
                                if( !empty($tag)){
                                echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                                }
                            }
                        ?>
                    </li>
                    </ul>
           </div>
       </div>
       <hr>
       <?php if(isset($_SESSION['user'])){ ?>
       <div class="row">
       <div class="col-md-3 text-center"></div>

           <div class="col-md-9">
               <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid='. $item['item_ID'] ?>" method='POST'>
                        <textarea name="comment" class="form-control" required></textarea>
                        <input type="submit" class="btn btn-primary" value="Add Comment">
                    </form>

                    <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                            $comment = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                            $itemid  = $item['item_ID'];
                            $userid  = $_SESSION['uid'];

                            if (! empty($comment)){
                                $stmt = $connect->prepare("INSERT INTO 
                                comments(comment, status, comment_date, item_id , user_id) 
                                VALUES(:comment, 0, NOW(),:itemid, :userid )");
                                $stmt->execute(array(
                                    'comment' => $comment,
                                    'itemid'  => $itemid,
                                    'userid'  => $userid
                                ));
                                if ($stmt) {
                                    echo '<div class="alert alert-success">Comment Added</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">Comment Must Be Not Empty</div>';
                            }
                        }
                    ?>
                </div>
            </div>
       </div>
       <?php } else {
           echo "<a href='login.php'>Login</a> Or <a href='login.php'> Register</a> To Add Comment";
       } ?>
       <hr>
       <?php
            $stmt = $connect->prepare(" SELECT comments.*,users.Username,users.avatar FROM comments
            INNER JOIN users ON comments.user_id = users.UserID
            WHERE item_id = ?
            AND status = 1
            ORDER BY c_id DESC ");
            $stmt->execute(array($item['item_ID']));
            $comments = $stmt->fetchAll();
    ?>
    <?php
        foreach($comments as $comment){ ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <?php 
                            if(empty($comment['avatar'])){
                                echo '<img src="./admin/uploads/avatar/download.png" alt="" title="" class="card-img-top img-thumbnail " width="400px" height="200px" >';
                            } else {

                                echo '<img src="./admin/uploads/avatar/'.$comment['avatar'].'" alt="" title="" class="card-img-top img-thumbnail " width="400px" height="200px">';
                            }
                        ?>
                        <!-- <img src="moslah.jpg" alt="" title="" class="img-responsive img-thumbnail img-circle">     -->
                        <?php echo $comment['Username']?>
                    </div>
                    <div class="col-sm-10">
                        <p class="lead">
                            <?php echo $comment['comment'] ?>
                        </p>
                    </div>
                </div>
                <hr>
            </div>
      <?php  } ?>
   </div>
   
<?php 

    } else {
            echo '<div class="container">';
                 echo '<div class="alert alert-danger my-5">There\'s No Such ID OR This Item Waiting Appooval</div>';
            echo '</div>';
    }
    include $tpl ."footer.php";
?>