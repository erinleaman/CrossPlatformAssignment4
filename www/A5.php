<?php

$action = $_POST['action'];

//used the switch statement to select their functions   
switch($action){
    case 'register':
        register();
        break;
    case 'login':
        login();
        break;
    case 'checkLogin':
        checkLogin();
        break;
    case 'message':
        message();
        break;
    case 'send':
        send();
        break;
    
}

//added the login function and an if statement to check if the username and passord is needed.
function login(){
    if( strcmp('', $_POST['password']) == 0 || strcmp('', $_POST['name']) == 0) {
        echo 'no info';
        return;
    }
    require('student.php');
    $con = dbConnect();
    $query = "SELECT password, user_id FROM name_login WHERE username = :username;";
    $stm = $con->prepare($query);
    $hashPass = md5($_POST['password']);
    $stm->bindValue(':username', $_POST['name'],PDO::PARAM_STR);
    $stm->execute();
    $row = $stm->fetch(PDO::PARAM_STR);
    $hashPass = md5($_POST['password']);
    if( strcmp($row['password'], $hashPass) == 0) {
        echo 'good to go';
        session_start();
        $_SESSION['user_id'] = $row['user_id'];
    }else{
        echo 'no go';
    }
   
   
}
//added username and password to the database to register account
function register(){
    require('student.php');
    $con = dbConnect();
    $query = "INSERT INTO name_login (username,password) VALUES (:username,:password)";
    $stm = $con->prepare($query);
    $hashPass = md5($_POST['password']);
    $stm->bindValue(':username', $_POST['username'],PDO::PARAM_STR);
    $stm->bindValue(':password', $hashPass,PDO::PARAM_STR);
    if($stm->execute()){
        echo 'success';
    }else{
        echo 'failure';
    }
}

//check if logged in and begin session then echo 'Done' when session is over
function checkLogin() {
    session_start();
    unset($_SESSION['logginIn']);
    echo 'Done';
}

//a function to select the messages from one database to another with an inner join to connect them 
function message() {
    require('student.php');
    $con = dbConnect();
    $query = "SELECT name_login.username,
                    message_sent.date_time,
                    message_sent.messages
             FROM name_login
              INNER JOIN message_sent
                     ON name_login.user_id = message_sent.id_sent
             ORDER BY message_id desc;";
    $stm= $con->prepare($query);
    $stm->execute();
    while( $row = $stm->fetch() ) {
        echo '<li>' . $row['username'] . ' - ' .  $row['messages'] . '<br><span class="message_time">sent at ' .  explode(" ",$row['date_time'])[1] .  '</span></li>';
    }
}
//a function to show who sent the message and what time it was sent.
function send(){
    require('student.php');
    $con=dbConnect();
    if( isset($_POST['message']) ) {
        $query = "INSERT INTO message_sent (messages, date_time, id_sent) VALUES (:message, NOW(), :username_id );";
        $stm=$con->prepare($query);
        $stm->bindValue(':message',$_POST['message'],PDO::PARAM_STR);
        session_start();
        $stm->bindValue(':username_id', $_SESSION['user_id'],PDO::PARAM_INT);
        $stm->execute();
    }
    
}


?>