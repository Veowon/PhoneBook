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
        </header>
        <!-- Форма входа в админ панель -->
        <main>
            <div>
                <h2>Войти в админ панель</h2>
            </div>
            <div> 
                <form method="POST" action="auth.php" class="form_add">
                    <div>
                        <label>Логин</label>
                        <input name="login" type="login" required>
                    </div>
                    <div>
                        <label>Пароль</label>
                        <input name="password" type="password" required>
                    </div>
                    <div>
                        <a class="button button_back search_result" href=../index.php>Назад</a>
                        <button type="submit">Войти</button>
                    </div>
                </form>
            </div>
        </main>
        <!-- Подвал -->
        <footer>
            <div>
            </div>
        </footer>
    </body>
</html>