<?php

//I made a function to connect the php file with the database table ss2
function dbConnect() {
    $db_uid = "root";
    $db_pwd = "password";
    $db_conn_string = "mysql:host=localhost;dbname=chat;charset=utf8";
    $dbConnection = new PDO($db_conn_string, $db_uid, $db_pwd);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}



?>