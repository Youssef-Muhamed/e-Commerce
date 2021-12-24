<?php
ob_start();
session_start();
$pageTitle = 'Online Shop';
include "ini.php"; ?>
   
<div class="container">
    <div class="row">
        <?php 
        $allItems = getAllFrom('*','items','WHERE Approve = 1','','item_ID');
            foreach($allItems as $items) {
                echo '<div class="col-sm-6 col-md-3">';
                     echo '<div class="card my-1 item-box"> ';
                     echo '<p class="card-text price-tag"><span class="p-2 my-3">' . $items['Price'] . '</span></p>';
                       if(empty($items['avatar'])){
                        echo '<img src="./admin/uploads/avatar/download.png" alt="" title="" class="card-img-top  " width="400px" height="200px" >';
                       } else {
                        echo '<img src="./admin/uploads/avatar/'.$items['avatar'].'" alt="" title="" class="card-img-top " width="400px" height="200px">';
                       }
                         echo '<div class="card-body">';
                            echo '<h5 class="card-title"><a href="items.php?itemid='. $items['item_ID'] .'">'. $items['Name'] .'</a></h5>';
                            echo ' <p class="card-text">'. $items['Description'] .'</p>';
                            echo ' <div class="date">'. $items['Add_Date'] .'</div>';
                            echo ' <a class="btn btn-outline-secondary" href="items.php?itemid='. $items['item_ID'] .'">View</a>';
                         echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        ?>
    </div>
</div>
   
<?php include $tpl ."footer.php";
ob_end_flush();
?>