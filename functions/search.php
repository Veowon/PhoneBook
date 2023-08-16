<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Справочник</title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    </head>
    <body>
        <!-- Шапка -->
        <header>
            <!-- Заголовок -->
            <div class="title">
                <h1><a href="../index.php">Телефонный справочник</a></h1> <!-- Лого -->
            </div>
            <!-- Строка с поиском -->
            <div class="search">
                <div class="search_form">
                    <form action="search.php" method="get">
                        <?php

                        // Тут типа мы берем данные из строки поиска в браузере, если там есть global то поиск идет по всей таблице
                        if (isset($_GET['global']) && !empty($_GET['global'])) {
                            echo "<input type=hidden name=global value=yes>";
                        } elseif (isset($_GET['id']) && !empty($_GET['id'])) { // Иначе если есть id то поиск происходит по отделу
                            $id=$_GET['id'];
                            echo "<input type=hidden name=id value=$id>";
                        } else { // Если оба условия сверху не соблюдены то по умолчанию поиск будет глобальным, то есть по всей таблице
                            echo "<input type=hidden name=global value=yes>";
                        }

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
                        <a href=../admin/logout.php class=\"button contact_delete\">Выйти</a>
                    </div>
                    <div class=admin_panel_add>
                        <a href=new.php?handbook=new class=\"button contact_add\">Добавить контакт</a>
                    </div>
                    <div class=admin_panel_php>
                        <a href=http://phonebook.udm/phpmyadmin class=button>Войти в PhpMyAdmin</a>
                    </div>
                </div>";
            } else {
                echo "
                <div class=admin_panel>
                    <div class=admin_panel_php>
                        <a href=../admin/admin.php class=button>Войти в админ панель</a>
                    </div>
                </div>
                ";
            }

            ?>
        </header>
        <!-- Результат поиска -->
        <main>
            <?php 
            if (isset($_GET['q']) && !empty($_GET['q'])) { 
                $search=$_GET['q']; 
                // Подключение в БД
                include 'connection_bd.php';

                //- Запрос к таблице базы данных
                $global_search = isset($_GET['global']) && !empty($_GET['global']);
                if ($global_search) {
                    $sql="SELECT * FROM `contacts` WHERE `name` LIKE '%$search%' OR `extension_phone` LIKE '%$search%' OR `title` LIKE '%$search%'";
                } elseif (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id=$_GET['id'];
                    $sql="SELECT * FROM `contacts` WHERE `department` = '$id' AND `name` LIKE '%$search%' OR `department` = '$id' AND `extension_phone` LIKE '%$search%' OR `department` = '$id' AND `title` LIKE '%$search%'";
                }
                //- Запустить запрос к функции MySQL Query
                $result=mysqli_query($connect,$sql);
                $count=mysqli_num_rows($result); 
                //-Создание цикла
                if ($count>=1) {
                    echo "
                    <div class=search_result>
                        <a class=\"button button_back search_result\" href=javascript:history.go(-1)>Назад</a>
                    </div>
                    <table class=table>
                    <thead> 
                        <tr>
                            <th rowspan=2>Фото</th>
                            <th rowspan=2>Должность</th>
                            <th rowspan=2>Ф.И.О</th>
                            <th rowspan=2>Почта</th>
                            <th colspan=3>№ Телефона</th>
                            ";
                            if ($global_search) {
                                echo "<th rowspan=2>Отдел</th>";
                            } else {
                                echo "";
                            }
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
                    while ($out = mysqli_fetch_array($result)) { // Записи
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
                            if ($global_search) {
                                $str_out_cats = "SELECT * FROM `department` WHERE `id` = '$out[department]'";
                                $run_out_cats = mysqli_query($connect,$str_out_cats);
                                while ($dep_out = mysqli_fetch_array($run_out_cats))
                                    echo "<td>$dep_out[abbreviation]</td>";
                            } else {
                                echo "";
                            }
                            if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
                                echo "
                                <td class=contact_button><a class=\"button contact_edit\" href=update.php?handbook=update&id='$out[id]' >Изменить</a></td>
                                <td class=contact_button><a class=\"button contact_delete\" href=delete.php?handbook=delete&id='$out[id]' onclick=\"return confirm('Вы уверены что хотите удалить данный контакт?')\">Удалить</a></td>";
                            } else {
                                echo "";
                            }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                } else { // Сообщение если ничего не найдено с амогусом
                    echo "<div class=search_result>
                        <h2>Ничего не найдено ඞ</h2>
                        <a class=\"button button_back search_result\" href=javascript:history.go(-1)>Назад</a>
                    </div>";
                }
            } else { // Сообщение если запрос пустой
                echo "<div class=search_result>
                        <h2>Введите ключевые слова для поиска</h2>
                        <a class=\"button button_back search_result\" href=javascript:history.go(-1)>Назад</a>
                    </div>";
            }
            ?>
            </table>
        </main>
        <!-- Подвал -->
        <footer>
            <div>
            </div>
        </footer>
  </body>
</html>