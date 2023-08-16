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
        <!-- Форма изменения -->
        <main>
            <?php 
                // Подключение к БД
                require('connection_bd.php');

                // Берем ID из GET запроса (строка поиска сверху) и находим нужную запись по айдишнику
                $id = $_GET['id'];
                $out = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `contacts` WHERE `id` = $id"));
                $out_dep = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `department` WHERE `id` = $id"));
            ?>
            <?php if (isset($_GET['handbook']) and $_GET['handbook'] == 'update'): ?> 
                <!-- Тут типа появляются поля с данными которые можно изменить -->
                <div class=search_result>
                    <h2>Изменить контакт</h2>
                </div>
                <div>
                    <main>
                        <form method="POST" action="save.php?handbook=update" class="form_add" enctype="multipart/form-data">
                            <input name="id" type="hidden" value="<?php echo $out["id"]; ?>">
                            <div>
                                <label>Должность</label>
                                <input name="title" value="<?php echo $out["title"]; ?>">
                            </div>
                            <div>
                                <label>Ф.И.О</label>
                                <input name="name" value="<?php echo $out["name"]; ?>">
                            </div>
                            <div>
                                <label>Почта</label>
                                <input name="mail" value="<?php echo $out["mail"]; ?>">
                            </div>
                            <div>
                                <label>Город. номер</label>
                                <input name="landline_phone" value="<?php echo $out["landline_phone"]; ?>">
                            </div>
                            <div>
                                <label>Внутр. номер</label>
                                <input name="extension_phone" value="<?php echo $out["extension_phone"]; ?>">
                            </div>
                            <div>
                                <label>Мобильный номер</label>
                                <input name="mobile_phone" value="<?php echo $out["mobile_phone"]; ?>">
                            </div>
                            <div>
                                <label>Отдел</label>
                                <select name="department">
                                    <option selected value="<?php echo $out["department"]; ?>">Оставить текущий отдел</option>
                                <?php
                                    $cat = mysqli_query($connect, "SELECT * FROM `department`");
                                    while ($cat_out = mysqli_fetch_array($cat)) {
                                        echo "<option value=$cat_out[id]>$cat_out[name]</option>";
                                    };
                                ?>
                                </select>
                            </div>
                            <div>
                                <label>Приоритет</label>
                                <input name="priority" type="number" placeholder="1 - 10" value="<?php echo $out["priority"]; ?>">
                            </div>
                            <div>
                                <label>Загрузить фотографию</label>
                                <input name="file" type="file">
                            </div>
                            <div>
                                <a class="button button_back search_result" href=javascript:history.go(-1)>Назад</a>
                                <button type="submit">Изменить</button>
                            </div>
                        </form>
                    </main>
                </div>

            <?php elseif (isset($_GET['department']) and $_GET['department'] == 'update'): ?>
                <div class=search_result>
                    <h2>Добавить новый отдел</h2>
                </div>
                <div>
                    <main> 
                        <!-- Форма добавление -->
                        <form method="POST" action="save.php?department=update" class="form_add">
                            <input name="id" type="hidden" value="<?php echo $out_dep["id"]; ?>">
                            <div>
                                <label>Название отдела</label>
                                <input name="name" type="text" placeholder="Руководство" value="<?php echo $out_dep["name"]; ?>" required>
                            </div>
                            <div>
                                <label>Аббревиатура (необяз.)</label>
                                <input name="abbreviation" type="text" value="<?php echo $out_dep["abbreviation"]; ?>">
                            </div>
                            <div>
                                <a class="button button_back search_result" href=javascript:history.go(-1)>Назад</a>
                                <button type="submit">Изменить</button>
                            </div>
                        </form>
                    </main>
                </div>

            <?php else: ?>
                <!-- Если что-то пошло не по плану то можно вернуться назад -->
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