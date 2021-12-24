<?php

/*
    === Function to get All from any database table 
*/
function getAllFrom($field, $table, $where = NULL, $and = NULL,$orderfeild = NULL, $ordering = 'DESC'){
    global $connect;
    
    $getAll = $connect->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfeild $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();

    return $all;
}

 
/*
    Title function that echo the page title
    if not has title echo default title
*/

function getTitle() {
    global $pageTitle;

    if(isset($pageTitle)){
        echo $pageTitle;
    } else {
        echo 'Default';
    }
}

/*
    === Home Redirect Function [ this function accept parameters ]  
    === $theMsg = echo the  message [ error | Success | warning ]
    === $url = the link you want to redirect
    === $seondes = secpnde before redirecting
*/

function redirectHome($theMsg,$url = null, $seondes = 3){

    if ($url === null){
        $url = 'index.php';
        $link = 'Home Page';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !==''){
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        } else {
            $url = 'index.php';
            $link = 'Home Page';
        }
    }
    echo  $theMsg ;
    echo "<div class='alert alert-info'>You Will Be Redirected To <strong>$link</strong> After $seondes seondes. </div>";
    header("refresh:$seondes;url=$url");
    exit();
}

/*
    === Check Item In Database
    ===  this function accept parameters
    === $select = The item to select [Example: user, id ..]
    === $from   = The table to select from
    === $value  = The value to select
*/

function checkItem($select, $from, $value) {
    global $connect;
    $statement = $connect->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement-> execute(array($value));
    $count = $statement->rowCount();
    return $count; 
}

/*
    === Count Number Of Items Function
    === $item = The item to count 
    === $table = The table to choose from 
*/

function countItem($item,$table){
    global $connect;

    $stmt2 = $connect->prepare("SELECT COUNT($item) FROM $table ");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

/*
    === Function to get latest items from database [ Users, Items, Comments ]
*/
function getLatest($select, $table, $order, $limit = 5){
    global $connect;
    $getStmt = $connect->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();

    return $rows;
}

function validate($input,$flag){

    $status = true;
    switch ($flag) {
        case 1:
            # code...
            if(empty($input)){
                $status = false;
            }
            break;

        case 2:
            # code ...
            if(!filter_var($input,FILTER_VALIDATE_EMAIL)){
                $status = false;
            }
            break;


        case 3:
            # code ...
            if(strlen($input) < 6){
                $status = false;
            }
            break;


        case 4:
            # code ...
            if(!filter_var($input,FILTER_VALIDATE_INT)){
                $status = false;
            }
            break;

        case 5:
            #code ....
            $allowedExtension = ["jpeg","jpg","png","gif"];
            if(!in_array($input,$allowedExtension)){
                $status = false;
            }
            break;
        case 6 : 
            # code ..... 
            if(!preg_match('/^01[0-2,5][0-9]{8}$/',$input)){
                $status = false;
            }
            break;
}

    return $status ;
}