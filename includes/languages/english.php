<?php

    function lang($phrase) {
        static $lang = array(

            // Navbar Links
            
            'HOME'       => 'Home',
            'CATEGORIES' => 'Categories',
            'ITEMS'      => 'Items',
            'MEMBERS'    => 'Members',
            'COMMENTS'   => 'Comments',
            'STATISTICS' => 'Statistics',
            'LOGS'       => 'Logs',
            
        );
        return $lang[$phrase];
    }