<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Справочник</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	</head>
	<body>
		<!-- Шапка -->
		<header>
			<!-- Заголовок -->
			<div class="title">
				<h1><a href="index.php">Телефонный справочник ЦЦТ</a></h1> <!-- Лого -->
			</div>
			<!-- Строка с поиском -->
			<div class="search">
				<div class="search_form">
					<form action="functions/search.php" method="get">
						<input type="hidden" name="global" value="yes">
		                <input type="search" name="q" autocomplete="off" placeholder="Поиск контактов">
		                <button type="submit">Найти</button>
		            </form>
		        </div>
	    	</div>
	    	<!-- Админ панель -->
	    	<?php

	    	session_start();
			if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
				echo "
				<div class=admin_panel>
		    		<div class=admin_panel_exit>
		    			<a href=admin/logout.php class=\"button contact_delete\">Выйти</a>
		    		</div>
		    		<div class=admin_panel_add>
			        	<a href=functions/new.php?handbook=new class=\"button contact_add\">Добавить контакт</a>
			        </div>
			        <div class=admin_panel_php>
		    			<a href=http://phonebook.udm/phpmyadmin class=button>Войти в PhpMyAdmin</a>
		    		</div>
	    		</div>";
			} else {
				echo "
			  	<div class=admin_panel>
			  		<div class=admin_panel_php>
			  			<a href=admin/admin.php class=button>Войти в админ панель</a>
			  		</div>
			  	</div>
			  	";
			}

			?>
    	</header>
    	<!-- Выбор отдела -->
		<main>
			<div class="department">
				<?php
                    include 'functions/connection_bd.php';

                    $str_out_cats = "SELECT * FROM `department` ORDER BY `id`";
                    $run_out_cats = mysqli_query($connect,$str_out_cats);
                    $count=mysqli_num_rows($run_out_cats);

                    if ($count>=1) {
                    	if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
                    	echo "
	                    	<div class=search_result>
	                        	<a href=functions/new.php?department=new class=\"button contact_add\">Добавить отдел</a>
	                    	</div>";
	                    } else {
	                    	echo "";
	                    }

	                    while ($out = mysqli_fetch_array($run_out_cats)) {
	                    	echo "
	                        <div>";

	                        	if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
	                        		echo "<div><a class=\"button contact_edit\" href=functions/update.php?department=update&id='$out[id]' >Изменить</a></div>";
	                        	} else {
	                        		echo "";
	                        	}

	                        	echo "<div><a class=\"button department_select\" href=contacts.php?id=$out[id]>$out[name] $out[abbreviation]</a></div>";

	                        	if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
	                        		echo "<div><a class=\"button contact_delete\" href=functions/delete.php?department=delete&id='$out[id]' onclick=\"return confirm('Вы уверены что хотите удалить данный отдел? Все контакты связанные с данным отделом будут так же удалены!')\">Удалить</a></div>";
	                        	} else {
	                        		echo "";
	                        	}
	                        echo "</div>";
	                    }

	                }else { // Если список отделов пуст
	            		echo "<div class=search_result> 
	                    		<h2>Список отделов пуст :(</h2>
	                		</div>";
	            	}
                ?>
			</div>
		</main>
		<!-- Подвал -->
		<footer>
			<div>
			</div>
		</footer>
	</body>
</html>
<tr>