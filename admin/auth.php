<?php 
$login = "admin";
#$password = "admin";
$password = "LfdGjn67";

if ($login == $_POST['login'] && $password == $_POST['password']){

    session_start();
    $_SESSION["login"] = $_POST['login'];
    $_SESSION["password"] = $_POST['password']; 
    header('Location: ../index.php');

}

else {

    echo "
    <span>Неправильные логин/пароль</span>
    ";

}

?>