<?php 
    // Проверка на админа
    session_start();
    if (isset($_SESSION["login"]) && isset($_SESSION["password"])) { 
        // Подключение к БД
        require('connection_bd.php');

        // Если удаление контакта
        if ($_GET['handbook']=='delete') {
            $id = $_GET['id'];

            $out = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `photo` FROM `contacts` WHERE `id` = $id"));
            $photo = '../img/photos/'.$out["photo"];
            // Проверяем на наличие фотографии в папке
            if (!unlink($photo)) {
                mysqli_query($connect, "DELETE FROM `contacts` WHERE `contacts`.`id` = $id");

                // Возвращаемся на главную
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                // Выполняем удаление
                mysqli_query($connect, "DELETE FROM `contacts` WHERE `contacts`.`id` = $id");

                // Возвращаемся на главную
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        // Иначе если удаление отдела
        } elseif ($_GET['department']=='delete') {
            $id = $_GET['id'];

            mysqli_query($connect, "DELETE FROM `department` WHERE `department`.`id` = $id");

            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    } else {
        echo "Вы не вошли в панель администратора";
    }
?>