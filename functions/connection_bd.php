<?php
	// Подключаемся к БД
	// Замени данные между ковычками на свои
	$servername = "localhost"; // Имя хоста
	$username = "root"; // Имя пользователя (логин)
	$password = ""; // Пароль
	$database = "handbook"; // Название БД

	$connect=mysqli_connect("$servername","$username","$password","$database") or die("Ошибка." . mysqli_error($connect));
?>