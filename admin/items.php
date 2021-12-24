<?php
/*
================================================
== Items Page
== You can Add | Edit | Delete Members from here
================================================
*/

session_start();
    $pageTitle = 'Items';
if (isset($_SESSION['Username'])){
    include 'ini.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {  // Manage Members Page
        
        $stmt = $connect->prepare(" SELECT items.*,categories.Name AS cat_name,users.Username FROM items
        INNER JOIN categories ON items.Cat_ID = categories.ID
        INNER JOIN users ON items.Member_ID = users.UserID
        ORDER BY item_ID DESC");
        $stmt->execute();
        $items = $stmt->fetchAll();
        if(! empty($items)){
    ?>
    <div class="container">
        <h1 class='text-center'>Manage Items</h1>
           
        <div class="table-responsive">
                <table class="table text-center main-table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#ID</td>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Addinng Date</th>
                            <th>Category</th>
                            <th>User Name</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <?php 
                        foreach ($items as $item) {
                            echo "<tbody>";
                                echo "<tr>";
                                    echo "<td>" . $item['item_ID'] . "</td>";
                                    echo "<td>";
                                    if (empty($item['avatar'])){
                                        echo " <img src='uploads/avatar/download.png' alt='Image' width='80'>";
                                    }else {echo " <img src='uploads/avatar/" . $item['avatar'] . "' alt='Image' width='80'>";}

                                    echo "</td>";
                                    echo "<td>" . $item['Name'] . "</td>";
                                    echo "<td>" . $item['Description'] . "</td>";
                                    echo "<td>" . $item['Price'] . "</td>";
                                    echo "<td>" . $item['Add_Date'] ."</td>";
                                    echo "<td>" . $item['cat_name'] ."</td>";
                                    echo "<td>" . $item['Username'] ."</td>";
                                    echo "<td>
                                            <a href='items.php?do=Edit&itemid=". $item['item_ID'] ." ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='items.php?do=Delete&itemid=". $item['item_ID'] ." ' class='btn btn-danger confirm'>Delete</a> "; 
                                            if ($item['Approve'] == 0){
                                                echo " <a href='items.php?do=Approve&itemid=". $item['item_ID'] ." ' class='btn btn-info text-white'><i class='fa fa-check'></i> Approve</a>";
                                             }
                                    echo "</td>";
                                echo "</tr>";
                            echo "</tbody>";
                        }
                    ?>
              </table>
              <a href="items.php?do=Add" class="btn btn-primary my-3"><i class="fas fa-plus"></i> New Item</a>
            </div>
        </div>
            <?php } else {
                echo "<div class='container'>";
                    echo "<div class='alert alert-info my-5'>There's No Record Show</div>";
                    echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> New Item</a>';
                echo "</div>";
                
            } ?>
       <?php
        } elseif ($do == 'Add') { //  Add Page ?>
            

            <h1 class='text-center'>Add New Item</h1>
            <div class="container for">
                <form action="?do=Insert" method="post" class='form-horizontal mx-auto my-5' enctype="multipart/form-data">
                
                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="name" class="form-control" required = "required" placeholder=" " >
                        <label class="form-label fw-bold">Name</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">

                        <textarea name="description" class="form-control" required = "required" placeholder=" "></textarea>
                        <label class="form-label fw-bold">Description</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="price" class="form-control" required = "required" placeholder=" " >
                        <label class="form-label fw-bold">Price</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="country" class="form-control" required = "required" placeholder=" " >
                        <label class="form-label fw-bold">country Made</label>
                    </fieldset>

                    <fieldset class="form-floating  row my-3 mx-auto col-sm-8">
                        <select class="form-select " name="status" placeholder=" " >
                            <option value='0'></option>
                            <option value='1'>New</option>
                            <option value='2'>Like New</option>
                            <option value='3'>Used</option>
                            <option value='4'>Very Old</option>

                        </select> 
                        <label class="form-label fw-bold">Status</label>
                    </fieldset>

                    <fieldset class="form-floating  row my-3 mx-auto col-sm-8">
                        <select class="form-select " name="member" placeholder=" " >
                            <option value='0'></option>                  
                            <?php
                                $allMembers = getAllFrom("*", "users", "","","UserID");
                                foreach($allMembers as $user){
                                    echo "<option value='". $user['UserID'] ."'>". $user['Username'] ."</option>";
                                }
                            ?>
                        </select> 
                        <label class="form-label fw-bold">Member</label>
                    </fieldset>

                    <fieldset class="form-floating  row my-3 mx-auto col-sm-8">
                        <select class="form-select " name="category" placeholder=" " >

                            <option value='0' ></option>
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

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="tags" class="form-control" placeholder=" " >
                        <label class="form-label fw-bold">Tags</label>
                    </fieldset>
                    <fieldset class="mx-auto my-3 col-md-8">
                        <label class="form-label fw-bold col-md-3">User Image</label>
                        <input type="file" name="avatar" class="form-control"  placeholder=" " >
                    </fieldset>
                    <fieldset class="row justify-content-start mx-auto col-sm-8 my-4">
                            <input type="submit" class="btn btn-primary col-3 " value="Add Item">       
                    </fieldset>
                </form>
            </div> 
          
            <?php } elseif ($do == 'Insert'){

                        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                            echo "<h1 class='text-center'>Insert Item</h1>";
                            echo "<div class='container'>";

                            // Upload Image
                            $avatarName     = $_FILES['avatar']['name'];
                            $avatarSize     = $_FILES['avatar']['size'];
                            $avatarTmp      = $_FILES['avatar']['tmp_name'];
                            $avatarType     = $_FILES['avatar']['type'];

                            $avatarAllowExtention = array("jpeg","jpg","png","gif");

                            $tmp = explode('.',$avatarName);
                            $avatarExtention = strtolower(end($tmp));

                            $name     = $_POST['name'];
                            $desc     = $_POST['description'];
                            $price    = '$'.$_POST['price'];
                            $country  = $_POST['country'];
                            $status   = $_POST['status'];
                            $member   = $_POST['member'];
                            $cat      = $_POST['category'];
                            $tags     = $_POST['tags'];
                            
                            

                        // Validate The Form
                        $formError = array();
                        if (empty($name) ){
                            $formError[] = " Name  Can't Be <strong>Empty</strong> .";
                        }
                        if (empty($desc)){
                            $formError[] = "Description Can't Be <strong>Empty</strong> . ";
                        }
                        if (empty($price)){
                            $formError[] = " Price Can't Be <strong>Empty</strong> ";
                        }
                        if (empty($country)){
                            $formError[] = " Country Can't Be <strong>Empty</strong> ";
                        }
                        if ($status == 0){
                            $formError[] = " You Must choose the <strong>status</strong> ";
                        } 
                        if ($member == 0){
                            $formError[] = " You Must choose the <strong>Member</strong> ";
                        } 
                        if ($cat == 0){
                            $formError[] = " You Must choose the <strong>Category</strong> ";
                        }
                        if ( !empty($avatarName) && ! in_array($avatarExtention,$avatarAllowExtention)){
                            $formError[] = " The Extention Of Image Is Not <strong>Allowed</strong> ";
                        }
                        foreach($formError as $error){
                            echo "<div class='alert alert-danger'>" . $error . "</div>";
                        }

                        // Check If There's No Error

                        if (empty($formError)){
                            $avatar = rand().time() . '_' . $avatarName;
                            move_uploaded_file($avatarTmp,"./uploads/avatar/" . $avatar);
                                // Insert User Info The Database 
                                $stmt = $connect->prepare("INSERT INTO items(Name, Description, Price, Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags,avatar)
                                 VALUES(:name,:desc,:price,:country,:status,now(),:cat,:member,:tags,:avatar)");
                                $stmt->execute(array(
                                    'name'      => $name,
                                    'desc'      => $desc,
                                    'price'     => $price,
                                    'country'   => $country,
                                    'status'    => $status,
                                    'cat'       => $cat,
                                    'member'    => $member,
                                    'tags'      => $tags,
                                    'avatar'    => $avatar
                                    
                                ));
                                    $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Inserted</strong> </div>";
                                    redirectHome($theMsg);  
                            }

                } else {
                            echo "<div class='container'>";
                            $theMsg = '<div class="alert alert-danger my-5"> Sorry You Can\'t Browse This Page Direct </div>';
                            redirectHome($theMsg,'',5);
                            echo '</div>';
                        }
                        echo "</div>";

            } elseif($do == 'Edit') {  //  Edit Page
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']): 0;     
           
                $stmt = $connect->prepare("SELECT * FROM  items  WHERE  item_ID = ? ");
                $stmt->execute(array($itemid));
                $item = $stmt->fetch();
                $count = $stmt->rowCount();    
                
                if ($count > 0){ ?>

            <h1 class='text-center'>Edit Item</h1>
            <div class="container for">
                <form action="?do=Update" method="post" class='form-horizontal mx-auto my-5'  enctype="multipart/form-data">
                <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="name" class="form-control" required = "required" placeholder=" " value="<?php echo $item['Name']; ?>" >
                        <label class="form-label fw-bold">Name</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="description" class="form-control" required = "required" placeholder=" " value="<?php echo $item['Description']; ?>" >
                        <label class="form-label fw-bold">Description</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="price" class="form-control" required = "required" placeholder=" " value="<?php echo $item['Price']; ?>" >
                        <label class="form-label fw-bold">Price</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="country" class="form-control" required = "required" placeholder=" " value="<?php echo $item['Country_Made']; ?>" >
                        <label class="form-label fw-bold">country Made</label>
                    </fieldset>

                    <fieldset class="form-floating  row my-3 mx-auto col-sm-8">
                        <select class="form-select " name="status" placeholder=" " >
                            
                            <option value='1' <?php if($item['Status'] == 1) {echo 'selected';}?>>New</option>
                            <option value='2' <?php if($item['Status'] == 2) {echo 'selected';}?>>Like New</option>
                            <option value='3' <?php if($item['Status'] == 3) {echo 'selected';}?>>Used</option>
                            <option value='4' <?php if($item['Status'] == 4) {echo 'selected';}?>>Very Old</option>

                        </select> 
                        <label class="form-label fw-bold">Status</label>
                    </fieldset>

                    <fieldset class="form-floating  row my-3 mx-auto col-sm-8">
                        <select class="form-select " name="member" placeholder=" " >
                                              
                            <?php
                                $stmt = $connect->prepare("SELECT * FROM users");
                                $stmt-> execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                    echo "<option value='". $user['UserID'] ."'";
                                    if($item['Member_ID'] == $user['UserID']) {echo 'selected';}
                                    echo ">" . $user['Username'] ."</option>";
                                }
                            ?>
                        </select> 
                        <label class="form-label fw-bold">Member</label>
                    </fieldset>

                    <fieldset class="form-floating  row my-3 mx-auto col-sm-8">
                        <select class="form-select " name="category" placeholder=" " >
                            <option value='0'></option>                  
                            <?php
                                $stmt2 = $connect->prepare("SELECT * FROM categories");
                                $stmt2-> execute();
                                $cats = $stmt2->fetchAll();
                                foreach($cats as $cat){
                                    echo "<option value='". $cat['ID'] ."'";
                                    if($item['Cat_ID'] == $cat['ID']) {echo 'selected';}
                                    echo ">". $cat['Name'] ."</option>";
                                }
                            ?>
                        </select> 
                        <label class="form-label fw-bold">Category</label>
                    </fieldset>
                    
                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="tags" class="form-control" placeholder=" " value="<?php echo $item['tags']; ?>" >
                        <label class="form-label fw-bold">Tags</label>
                    </fieldset>
                    <fieldset class="mx-auto my-3 col-md-8">
                        <label class="form-label fw-bold col-md-3"> Image</label>
                        <input type="file" name="avatar" class="form-control" placeholder=" " >
                    </fieldset>


                    <fieldset class="mx-auto my-3 col-md-8">
                        <?php
                    if (empty($item['avatar'])){
                                        echo " <img src='uploads/avatar/download.png' alt='Image' width='80px'>";
                                    }else {echo " <img src='uploads/avatar/" . $item['avatar'] . "' alt='Image'width='80px'>";} ?>
                    </fieldset>
                    <fieldset class="row justify-content-start mx-auto col-sm-8 my-4">
                            <input type="submit" class="btn btn-primary col-3 " value="Save">       
                    </fieldset>
                </form>
                                <?php
                        $stmt = $connect->prepare(" SELECT comments.*,users.Username FROM comments
                                            INNER JOIN users ON comments.user_id = users.UserID
                                            WHERE item_id = ? ");
                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();
               if (!empty($rows)){
            ?>
          
                <h1 class='text-center'>Manage [ <?php echo $item['Name']; ?> ] Comments</h1>
                
                <div class="table-responsive">
                            <table class="table text-center main-table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Comment</th>
                                        <th>User Name</th>
                                        <th>Added Date</th>
                                        <th>Control</th>
                                    </tr>
                                </thead>
                                <?php 
                                    foreach ($rows as $row) {
                                        echo "<tbody>";
                                            echo "<tr>";
                                                echo "<td>" . $row['comment'] . "</td>";
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
                           <?php }?>
                </div>
               
            
          
    <?php 
                } else {
                    echo '<div class="container">';
                    $theMsg = '<div class="alert alert-danger my-5">There\'s No Such ID </div>';
                    redirectHome($theMsg,'',5);
                    echo '</div>';
                }
             } elseif ($do == 'Update') { // Update Page

              echo "<h1 class='text-center'>Update Item</h1>";
              echo "<div class='container'>";
              if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                $id       = $_POST['itemid'];
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $price    = $_POST['price'];
                $country  = $_POST['country'];
                $status   = $_POST['status'];
                $member   = $_POST['member'];
                $cat      = $_POST['category'];
                $tags     = $_POST['tags'];
                        
                        // Validate The Form
                        $formError = array();
                        if (empty($name) ){
                            $formError[] = " Name  Can't Be <strong>Empty</strong> .";
                        }
                        if (empty($desc)){
                            $formError[] = "Description Can't Be <strong>Empty</strong> . ";
                        }
                        if (empty($price)){
                            $formError[] = " Price Can't Be <strong>Empty</strong> ";
                        }
                        if (empty($country)){
                            $formError[] = " Country Can't Be <strong>Empty</strong> ";
                        }
                        if ($status == 0){
                            $formError[] = " You Must choose the <strong>status</strong> ";
                        } 
                        if ($member == 0){
                            $formError[] = " You Must choose the <strong>Member</strong> ";
                        } 
                        if ($cat == 0){
                            $formError[] = " You Must choose the <strong>Category</strong> ";
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
                    $stmt = $connect->prepare("SELECT * FROM  items  WHERE  item_ID = ? ");
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
                    $stmt = $connect->prepare("UPDATE items 
                                        SET Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ?, Member_ID = ? ,Cat_ID = ?, tags = ?, avatar = ?
                                         WHERE item_ID = ?");
                    $stmt-> execute(array($name,$desc,$price,$country,$status,$member,$cat,$tags,$FinalName,$id));

                    $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Update</strong> </div>";
                    redirectHome($theMsg);
                }
               

              }  else {
                $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Direct </div>";
                    
                     redirectHome($theMsg,'',5);
                 }
                 echo "</div>";

            } elseif ($do == 'Delete') {   // Delete Page
                
                echo "<h1 class='text-center'>Delete Item</h1>";
                echo "<div class='container'>";
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']): 0; 

               // $check = checkItem('item_ID', 'items',$itemid );
                $stmt = $connect->prepare("SELECT * FROM  items  WHERE  item_ID = ? LIMIT 1");
                $stmt->execute(array($itemid ));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count > 0){

                    $stmt = $connect->prepare("DELETE  FROM  items  WHERE  item_ID = ?");
                    $stmt->execute(array($itemid));
                    if(!empty($row['avatar'])){
                        unlink('uploads/avatar/'.$row['avatar']);
                    }
                    $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Deleted</strong> </div>";
                    redirectHome($theMsg,'back',5);
                } else {
                    $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
                    redirectHome($theMsg,'');
                }
                echo "</div>";

            } elseif ($do == 'Approve'){
                
                echo "<h1 class='text-center'>Approve Item</h1>";
                echo "<div class='container'>";
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']): 0; 

                $check = checkItem('item_ID', 'items',$itemid );
                
                if ($check > 0){ 

                    $stmt = $connect->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
                    $stmt->execute(array($itemid ));
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
        return confirm('Do You Want To Delete This Item?');
    });
</script>