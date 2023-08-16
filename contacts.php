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
						<?php
							// Тут мы берем id отдела для поиска по нему
							$id = $_GET['id'];
							echo "<input type=hidden name=id value=$id>";
						?>
		                <input type="search" name="q" autocomplete="off" placeholder="Поиск контактов">
		                <button type="submit">Найти</button>
		            </form>
		        </div>
	    	</div>
	    	<!-- Админ панель -->
	    	<?php

	    	session_start();
			if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
				// Кнопки администратора
				echo "
				<div class=admin_panel>
		    		<div class=admin_panel_exit>
		    			<a href=admin/logout.php class=\"button contact_delete\">Выйти</a>
		    		</div>
		    		<div class=admin_panel_add>
			        	<a href=functions/new.php?handbook=new class=\"button contact_add\">Добавить контакт</a>
			        </div>
			        <div class=admin_panel_php>
		    			<a href=http://phonebook.udm/phpmyadmin/index.php class=button>Войти в PhpMyAdmin</a>
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
    	<!-- Таблица -->
    	<main>
			<div class="departmentss">
				<?php
					include 'functions/connection_bd.php';

					$str_out_cats = "SELECT * FROM `department` ORDER BY `id`";
					$run_out_cats = mysqli_query($connect,$str_out_cats);
					$count=mysqli_num_rows($run_out_cats);
					
					if ($count>=1) {
						while ($out = mysqli_fetch_array($run_out_cats)) {
							echo "<div><a class=\"button departmentss_select\" href=contacts.php?id=$out[id]> $out[abbreviation]</a></div>";
						}
					}else { // Если список отделов пуст
						echo "<div class=search_result> 
								<h2>Список отделов пуст :(</h2>
							</div>";
					}
				?>
			</div>
    		<div class="search_result">
    			<!-- Кнопка назад -->
    			<a class="button button_back search_result" href="index.php">Назад</a>
    			<!-- Название отдела -->
    			<?php
    				$id = $_GET['id'];
    				include 'functions/connection_bd.php';
                    $str_out_cats = "SELECT * FROM `department` WHERE `id` = '$id'";
                    $run_out_cats = mysqli_query($connect,$str_out_cats);
                    while ($out = mysqli_fetch_array($run_out_cats)) {
                    	echo "<h2>$out[name] $out[abbreviation]</h2>";
                    }
    			?>
    		</div>
				<?php
					// Подключение в БД
	                include 'functions/connection_bd.php';

	                // Число страницы для пагинации
					if (isset($_GET['page'])) {
	                    $page = $_GET['page'];
	                } else {
	                    $page = 1;
	                }

	                $id = $_GET['id'];
	                $limit = 15; // Лимит контактов на одной странице, если тебе нужно больше то просто поменяй цифру
	                $offset = ($page - 1) * $limit;

	                // Запрос к таблице базы данных
	                $str_out_cats = "SELECT * FROM `contacts` WHERE `department` = '$id' ORDER BY `priority` ASC, `id` ASC LIMIT $offset, $limit";
	                // Запустить запрос к функции MySQL Query
	                $run_out_cats = mysqli_query($connect,$str_out_cats);
	                $count=mysqli_num_rows($run_out_cats);

	                // Создание цикла
	                if ($count>=1) {
	                	echo "
	                    <table class=table>
	                    <thead> 
	                        <tr>
	                            <th rowspan=2>Фото</th>
	                            <th rowspan=2>Должность</th>
	                            <th rowspan=2>Ф.И.О</th>
	                            <th rowspan=2>Почта</th>
	                            <th colspan=3>№ Телефона</th>
	                            ";
	                            if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
	                                echo "<th rowspan=2 colspan=2>Действия</th>";
	                            } else {
	                                echo "";
	                            }
	                        echo "
	                        </tr>
	                        <tr>
	                            <th>город.</th>
	                            <th>внутр.</th>
	                            <th>мобильный</th>
	                        </tr>
	                    </thead>
	                    <tbody>";
	                    while ($out = mysqli_fetch_array($run_out_cats)) { // Записи
	                        echo "
	                        <tr>
	                            <td><img class=table_photo src=\"/img/photos/$out[photo]\" onerror=\"this.onerror=null; this.src='/img/photos/default.png'\"></td>
	                            <td>$out[title]</td>
	                            <td>$out[name]</td>
	                            <td>$out[mail]</td>
	                            <td>$out[landline_phone]</td>
	                            <td>$out[extension_phone]</td>
	                            <td>$out[mobile_phone]</td>
	                            ";
	                            if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
	                                echo "
	                                <td class=contact_button><a class=\"button contact_edit\" href=functions/update.php?handbook=update&id='$out[id]' >Изменить</a></td>
	                                <td class=contact_button><a class=\"button contact_delete\" href=functions/delete.php?handbook=delete&id='$out[id]' onclick=\"return confirm('Вы уверены что хотите удалить данный контакт?')\">Удалить</a></td>";
	                            } else {
	                                echo "";
	                            }
	                        echo "</tr>";
	                    }
                    echo "</tbody>";
	            	} else { // Если БД пустая
	            		echo "<div class=search_result> 
	                    		<h2>Список контактов пуст :(</h2>
	                		</div>";
	            	}
	            ?>
			</table>
			<!-- Пагинация -->
			<div class="pagination">
				<nav>
					<ul>
						<!-- Хз как это комментировать, оно просто работает кек -->
						<?php
						if ($count>=1) {
							$id = $_GET['id'];
				            $query = "SELECT COUNT(*) FROM `contacts` WHERE `department` = '$id'";
				            $run_out_pages = mysqli_query($connect,$query);
				            $count = mysqli_fetch_array($run_out_pages)['0'];
				            $pages = ceil($count / $limit);
				            $id = $_GET['id'];

				            if ($page != 1) {
				                $prev = $page - 1;
				                echo "<li>
				                        <a class=page_link href=?id=$id&page=$prev>Предыдущая</a>
				                    </li>";
				            }

				            for ($i=1; $i <= $pages ; $i++) { 
				                if ($page == $i) {
				                    $class = '';
				                } else {
				                    $class = '';
				                }

				                echo "<li>
				                        <a class=page_link href=?id=$id&page=$i>$i</a>
				                    </li>
				                ";
				            }

				            if ($page != $pages) {
				                $prev = $page + 1;
				                echo "<li>
				                        <a class=page_link href=?id=$id&page=$prev>Следующая</a>
				                    </li>";
				            }
				        } else {
				        	echo " ";
				        }
				        ?>	
	        		</ul>
	        	</nav>
	        </div>	
    	</main>
    	<!-- Подвал -->
		<footer>
			<div>
				<!-- Копирайт гыгыгы -->
			</div>
		</footer>
	</body>
</html>
<tr>