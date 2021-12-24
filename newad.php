<?php

    session_start();
    $pageTitle = 'Create New Item';
    include "ini.php"; 
    if(isset($_SESSION['user'])){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();
                            
            $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price      = '$'.filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            if(empty($name)){
                $formErrors[] = "Item title Must Be Not Empty";
            }

            if(empty($desc)){
                $formErrors[] = "Item Description Must Be Not Empty";
            }

            if(empty($price)){
                $formErrors[] = "Item Price Must Be Not Empty";
            }

            if(empty($country)){
                $formErrors[] = "Item Country Must Be Not Empty";
            }

            if(empty($status)){
                $formErrors[] = "Item status Must Be Not Empty";
            }

            if(empty($category)){
                $formErrors[] = "Item category Must Be Not Empty";
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
            }

            

            if (empty($formError)){

                
                if (empty($_FILES['avatar']['name'])) {
                    $avatar = '';
                } else {
                    $avatar = rand().time() . '_' . $avatarName;
                    move_uploaded_file($avatarTmp,"./admin/uploads/avatar/" . $avatar);
                }
                

                // Insert User Info The Database 
                $stmt = $connect->prepare("INSERT INTO items(Name, Description, Price, Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags,avatar)
                                            VALUES(:name,:desc,:price,:country,:status,now(),:cat,:member,:tags,:avatar)");
                $stmt->execute(array(
                    'name'      => $name,
                    'desc'      => $desc,
                    'price'     => $price,
                    'country'   => $country,
                    'status'    => $status,
                    'cat'       => $category,
                    'member'    => $_SESSION['uid'],
                    'tags'      => $tags,
                    'avatar'    => $avatar
                ));
                if($stmt){
                    $succesMsg = "<div class='alert alert-success text-center my-2'> Item Added </div>";
                }
            }
        }
    ?>
    <h1 class="text-center"><?php echo $pageTitle ?></h1>
   <div class="create-ad block for">
       <div class="container">
            <div class="card  shadow-sm">
                <div class="card-header bg-primary text-light">
                    <h5 class="card-title"><?php echo $pageTitle ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 ">
                             <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class='form-horizontal p-2'  enctype="multipart/form-data">
                                <fieldset class="form-floating row  col-sm-10">
                                    <input type="text" name="name" class="form-control live" 
                                    required = "required" placeholder=" " data-class=".live-title">
                                    <label class="form-label fw-bold">Name</label>
                                </fieldset>

                                <fieldset class="form-floating row my-3 col-sm-10">
                                    <input type="text" name="description" class="form-control live" 
                                    required = "required" placeholder=" " data-class=".live-desc">
                                    <label class="form-label fw-bold">Description</label>
                                </fieldset>

                                <fieldset class="form-floating row my-3 col-sm-10">
                                    <input type="number" name="price" class="form-control live" 
                                    required = "required" placeholder=" "  data-class=".live-price">
                                    <label class="form-label fw-bold">Price</label>
                                </fieldset>

                                <fieldset class="form-floating row my-3 col-sm-10">
                                    <input type="text" name="country" class="form-control" required = "required" placeholder=" " >
                                    <label class="form-label fw-bold">country Made</label>
                                </fieldset>

                                <fieldset class="form-floating  row my-3 col-sm-10">
                                    <select class="form-select" required = "required" name="status" placeholder=" " >
                                        <option value=''></option>
                                        <option value='1'>New</option>
                                        <option value='2'>Like New</option>
                                        <option value='3'>Used</option>
                                        <option value='4'>Very Old</option>

                                    </select> 
                                    <label class="form-label fw-bold">Status</label>
                                </fieldset>

                                <fieldset class="form-floating  row my-3 col-sm-10">
                                    <select class="form-select " required = "required" name="category" placeholder=" " >
                                        <option value=''></option>                  
                                        <?php
                                        $allCats = getAllFrom("*", "categories", "WHERE parent = 0","","ID");
                                        foreach($allCats as $cat){
                                            echo "<option  value='". $cat['ID'] ."' disabled> ". $cat['Name'] ."</option>";

                                            $childCats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}","","ID");
                                            foreach ($childCats as $child) {
                                                echo "<option value='". $child['ID'] ."'>--". $child['Name'] ."</option>";
                                            }
                                        }
                                    ?>
                                    </select> 
                                    <label class="form-label fw-bold">Category</label>
                                </fieldset>

                                <fieldset class="form-floating  row my-3 col-sm-10">
                                    <input type="text" name="tags" class="form-control" placeholder=" " >
                                    <label class="form-label fw-bold">Tags</label>
                                </fieldset>


                                <label class="form-label fw-bold "> Image</label>
                                <fieldset class="form-floating  row my-1 col-sm-10">
                                <input type="file" name="avatar" class="form-control" placeholder=" " >
                                </fieldset>

                 

                                <fieldset class="row justify-content-start col-sm-4 ">
                                        <input type="submit" class="btn btn-primary" value="Add Item">       
                                </fieldset>
                             </form>
                        </div>
                        <div class="col-md-4">
                            <div class="card my-1 item-box live-preview"> 
                                <p class="card-text price-tag">$<span class="live-price">0</span></p>
                                <img src="./admin/uploads/avatar/download.png" alt="" title="" class="card-img-top ">
                                <div class="card-body">
                                    <h5 class="card-title live-title">Title</h5>
                                    <p class="card-text live-desc">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- start loop Errors -->
                       <?php
                            if(!empty($formErrors)){
                                foreach($formErrors as $error){
                                    echo '<div calss="alert alert-danger">' . $error . '</div>';
                                }
                            }
                             if(isset($succesMsg)){
                                echo $succesMsg;
                            }
                            
                       ?>
                    <!-- End loop Errors -->
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
<script>
      // Live New Add
$(function(){
    $('.live').keyup(function(){
         $($(this).data('class')).text($(this).val());
    });
});  
</script>