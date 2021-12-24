<?php 
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login';

    if (isset($_SESSION['Username'])){
        header('Location: dashboard.php');
    }

    include "ini.php";
   

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        // Check If The User Exist In Database

        $stmt = $connect->prepare(" SELECT
                                        UserID ,Username, Password
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ?
                                    AND
                                         GroupID = 1
                                    LIMIT 1");

        $stmt->execute(array($username,$hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) {
            $_SESSION['Username'] = $username;
            $_SESSION['ID'] = $row['UserID'] ;
            header('Location: dashboard.php');
            exit();
        } 
    }

?>

    
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="login">
        
        <h3 class="text-center">Admin Login</h3>

        <div class="form-floating mb-3">
            <input type="text" class="form-control col-sm-10 userName" id="floatingInput" name="user" placeholder="name@example.com" autocomplete="off">
            <label for="floatingInput">User Name</label>
        </div>
        
        <div class="form-floating">
            <input type="password" class="password form-control col-sm-10 pwd" id="floatingPassword" name="pass" placeholder="Password" autocomplete="new-password">
            <label for="floatingPassword">Password</label>
            <i class="show-pass fas fa-eye"></i>
        </div>
        
        <input type="submit" class="btn btn-primary btn-lg w-100 m-auto col-sm-10" value="Login">
    </form>

           
      
    
<?php include $tpl ."footer.php";?>