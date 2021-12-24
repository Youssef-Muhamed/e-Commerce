<?php
session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['Username'])){
include 'ini.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {  // Manage Members Page

        $sort = 'ASC';
        $sort_arrar = array("ASC","DESC");
        $sort = isset($_GET['sort']) && in_array($_GET['sort'],$sort_arrar) ?  $_GET['sort'] : 'ASC';
        $stmt2 = $connect-> prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort ");
        $stmt2->execute();
        $cats = $stmt2->fetchAll(); 
        if(! empty($cats)){
        ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
        <div class="card shadow-sm">
                 <div class="card-header">
                    <i class="fa fa-edit"></i>  Manage Categories
                      <div class="ordering">
                        <i class="fa fa-sort"></i>  Ordering: [
                          <a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a>  |
                          <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a> ]
                      </div>
                 </div>
                    <div class="card-body">
                        <?php
                            foreach($cats as $cat){
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='?do=Edit&catid=". $cat['ID'] ." ' class='btn btn-primary'><i class='fa fa-edit'></i> Edit</a> ";
                                        echo " <a href='?do=Delete&catid=". $cat['ID'] ."' class='btn btn-danger confirm'> Delete</a>";
                                    echo "</div>";
                                    echo "<div class='full-view'>";
                                        echo "<h3 class='cat-h'>" .  $cat['Name'] . "</h3>";
                                        echo "<p class='paragraph'>"; if($cat['Description'] == ''){echo 'This category has no description ';}else{echo $cat['Description'];} echo "</p>";
                                        if($cat['Visibility'] == 1) { echo '<span class="vis">Hidden</span>'; } 
                                        if($cat['Allow_Comment'] == 1) { echo '<span class="commenting">Comment Disabled</span>'; } 
                                        if($cat['Allow_Ads'] == 1) { echo '<span class="ads">Ads Disabled</span>'; } 
                                        // Get Child Category
                                        $childCats = getAllFrom("*", "categories", "WHERE parent =  {$cat['ID']} ","","ID","ASC");
                                        if (! empty($childCats)){
                                            echo "<h5 class='child-head'>Child Category</h5>";
                                            echo "<ul class='list-unstyled child-cats'>";
                                                foreach($childCats as $childCat){
                                                    echo "<li class='child-link'><a href='?do=Edit&catid=". $childCat['ID'] ." '>" . $childCat['Name'] . "</a>
                                                    <a href='?do=Delete&catid=". $childCat['ID'] ."' class='show-delete confirm'> Delete</a>
                                                    </li>";
                                                    
                                                }
                                            echo "</ul>";
                                        }
                                        echo "<p class='cat-p'></p>";
                                    echo "</div>";    
                                echo "</div>"; 
                                
                                
                            }
                        ?>
                    </div>
            </div>
            <a href="?do=Add" class="btn btn-primary my-3"><i class="fas fa-plus"></i> New Category</a>
        </div>
        <?php } else {
            echo "<div class='container'>";
            echo "<div class='alert alert-info my-5'>There's No Record Show</div>";
            echo '<a href="?do=Add" class="btn btn-primary ><i class="fas fa-plus"></i> New Category</a>';
        echo "</div>";
        } ?>
        <?php    } elseif ($do == 'Add') { //  Add Page ?>

            <h1 class='text-center'>Add New Category</h1>
            <div class="container for my-5">
                <form action="?do=Insert" method="post" class='form-horizontal mx-auto'>
                
                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="name" class="form-control" required = "required" placeholder=" " autocomplete='off'>
                        <label class="form-label fw-bold">Name</label>
                    </fieldset>
                    

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="description" class="form-control"  placeholder=" ">
                        <label class="form-label fw-bold">Description</label>
                    </fieldset>


                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <input type="text" name="ordering" class="form-control" placeholder=" " >
                        <label class="form-label fw-bold">Ordering</label>
                    </fieldset>

                    <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                        <select class="form-select" name="parent" placeholder=" " >
                                <option value='0'>None</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0","","ID","ASC");
                                    foreach ($allCats as $cat) {
                                        echo '<option value=" ' . $cat['ID']. ' ">'. $cat['Name'] .'</option>';
                                    }
                                ?>
                        </select> 
                        <label class="form-label fw-bold">Parent?</label>
                    </fieldset>

                    <fieldset class="row my-3 mx-auto col-sm-8 fw-bold">
                        <label class="col-sm-3">Visible</label>
                            <div class="form-check form-switch col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="vis-yes" name="visible" class="form-check-input" value="0" checked>
                                    <label for="vis-yes" class="form-check-label" >Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="visible" id="vis-no" class="form-check-input" value="1" >
                                    <label class="form-check-label" for="vis-no">No</label>
                                </div>
                            </div>
                    </fieldset>

                    <fieldset class="row my-3 mx-auto col-sm-8 fw-bold">
                        <label class="col-sm-3">Allow Commenting</label>
                            <div class="form-check form-switch col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="com-yes" name="commenting" class="form-check-input" value="0" checked>
                                    <label for="com-yes" class="form-check-label" >Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="commenting" id="com-no" class="form-check-input" value="1" >
                                    <label class="form-check-label" for="com-no">No</label>
                                </div>
                            </div>
                    </fieldset>

                    <fieldset class="row my-3 mx-auto col-sm-8 fw-bold">
                        <label class="col-sm-3">Allow Ads</label>
                            <div class="form-check form-switch col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="ads-yes" name="ads" class="form-check-input" value="0" checked>
                                    <label for="ads-yes" class="form-check-label" >Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="ads" id="ads-no" class="form-check-input" value="1" >
                                    <label class="form-check-label" for="ads-no">No</label>
                                </div>
                            </div>
                    </fieldset>

                   

                        <fieldset class="row justify-content-start mx-auto col-sm-8">
                                <input type="submit" class="btn btn-primary col-3 " value="Add Category">       
                        </fieldset>
                </form>
            </div> 


           <?php } elseif ($do == 'Insert'){

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                        echo "<h1 class='text-center'>Insert Category</h1>";
                        echo "<div class='container'>";

                        $name      = $_POST['name'];
                        $desc      = $_POST['description'];
                        $order     = $_POST['ordering'];
                        $parent    = $_POST['parent'];
                        $visible   = $_POST['visible'];
                        $comment   = $_POST['commenting'];
                        $ads       = $_POST['ads'];
                   
                    // Check If There's No Error
  

                        // Check if Category exist in database
                       
                        $check =  checkItem("Name","categories",$name);
                        if ($check == 1){
                            $theMsg = "<div class='alert alert-danger'>Sorry This <strong>Category</strong> Is Exist</div>";
                            redirectHome($theMsg,'back',5);
                            } else {
                            // Insert Category Info The Database 
                            $stmt = $connect->prepare("INSERT INTO categories(Name, Description, Ordering, parent,Visibility, Allow_Comment,Allow_Ads)
                                                     VALUES(:name,:desc,:order, :parent,:vis,:comment,:ads)");
                            $stmt->execute(array(
                                'name'      => $name,
                                'desc'      => $desc,
                                'order'     => $order,
                                'parent'    => $parent,
                                'vis'       => $visible,
                                'comment'   => $comment,
                                'ads'       => $ads,
                            ));
                                $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Inserted</strong> </div>";
                                redirectHome($theMsg,'back');
                        }      
                        

                    } else {
                        echo "<div class='container'>";
                        $theMsg = '<div class="alert alert-danger my-5"> Sorry You Can\'t Browse This Page Direct </div>';
                        redirectHome($theMsg,'',5);
                        echo '</div>';
                    }
                    echo "</div>";


            } elseif($do == 'Edit') {  //  Edit Page
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']): 0;     
           
                $stmt = $connect->prepare("SELECT * FROM  categories  WHERE  ID = ? ");
                $stmt->execute(array($catid ));
                $cat = $stmt->fetch();
                $count = $stmt->rowCount();    
                
                if ($count > 0){ ?>

                <h1 class='text-center'>Edit Category</h1>
                <div class="container for">
                    <form action="?do=Update" method="post" class='form-horizontal mx-auto'>
                    <input type="hidden" name="catid" value="<?php echo $catid; ?>">
                        <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                            <input type="text" name="name" class="form-control" required = "required" placeholder=" " value="<?php echo $cat['Name']; ?>">
                            <label class="form-label fw-bold">Name</label>
                        </fieldset>
                        

                        <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                            <input type="text" name="description" class="form-control"  placeholder=" " value="<?php echo $cat['Description']; ?>">
                            <label class="form-label fw-bold">Description</label>
                        </fieldset>


                        <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                            <input type="text" name="ordering" class="form-control" placeholder=" " value="<?php echo $cat['Ordering']; ?>">
                            <label class="form-label fw-bold">Ordering</label>
                        </fieldset>

                        <fieldset class="form-floating row my-3 mx-auto col-sm-8">
                            <select class="form-select" name="parent" placeholder=" " >
                                    <option value='0'>None</option>
                                    <?php
                                        $allCats = getAllFrom("*", "categories", "WHERE parent = 0","","ID","ASC");
                                        foreach ($allCats as $c) {
                                            echo '<option value=" ' . $c['ID']. '"';
                                            if ($cat['parent'] == $c['ID']){ echo 'selected';}
                                            echo '>'. $c['Name'] .'</option>';
                                        }
                                    ?>
                            </select> 
                            <label class="form-label fw-bold">Parent?</label>
                        </fieldset>

                        <fieldset class="row my-3 mx-auto col-sm-8 fw-bold">
                            <label class="col-sm-3">Visible</label>
                                <div class="form-check form-switch col-sm-10 col-md-6">
                                    <div>
                                        <input type="radio" id="vis-yes" name="visible" class="form-check-input" value="0"<?php if($cat['Visibility']==0){echo 'checked';} ?> >
                                        <label for="vis-yes" class="form-check-label" >Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" name="visible" id="vis-no" class="form-check-input" value="1"<?php if($cat['Visibility']==1){echo 'checked';} ?> >
                                        <label class="form-check-label" for="vis-no">No</label>
                                    </div>
                                </div>
                        </fieldset>

                        <fieldset class="row my-3 mx-auto col-sm-8 fw-bold">
                            <label class="col-sm-3">Allow Commenting</label>
                                <div class="form-check form-switch col-sm-10 col-md-6">
                                    <div>
                                        <input type="radio" id="com-yes" name="commenting" class="form-check-input" value="0" <?php if($cat['Allow_Comment']==0){echo 'checked';}?> >
                                        <label for="com-yes" class="form-check-label" >Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" name="commenting" id="com-no" class="form-check-input" value="1"<?php if($cat['Allow_Comment']==1){echo 'checked';}?> >
                                        <label class="form-check-label" for="com-no">No</label>
                                    </div>
                                </div>
                        </fieldset>

                        <fieldset class="row my-3 mx-auto col-sm-8 fw-bold">
                            <label class="col-sm-3">Allow Ads</label>
                                <div class="form-check form-switch col-sm-10 col-md-6">
                                    <div>
                                        <input type="radio" id="ads-yes" name="ads" class="form-check-input" value="0"<?php if($cat['Allow_Ads']==0){echo 'checked';}?> >
                                        <label for="ads-yes" class="form-check-label" >Yes</label>
                                    </div>
                                    <div>
                                        <input type="radio" name="ads" id="ads-no" class="form-check-input" value="1"<?php if($cat['Allow_Ads']==1){echo 'checked';}?> >
                                        <label class="form-check-label" for="ads-no">No</label>
                                    </div>
                                </div>
                        </fieldset>

                    

                        <fieldset class="row justify-content-start mx-auto col-sm-8">
                                <input type="submit" class="btn btn-primary col-3 " value="Save Category">       
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
            } elseif ($do == 'Update') {  // Update Page

                echo "<h1 class='text-center'>Update Category</h1>";
                echo "<div class='container'>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                  $id        = $_POST['catid'];
                  $name      = $_POST['name'];
                  $desc      = $_POST['description'];
                  $order     = $_POST['ordering'];
                  $parent    = $_POST['parent'];
                  $visible   = $_POST['visible'];
                  $comment   = $_POST['commenting'];
                  $ads       = $_POST['ads'];
                    // Update The Database With This Info
                    $stmt = $connect->prepare("UPDATE categories 
                                                SET 
                                                 Name = ?, Description = ?, Ordering = ?, parent = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ?
                                                WHERE
                                                 ID = ?");
                    $stmt-> execute(array($name,$desc,$order,$parent,$visible,$comment,$ads,$id));

                    $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Update</strong> </div>";
                    redirectHome($theMsg,'back',5);
                 

                }  else {
                  $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Direct </div>";
                      
                     redirectHome($theMsg,'',5);
                   }
                   echo "</div>";

            } elseif ($do == 'Delete') {   // Delete Page
                
                echo "<h1 class='text-center'>Delete Category</h1>";
                echo "<div class='container'>";
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']): 0; 

                $check = checkItem('ID', 'categories',$catid );
                
                if ($check > 0){ 

                    $stmt = $connect->prepare("DELETE  FROM  categories  WHERE  ID = ?");
                    $stmt->execute(array($catid ));
                    $theMsg = "<div class='alert alert-success'><strong> " . $stmt->rowCount() . " Record Deleted</strong> </div>";
                    redirectHome($theMsg,'',5);
                } else {
                    $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
                    redirectHome($theMsg,'',5);
                }
                echo "</div>";

            } elseif ($do == 'Activate'){

            
            }


    include $tpl ."footer.php";

    } else {
        header('Location: index.php');
        exit();
    }

    ?>
<script>
$(function(){
    $(".confirm").click(function(){
        return confirm('Do You Want To Delete This Category?');
    });
    // $('cat-h').click(function(){
    //     $(this).next('.full-view').fadeToggle(200);
    // });
   $('.child-link').hover(function (){
        $(this).find('.show-delete').fadeIn(400);
   }, function (){
        $(this).find('.show-delete').fadeOut(400);
   })
});

</script>