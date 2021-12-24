<?php

/*
================================================
== Manage Members Page
== You can Edit | Delete | Approve Comments from here
================================================
*/

session_start();
    $pageTitle = 'Comments';
if (isset($_SESSION['Username'])){
    include 'ini.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {  // Manage Members Page
        
        $stmt = $connect->prepare(" SELECT comments.*,items.Name,users.Username FROM comments
                                    INNER JOIN items ON comments.item_id = items.item_ID
                                    INNER JOIN users ON comments.user_id = users.UserID
                                    ORDER BY c_id DESC ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!empty($rows)){
    ?>
    <div class="container">
        <h1 class='text-center'>Manage Comments</h1>
           
        <div class="table-responsive">
                <table class="table text-center main-table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#ID</td>
                            <th>Comment</th>
                            <th>Item Name</th>
                            <th>User Name</th>
                            <th>Added Date</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <?php 
                        foreach ($rows as $row) {
                            echo "<tbody>";
                                echo "<tr>";
                                    echo "<td>" . $row['c_id'] . "</td>";
                                    echo "<td>" . $row['comment'] . "</td>";
                                    echo "<td>" . $row['Name'] . "</td>";
                                    echo "<td>" . $row['Username'] . "</td>";
                                    echo "<td>" . $row['comment_date'] ."</td>";
                                    echo "<td>
                                            <a href='comments.php?do=Edit&comid=". $row['c_id'] ." ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='comments.php?do=Delete&comid=". $row['c_id'] ." ' class='btn btn-danger confirm'>Delete</a> ";
                                        if ($row['status'] == 0){
                                           echo " <a href='comments.php?do=Approve&comid=". $row['c_id'] ." ' class='btn btn-info text-white'><i class='fa fa-check'></i> Approve</a>";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            echo "</tbody>";
                        }
                        
                    ?>
              </table>
            </div>
        </div>
                        <?php }else{
                             echo "<div class='container'>";
                             echo "<div class='alert alert-info my-5'>There's No Record Show</div>";
                         echo "</div>";
                        } ?>
       <?php 

        } elseif($do == 'Edit') {  //  Edit Page
        
           $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']): 0;     
           
                    $stmt = $connect->prepare("SELECT * FROM  comments  WHERE  c_id = ? ");
                    $stmt->execute(array($comid ));
                    $row = $stmt->fetch();
                    $count = $stmt->rowCount();    
                    
                    if ($count > 0){ ?>
 
                        <h1 class='text-center'>Edit Comment</h1>
                        <div class="container for">
                            <form action="?do=Update" method="post" class='form-horizontal mx-auto'>
                                <input type="hidden" name="comid" value="<?php echo $comid; ?>">
                                <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                                    <textarea name="comment" class="form-control" placeholder=" " ><?php echo $row['comment'] ?></textarea>
                                    <label class="form-label fw-bold">Comment</label>
                                </fieldset>

                                <fieldset class="row justify-content-start mx-auto col-sm-8">
                                        <input type="submit" class="btn btn-primary col-3 " value="Save">       
                                </fieldset>
                            </form>
                        </div> 
        <?php 
                    } else {
                        echo '<div class="container">';
                        $theMsg = '<div class="alert alert-danger my-5">There\'s No Such ID </div>';
                        redirectHome($theMsg);
                        echo '</div>';
                    }
                 } elseif ($do == 'Update') {

                  echo "<h1 class='text-center'>Update Comment</h1>";
                  echo "<div class='container'>";
                  if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                    $comid   = $_POST['comid'];
                    $comment = $_POST['comment'];
                   
                         // Update The Database With This Info
                        $stmt = $connect->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                        $stmt-> execute(array($comment,$comid));

                        $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Update</strong> </div>";
                        redirectHome($theMsg,'back');
                    
                   

                  }  else {
                    $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Direct </div>";
                        
                         redirectHome($theMsg,'',5);
                     }
                     echo "</div>";

                 } elseif ($do == 'Delete') {   // Delete Page

                    echo "<h1 class='text-center'>Delete Comment</h1>";
                    echo "<div class='container'>";
                    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']): 0; 

                    $check = checkItem('c_id', 'comments',$comid );
                    
                    if ($check > 0){ 

                        $stmt = $connect->prepare("DELETE FROM comments WHERE c_id = ? ");
                        $stmt->execute(array($comid));
                        $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Deleted</strong> </div>";
                        redirectHome($theMsg,'back');
                    } else {
                        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
                        redirectHome($theMsg);
                    }
                    echo "</div>";

                 } elseif ($do == 'Approve'){

                    echo "<h1 class='text-center'>Approve Member</h1>";
                    echo "<div class='container'>";
                    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']): 0; 

                    $check = checkItem('c_id', 'comments',$comid );
                    
                    if ($check > 0){ 

                        $stmt = $connect->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                        $stmt->execute(array($comid ));
                        $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Approved</strong> </div>";
                        redirectHome($theMsg,'back');
                    } else {
                        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
                        redirectHome($theMsg);
                    }
                    echo "</div>";
                 }


    include $tpl ."footer.php";

} else {
    header('Location: index.php');
    exit();
}

?>
<script>
    $(".confirm").click(function(){
        return confirm('Do You Want To Delete This Comment?');
    });
</script>