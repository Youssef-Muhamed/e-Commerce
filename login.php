<?php  
    session_start();
    $pageTitle = 'Login';
    if (isset($_SESSION['user'])){
        
        header('Location: index.php');
    }
    include "ini.php";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        if (isset($_POST['login'])){

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);

            // Check If The User Exist In Database

            $stmt = $connect->prepare(" SELECT
                                           *
                                        FROM 
                                            users 
                                        WHERE 
                                            Username = ? 
                                        AND 
                                            Password = ?
                                        And 
                                           RegStatus = 1
                                            ");

            $stmt->execute(array($user,$hashedPass));
            $get = $stmt->fetch(); 
            $count = $stmt->rowCount();

            if ($count > 0) {
                $_SESSION['user'] = $user;
                $_SESSION['uid'] = $get['UserID'];
                $_SESSION['uimage'] = $get['avatar'];
                header('Location: index.php');
                exit();
            } else {
                echo '<div class="alert alert-danger">In Valid Email OR Waiting Aprove Your Account</div>';
            }
        } else {
            $formErrors = array();
            // Upload Vars
            $avatarName     = $_FILES['avatar']['name'];
            $avatarSize     = $_FILES['avatar']['size'];
            $avatarTmp      = $_FILES['avatar']['tmp_name'];
            $avatarType     = $_FILES['avatar']['type'];

            $avatarAllowExtention = array("jpeg","jpg","png","gif");
            
            $tmp = explode('.',$avatarName);
            $avatarExtention = strtolower(end($tmp));

            $username   = $_POST['username'];
            $password   = $_POST['password'];
            $password2  = $_POST['password2'];
            $email      = $_POST['email'];
            $full       = $_POST['full'];
            $phone      = $_POST['phone'];

            if(isset($username)){
                $filterdUser = filter_var($username,FILTER_SANITIZE_STRING);
                if(strlen($filterdUser) < 4){
                    $formErrors[] = "User Name Must Be More Than 4 Characters";
                }
            }

            if(isset($full)){
                $filterdfull = filter_var($full,FILTER_SANITIZE_STRING);
                if(strlen($full) <= 3){
                    $formErrors[] = "full Name Must Be More Than 4 Characters";
                }
            }

            if(isset($password) && isset($password2)){
                if(empty($password)){
                    $formErrors[] = "Password Can't Be Empty";
                }

                if(sha1($password) !== sha1($password2) ){
                    $formErrors[] = "Password Is Not Match";
                } 
            }

            if(isset($email)){
                $filterdEmail = filter_var($email,FILTER_SANITIZE_EMAIL);
                if(filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){
                    $formErrors[] = "This Emai Is Not Valid";
                }
            }
             # Validate phone
             if (!validate($phone, 1)) {
                $formError[] = " Phone Can't Be <strong>Empty</strong> ";
            } elseif (!validate($phone, 6)) {
                $formError[] = 'Invalid Phone Number';
            }
            if(isset($avatarName)){
                if ( !empty($avatarName) && ! in_array($avatarExtention,$avatarAllowExtention)){
                    $formError[] = " The Extention Of Image Is Not <strong>Allowed</strong> ";
                }
            }

            if (empty($formError)){
                $avatar = rand().time() . '_' . $avatarName;
                move_uploaded_file($avatarTmp,"./admin/uploads/avatar/" . $avatar);

                // Check if user exist in database
                
                $check =  checkItem("Username","users",$username);
                if ($check == 1){
                    $formErrors[] = "Sorry This User Is Exists";
                    } else {
                    // Insert User Info The Database 
                    $stmt = $connect->prepare("INSERT INTO users(Username, Password, Email,FullName,phone,RegStatus,Date,avatar) 
                                                VALUES(:user,:pass,:mail,:full,:phone,0,now(),:avatar)");
                    $stmt->execute(array(
                        'user'   => $username,
                        'pass'   => sha1($password),
                        'mail'   => $email,
                        'full'   => $full,
                        'phone'  => $phone,
                        'avatar' => $avatar
                    ));
                        $succesMsg = "<div class='alert alert-success'>Congrats! You Are Now Registerd And waiting Approve </div>";
                }      
            }
        }
    }


?>
<div class="container login-page">
<h1 class="text-center"><span class="selected" data-class="login ">Login</span> | <span data-class="signup">Sign UP</span></h1>   

                <!--############################# start login form ############################# -->

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="login">

        <div class="form-floating mb-3">
            <input type="text" class="form-control col-sm-10 userName" id="floatingInput" name="username" placeholder=" " required="required" autocomplete="off">
            <label for="floatingInput">User Name</label>
        </div>
        
        <div class="form-floating">
            <input type="password" class="password form-control col-sm-10 pwd" id="floatingPassword" name="password" required="required" placeholder=" " autocomplete="new-password">
            <label for="floatingPassword">Password</label>
            <i class="show-pass fas fa-eye"></i>
        </div>
        
        <input type="submit" name="login" class="btn btn-primary btn-lg w-100 m-auto col-sm-10" value="Login">
    </form> 

                <!-- ############################# start signup form  #############################-->

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="signup" enctype="multipart/form-data">

        <div class="form-floating mb-3">
            <input type="text" class="form-control col-sm-10 userName" id="floatingInput" name="username"
            placeholder=" " pattern=".{3,}" title="User Name Must Be 4 Char" required="required" autocomplete="off">
            <label for="floatingInput">User Name</label>
        </div>
        
        <div class="form-floating">
            <input type="password" class="password form-control col-sm-10 pwd" id="floatingPassword" name="password" 
            placeholder=" " minlength="4" required="required" autocomplete="new-password">
            <label for="floatingPassword">Password</label>
        </div>
                
        <div class="form-floating">
            <input type="password" class="password form-control col-sm-10 pwd" id="floatingPassword" name="password2" 
            placeholder=" " minlength="4" required="required" autocomplete="new-password">
            <label for="floatingPassword">Password</label>
        </div>

        <div class="form-floating">
            <input type="email" class="form-control col-sm-10 eamil" id="floatingPassword" name="email" required="required" placeholder=" " >
            <label for="floatingPassword">Email</label>
        </div>
        
        <div class="form-floating">
            <input type="text" class="form-control col-sm-10 eamil" id="floatingPassword" name="full" required="required" placeholder=" " >
            <label for="floatingPassword">Full Name</label>
        </div>
        <div class="form-floating">
            <input type="number" class="form-control col-sm-10 eamil" id="floatingPassword" name="phone" required="required" placeholder=" " >
            <label for="floatingPassword">Phone</label>
        </div>
        Your Image:
        <div class="form-floating">
            <input type="file" name="avatar" class="form-control col-sm-10 eamil"   placeholder=" "  required="required" >
        </div>
        <input type="submit" name="signup" class="btn btn-success btn-lg w-100 m-auto col-sm-10" value="SignUp">
    </form>
    <div class="the-erroes  text-center p-2 my-2">
        <?php  
            if (!empty($formErrors)){
                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }
            }
            if(isset($succesMsg)){
                echo $succesMsg;
            }

        ?>
    </div>
</div>
<?php include $tpl ."footer.php";?>