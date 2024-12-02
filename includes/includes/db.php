<?php
    $db['db_host'] = '127.0.0.1';
    $db['db_port'] = '3306';
    $db['db_user'] = 'garoot';
    $db['db_pass'] = 'HaX1lml@gJIK';
    $db['db_name'] = 'goldenarm';

    foreach($db as $key => $value){
        define(strtoupper($key), $value);
    }

    $connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    // if($connection){
    //     echo "we are connected";
    // }
?>