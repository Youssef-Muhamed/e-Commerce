<?php

/*
================================================
== Manage Members Page
== You can Add | Edit | Delete Members from here
================================================
*/
ob_start();
session_start();
    $pageTitle = 'Members';
if (isset($_SESSION['Username'])){

    include 'ini.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {  // Manage Members Page
        
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending'){
            $query = 'AND RegStatus = 0';
        }
        $stmt = $connect->prepare(" SELECT * FROM  users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)){
    ?>
    <div class="container">
        <h1 class='text-center'>Manage Members</h1>
           
        <div class="table-responsive">
                <table class="table text-center main-table manage-members table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#ID</td>
                            <th>Image</td>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>Registerd Date</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <?php 
                        foreach ($rows as $row) {
                            echo "<tbody>";
                                echo "<tr>";
                                    echo "<td>" . $row['UserID'] . "</td>";
                                    echo "<td>";
                                    if (empty($row['avatar'])){
                                        echo " <img src='uploads/avatar/download.png' alt='Image'>";
                                    }else {echo " <img src='uploads/avatar/" . $row['avatar'] . "' alt='Image'>";}
                                   
                                    echo "</td>";
                                    echo "<td>" . $row['Username'] . "</td>";
                                    echo "<td>" . $row['Email'] . "</td>";
                                    echo "<td>" . $row['FullName'] . "</td>";
                                    echo "<td>" . $row['phone'] . "</td>";
                                    echo "<td>" . $row['Date'] ."</td>";
                                    echo "<td>
                                            <a href='members.php?do=Edit&userid=". $row['UserID'] ." ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='members.php?do=Delete&userid=". $row['UserID'] ." ' class='btn btn-danger confirm'>Delete</a> ";
                                        if ($row['RegStatus'] == 0){
                                           echo " <a href='members.php?do=Activate&userid=". $row['UserID'] ." ' class='btn btn-info text-white'><i class='fa fa-check'></i> Activate</a>";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            echo "</tbody>";
                        }
                    ?>
              </table>
              <a href="members.php?do=Add" class="btn btn-primary my-4"><i class="fas fa-plus"></i> New Member</a>
            </div>
        </div>
                        <?php }else {
                            echo "<div class='container'>";
                                echo "<div class='alert alert-info'>There's No Record Show</div>";
                                echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> New Member</a>';
                            echo "</div>";
                        } ?>
       <?php } elseif ($do == 'Add') { //  Add Page ?> 

            <h1 class='text-center'>Add New Member</h1>
            <div class="container for">
                <form action="?do=Insert" method="post" class='form-horizontal mx-auto' enctype="multipart/form-data">
                   
                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="username" class="form-control" required = "required" placeholder=" " autocomplete='off'>
                        <label class="form-label fw-bold">Username</label>
                    </fieldset>
                    

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="password" name="password" class="password form-control" required = "required" autocomplete='new-password' placeholder=" ">
                        <label class="form-label fw-bold">Password</label>
                        <i class="show-pass fas fa-eye"></i>
                    </fieldset>


                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="email" name="email" class="form-control" required = "required" placeholder=" " >
                        <label class="form-label fw-bold">Email</label>
                    </fieldset>


                    <fieldset class="form-floating row my-3 mx-auto  col-sm-8">
                        <input type="text" name="full" class="form-control" required = "required" placeholder=" " >
                        <label class="form-label fw-bold">Full Name</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto  col-sm-8">
                        <input type="text" name="phone" class="form-control" required = "required" placeholder=" " >
                        <label class="form-label fw-bold">Phone</label>
                    </fieldset>

                    <fieldset class="mx-auto my-3 col-md-8">
                    <label class="form-label fw-bold col-md-3">User Image</label>
                        <input type="file" name="avatar" class="form-control"  placeholder=" " >
                    </fieldset>

                    <fieldset class="row justify-content-start my-3 mx-auto col-sm-8">
                            <input type="submit" class="btn btn-primary col-3 " value="Add Member">
                    </fieldset>
                </form>
            </div> 

        <?php } elseif ($do == 'Insert'){

                        
                        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                            echo "<h1 class='text-center'>Insert Member</h1>";
                            echo "<div class='container'>";

                            $user   = $_POST['username'];
                            $pass   = $_POST['password'];
                            $email  = $_POST['email'];
                            $name   = $_POST['full'];
                            $phone  = $_POST['phone'];
                            $hashPass = sha1($_POST['password']);
                            
                        
                        // Validate The Form
                        $formError = array();
                        if (strlen($user) < 3){
                            $formError[] = " User Name Can't Be Less Than <strong>3 Characters</strong>";
                        }
                        if (strlen($user) > 20){
                            $formError[] = " User Name Can't Be More Than <strong>20 Characters</strong> ";
                        }
                        if (empty($user)){
                            $formError[] = "  User Name  Can't Be <strong>Empty</strong> ";
                        }
                        if (empty($pass)){
                            $formError[] = " Password Can't Be <strong>Empty</strong> ";
                        }
                        if (empty($email)){
                            $formError[] = " Email Can't Be <strong>Empty</strong> ";
                        }
                        if (empty($name)){
                            $formError[] = " Full Name Can't Be <strong>Empty</strong> ";
                        }
                        
                        # Validate phone
                        if (!validate($phone, 1)) {
                            $formError[] = " Phone Can't Be <strong>Empty</strong> ";
                        } elseif (!validate($phone, 6)) {
                            $formError[] = 'Invalid Phone Number';
                        }
                        if(validate($_FILES['avatar']['name'],1)){
                            // Upload Image
                            $avatarName     = $_FILES['avatar']['name'];
                            $avatarSize     = $_FILES['avatar']['size'];
                            $avatarTmp      = $_FILES['avatar']['tmp_name'];
                            $avatarType     = $_FILES['avatar']['type'];
            
                            $avatarAllowExtention = array("jpeg","jpg","png","gif");
            
                            $tmp = explode('.',$avatarName);
                            $avatarExtention = strtolower(end($tmp));
                            if ( !empty($avatarName) && ! in_array($avatarExtention,$avatarAllowExtention)){
                                $formError[] = " The Extention Of Image Is Not <strong>Allowed</strong> ";
                            }
                            if ($avatarSize > 4194304){
                                $formError[] = " The Image Can't Be Larger Than <strong>4MB</strong> ";
                            }
                        }
            

                        foreach($formError as $error){
                            echo "<div class='alert alert-danger'>" . $error . "</div>";
                        }

                        // Check If There's No Error

                        if (empty($formError)){

                            if (empty($_FILES['avatar']['name'])) {
                                $avatar = '';
                            } else {
                                $avatar = rand().time() . '_' . $avatarName;
                                move_uploaded_file($avatarTmp,"./uploads/avatar/" . $avatar);
                            }
                           // Check if user exist in database
                            $check =  checkItem("Username","users",$user);
                            if ($check == 1){
                                $theMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                                redirectHome($theMsg,'back',5);
                                } else {
                                // Insert User Info The Database 
                                $stmt = $connect->prepare("INSERT INTO users(Username, Password, Email, FullName,phone,RegStatus,Date,avatar) VALUES(:user,:pass,:mail,:full,phone,0,now(),:avatar)");
                                $stmt->execute(array(
                                    'user'  => $user,
                                    'pass'  => $hashPass,
                                    'mail'  => $email,
                                    'full'  => $name,
                                    'phone' => $phone,
                                    'avatar'=> $avatar
                                ));
                                    $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Inserted</strong> </div>";
                                    redirectHome($theMsg);
                               }      
                            }
                    
                        } else {
                            echo "<div class='container'>";
                            $theMsg = '<div class="alert alert-danger my-5"> Sorry You Can\'t Browse This Page Direct </div>';
                            redirectHome($theMsg,'',5);
                            echo '</div>';
                        }
                        echo "</div>";

        } elseif($do == 'Edit') {  //  Edit Page
        
           $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']): 0;     
           
                    $stmt = $connect->prepare("SELECT * FROM  users  WHERE  UserID = ? LIMIT 1");
                    $stmt->execute(array($userid ));
                    $row = $stmt->fetch();
                    $count = $stmt->rowCount();    
                    
                    if ($count > 0){ ?>
 
                        <h1 class='text-center'>Edit Member</h1>
                        <div class="container for">
                            <form action="?do=Update" method="post" class='form-horizontal mx-auto' enctype="multipart/form-data">
                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                                    <input type="text" name="username" class="form-control" required = "required"  value="<?php echo $row['Username'] ?>" placeholder=" " autocomplete='off'>
                                    <label class="form-label fw-bold">Username</label>
                                </fieldset>
                                

                                <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                                    <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" >
                                    <input type="password" name="newpassword" class="form-control" autocomplete='new-password' placeholder=" ">
                                    <label class="form-label fw-bold">Password</label>
                                </fieldset>


                                <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                                    <input type="email" name="email" class="form-control" required = "required" value="<?php echo $row['Email'] ?>" placeholder=" " >
                                    <label class="form-label fw-bold">Email</label>
                                </fieldset>


                                <fieldset class="form-floating row my-3 mx-auto  col-sm-8">
                                    <input type="text" name="full" class="form-control" required = "required" value="<?php echo $row['FullName'] ?>" placeholder=" " >
                                    <label class="form-label fw-bold">Full Name</label>
                                </fieldset>
                                <fieldset class="form-floating row my-3 mx-auto  col-sm-8">
                                    <input type="number" name="phone" class="form-control" required = "required" value="<?php echo $row['phone'] ?>" placeholder=" " >
                                    <label class="form-label fw-bold">Phone</label>
                                </fieldset>

                                <fieldset class="mx-auto my-3 col-md-8">
                                    <label class="form-label fw-bold col-md-3"> Image</label>
                                    <input type="file" name="avatar" class="form-control" placeholder=" " >
                                </fieldset>
                                <fieldset class="mx-auto my-3 col-md-8">
                                <?php
                                    if (empty($row['avatar'])){
                                        echo " <img src='uploads/avatar/download.png' alt='Image' width='80px'>";
                                    }else {echo " <img src='uploads/avatar/" . $row['avatar'] . "' alt='Image'width='80px'>";} ?>

                                </fieldset>


                                    <fieldset class="row justify-content-start mx-auto col-sm-8 my-4">
                                            <input type="submit" class="btn btn-primary col-3 " value="Save">       
                                    </fieldset>
                            </form>
                        </div> 
        <?php 
                    } else {
                        echo '<div class="container">';
                        $theMsg = '<div class="alert alert-danger my-5">There\'s No Such ID </div>';
                        redirectHome($theMsg,'',5);
                        echo '</div>';
                    }
                 } elseif ($do == 'Update') {  // Update

                  echo "<h1 class='text-center'>Update Member</h1>";
                  echo "<div class='container'>";
                  if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                    $id     = $_POST['userid'];
                    $user   = $_POST['username'];
                    $email  = $_POST['email'];
                    $name   = $_POST['full'];
                    $phone  = $_POST['phone'];

                    // Passwrod
                    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
                    
                       // Validate The Form
                       $formError = array();
                       if (strlen($user) < 3){
                           $formError[] = " User Name Can't Be Less Than <strong>3 Characters</strong>";
                       }
                       if (strlen($user) > 20){
                           $formError[] = " User Name Can't Be More Than <strong>20 Characters</strong> ";
                       }
                       
                       if (empty($email)){
                           $formError[] = " Email Can't Be <strong>Empty</strong> ";
                       }
                       if (empty($name)){
                           $formError[] = " Full Name Can't Be <strong>Empty</strong> ";
                       }

                      if(validate($_FILES['avatar']['name'],1)){

                          $tmpPath    =  $_FILES['avatar']['tmp_name'];
                          $imageName  =  $_FILES['avatar']['name'];
                          $imageSize  =  $_FILES['avatar']['size'];
                          $imageType  =  $_FILES['avatar']['type'];

                          $exArray   = explode('.',$imageName);
                          $extension = end($exArray);

                          $FinalName = rand().time().'.'.$extension;


                          if(!validate($extension,5)){
                              $formError[] = "Error In Extension";
                          }

                      }
                      foreach($formError as $error){
                           echo "<div class='alert alert-danger'>" . $error . "</div>";
                       }
                    // Check If There's No Error

                    if (empty($formError)){
                        $stmt2 =  $connect->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                        $stmt2->execute(array($user,$id));
                        $count = $stmt2->rowCount();

                        if ($count == 1) {
                            $theMsg = "<div class='alert alert-danger'>Sorry This User Is Exist </div>";
                            redirectHome($theMsg,'back');
                        } else {

                                $stmt = $connect->prepare("SELECT * FROM  users  WHERE  UserID = ? ");
                                $stmt->execute(array($id));
                                $row = $stmt->fetch();

                               if(!empty($row['avatar'])){
                                    $OldImage = $row['avatar'];

                                    if(validate($_FILES['avatar']['name'],1)){
                                        $desPath = 'uploads/avatar/'.$FinalName;

                                        if(move_uploaded_file($tmpPath,$desPath)){
                                            unlink('uploads/avatar/'.$OldImage);
                                        }
                                    }else{
                                        $FinalName = $OldImage;
                                    }
                               } else {
                                $FinalName = rand().time() . '_' . $imageName;
                                move_uploaded_file($tmpPath,"./uploads/avatar/" . $FinalName);
                               }

                                // Update The Database With This Info
                                $stmt = $connect->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?,phone = ?, Password = ?, avatar = ? WHERE UserID = ?");
                                $stmt-> execute(array($user,$email,$name,$phone,$pass,$FinalName,$id));

                                $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Update</strong> </div>";
                                redirectHome($theMsg,'back');
                          }

                        }
                  }  else {
                    $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Direct </div>";
                        
                         redirectHome($theMsg,'',5);
                     }
                     echo "</div>";

                 } elseif ($do == 'Delete') {   // Delete Page

                    echo "<h1 class='text-center'>Delete Member</h1>";
                    echo "<div class='container'>";
                    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']): 0; 

                    //$check = checkItem('userid', 'users',$userid );
                    
                     $stmt = $connect->prepare("SELECT * FROM  users  WHERE  UserID = ? LIMIT 1");
                     $stmt->execute(array($userid ));
                     $row = $stmt->fetch();
                     $count = $stmt->rowCount();

                    if ($count > 0){

                        $stmt = $connect->prepare("DELETE FROM  users  WHERE  UserID = ?");
                        $stmt->execute(array($userid ));
                        if(!empty($row['avatar'])) {
                            unlink('uploads/avatar/'.$row['avatar']);
                        }
                        $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Deleted</strong> </div>";
                        redirectHome($theMsg,'back');
                    } else {
                        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
                        redirectHome($theMsg,'',5);
                    }
                    echo "</div>";

                 } elseif ($do == 'Activate'){

                    echo "<h1 class='text-center'>Activate Member</h1>";
                    echo "<div class='container'>";
                    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']): 0; 

                    $check = checkItem('userid', 'users',$userid );
                    
                    if ($check > 0){ 

                        $stmt = $connect->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                        $stmt->execute(array($userid ));
                        $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Updated</strong> </div>";
                        redirectHome($theMsg);
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
ob_end_flush();
?>
<script>
    // Comfirm Delete

    $(".confirm").click(function(){
        return confirm('Do You Want To Delete This Member?');
    });
</script>