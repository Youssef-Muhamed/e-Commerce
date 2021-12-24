<?php
//ob_start();
    // Error Reporting
    ini_set('display_errors','On');
    error_reporting(E_ALL);

    include 'admin/connect.php';

    $sessionUser = '';
    if (isset ($_SESSION['user'])){
        $sessionUser = $_SESSION['user'];
    }
    // routes

    $tpl  = 'includes/templates/'; // Template Directiry
    $lang = 'includes/languages/'; // Languages Directiry
    $func = 'includes/functions/'; // Functions Directiry
    $css  = 'layout/css/'; // css Directiry
    $js   = 'layout/js/'; // js Directiry

    // The important files
    include $func . 'functions.php';
    include  $lang . 'english.php';
    include $tpl . "header.php";
    //ob_end_flush();