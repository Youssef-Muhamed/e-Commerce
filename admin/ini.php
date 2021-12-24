<?php

    include 'connect.php';

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

    if (!isset($noNavbar)){
       include $tpl . "navbar.php";
    }