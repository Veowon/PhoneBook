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
                <h1><a href="../index.php">Телефонный справочник ЦЦТ</a></h1> <!-- Лого -->
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
        <!-- Форма добавления -->
        <main>

            <!-- Форма для нового контакта -->
            <?php if (isset($_GET['handbook']) and $_GET['handbook'] == 'new'): ?>
                <div class=search_result>
                    <h2>Добавить новый контакт</h2>
                </div>
                <div>
                    <main> 
                        <!-- Форма добавление -->
                        <form method="POST" action="save.php?handbook=new" class="form_add" enctype="multipart/form-data">
                            <input name="id" type="hidden">
                            <div>
                                <label>Должность</label>
                                <input name="title" type="text" required placeholder="Директор">
                            </div>
                            <div>
                                <label>Ф.И.О</label>
                                <input name="name" type="text" required placeholder="Иван Иванов">
                            </div>
                            <div>
                                <label>Почта</label>
                                <input name="mail" type="mail" placeholder="example@gmail.com">
                            </div>
                            <div>
                                <label>Город. номер</label>
                                <input name="landline_phone" type="tel" placeholder="999-999">
                            </div>
                            <div>
                                <label>Внутр. номер</label>
                                <input name="extension_phone" type="tel" required placeholder="999">
                            </div>
                            <div>
                                <label>Мобильный номер</label>
                                <input name="mobile_phone" type="tel" placeholder="8-800-000-0000">
                            </div>
                            <div>
                                <label>Отдел</label>
                                <select name="department" required>
                                    <option disabled selected value="">Выберите отдел</option>
                                <?php
                                    require('connection_bd.php');
                                    $cat = mysqli_query($connect, "SELECT * FROM `department`");
                                    while ($cat_out = mysqli_fetch_array($cat)) {
                                        echo "<option value=$cat_out[id]>$cat_out[name]</option>";
                                    };
                                ?>
                                </select>
                            </div>
                            <div>
                                <label>Приоритет</label>
                                <input name="priority" type="number" placeholder="1 - 10">
                            </div>
                            <div>
                                <label>Загрузить фотографию</label>
                                <input name="file" type="file">
                            </div>
                            <div>
                                <a class="button button_back search_result" href=javascript:history.go(-1)>Назад</a>
                                <button type="submit">Добавить</button>
                            </div>
                        </form>
                    </main>
                </div>

            <!-- Форма для нового отдела -->
            <?php elseif (isset($_GET['department']) and $_GET['department'] == 'new'): ?>
                <div class=search_result>
                    <h2>Добавить новый отдел</h2>
                </div>
                <div>
                    <main> 
                        <!-- Форма добавление -->
                        <form method="POST" action="save.php?department=new" class="form_add">
                            <input name="id" type="hidden">
                            <div>
                                <label>Название отдела</label>
                                <input name="name" type="text" placeholder="Руководство" required>
                            </div>
                            <div>
                                <label>Аббревиатура (необяз.)</label>
                                <input name="abbreviation" type="text" placeholder="(ООП)">
                            </div>
                            <div>
                                <a class="button button_back search_result" href=javascript:history.go(-1)>Назад</a>
                                <button type="submit">Добавить</button>
                            </div>
                        </form>
                    </main>
                </div>

            <?php else: ?>
                <div class=search_result>
                    <h1>Перейдите назад и выберите что изменить.</h1>
                    <a class="button button_back search_result" href=../index.php>Назад</a>
                </div>
            <?php endif ?>
        </main>
        <!-- Подвал -->
        <footer>
            <div>
            </div>
        </footer>
    </body>
</html>