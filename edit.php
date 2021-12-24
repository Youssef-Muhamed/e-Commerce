<?php

session_start();
    $pageTitle = 'Edit';
    include "ini.php"; 
    if(isset($_SESSION['user'])){
        $do = isset($_GET['do']) ? $_GET['do'] : '';
        if ($do == 'Edit'){

               $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']): 0;
               $stmt = $connect->prepare("SELECT * FROM  users  WHERE  UserID = ? LIMIT 1");
               $stmt->execute(array($userid ));
               $row = $stmt->fetch();
               $count = $stmt->rowCount();
       
               if ($count > 0){  ?>


<h1 class='text-center'>Edit Profile</h1>
<div class="container for">
    <form action="?do=Update" method="post" class='form-horizontal mx-auto' enctype="multipart/form-data">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <fieldset class="form-floating row my-3 mx-auto col-sm-8">
            <input type="text" name="username" class="form-control" required="required"
                value="<?php echo $row['Username'] ?>" placeholder=" " autocomplete='off'>
            <label class="form-label fw-bold">Username</label>
        </fieldset>


        <fieldset class="form-floating row my-3 mx-auto col-sm-8">
            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
            <input type="password" name="newpassword" class="form-control" autocomplete='new-password' placeholder=" ">
            <label class="form-label fw-bold">Password</label>
        </fieldset>


        <fieldset class="form-floating row my-3 mx-auto col-sm-8">
            <input type="email" name="email" class="form-control" required="required"
                value="<?php echo $row['Email'] ?>" placeholder=" ">
            <label class="form-label fw-bold">Email</label>
        </fieldset>


        <fieldset class="form-floating row my-3 mx-auto  col-sm-8">
            <input type="text" name="full" class="form-control" required="required"
                value="<?php echo $row['FullName'] ?>" placeholder=" ">
            <label class="form-label fw-bold">Full Name</label>
        </fieldset>

        <fieldset class="form-floating row my-3 mx-auto  col-sm-8">
            <input type="number" name="phone" class="form-control" required="required"
                value="<?php echo $row['phone'] ?>" placeholder=" ">
            <label class="form-label fw-bold">Phone</label>
        </fieldset>

        <fieldset class="mx-auto my-3 col-md-8">
            <label class="form-label fw-bold col-md-3"> Image</label>
            <input type="file" name="avatar" class="form-control" placeholder=" ">
        </fieldset>
        <fieldset class="mx-auto my-3 col-md-8">
            <?php
                        if (empty($row['avatar'])){
                            echo " <img src='./admin/uploads/avatar/download.png' alt='Image' width='80px'>";
                        }else {echo " <img src='./admin/uploads/avatar/" . $row['avatar'] . "' alt='Image'width='80px'>";} ?>

        </fieldset>


        <fieldset class="row justify-content-start mx-auto col-sm-8 my-4">
            <input type="submit" class="btn btn-primary col-3 " value="Save">
        </fieldset>
    </form>
</div>


<?php
               } else {

                echo '<div class="alert alert-danger my-5"> thers No Such ID </div>';
               }
        }elseif ($do == 'Update') {  // Update

            echo "<h1 class='text-center'>Update Profile</h1>";
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
                  # Validate phone
                if (!validate($phone, 1)) {
                    $formError[] = " Phone Can't Be <strong>Empty</strong> ";
                } elseif (!validate($phone, 6)) {
                    $formError[] = 'Invalid Phone Number';
                }

                if(validate($_FILES['avatar']['name'],1)){

                    $tmpPath    =  $_FILES['avatar']['tmp_name'];
                    $imageName  =  $_FILES['avatar']['name'];
                    $imageSize  =  $_FILES['avatar']['size'];
                    $imageType  =  $_FILES['avatar']['type'];

                    $exArray   = explode('.',$imageName);
                    $extension = end($exArray);

                    $FinalName = rand().time().'.'.$extension;

                   // $allowedExtension = ["jpeg","jpg","png","gif"];

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

            }
    } else {
        header('Location: index.php');
        exit();
    }