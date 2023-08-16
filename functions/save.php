<?php 
    // Подключение к БД
    require('connection_bd.php');
  
    // Если добавляем запись то выполняется этот код
    if (isset($_GET['handbook']) and $_GET['handbook']=='new') {
        // Берем данные из POST запроса
        $id = $_POST['id'];
        $title = $_POST['title'];
        $name = $_POST['name'];
        $mail = $_POST['mail'];
        $landline_phone = $_POST['landline_phone'];
        $extension_phone = $_POST['extension_phone'];
        $mobile_phone = $_POST['mobile_phone'];
        $department = $_POST['department'];
        $priority = $_POST['priority'];

        if (empty($_FILES['file']['name'])) {
            mysqli_query($connect, "INSERT INTO `contacts` (`title`, `name`, `mail`, `landline_phone`, `extension_phone`, `mobile_phone`, `department`, `priority`) VALUES ('$title', '$name', '$mail', '$landline_phone', '$extension_phone', '$mobile_phone', '$department', '$priority');");
        } else {
            $file = $_FILES['file'];
            $fileName = time()."_".$file['name'];
            $filePath = '../img/photos/'.$fileName;

            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                echo "Файл не загрузился";
                exit();
            }

            // И вставляем их в базу данных через запрос
            mysqli_query($connect, "INSERT INTO `contacts` (`title`, `name`, `mail`, `landline_phone`, `extension_phone`, `mobile_phone`, `department`, `priority`, `photo`) VALUES ('$title', '$name', '$mail', '$landline_phone', '$extension_phone', '$mobile_phone', '$department', '$priority', '$fileName');");
        }
    }
?>
<script type="text/javascript">
    window.history.go(-2);
</script>
<?php 
    // Если изменяем запись то выполняется этот код
    if (isset($_GET['handbook']) and $_GET['handbook']=='update') {
        // Берем данные из POST запроса
        $id = $_POST['id'];
        $title = $_POST['title'];
        $name = $_POST['name'];
        $mail = $_POST['mail'];
        $landline_phone = $_POST['landline_phone'];
        $extension_phone = $_POST['extension_phone'];
        $mobile_phone = $_POST['mobile_phone'];
        $department = $_POST['department'];
        $priority = $_POST['priority'];

        $out = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `photo` FROM `contacts` WHERE `id` = $id"));
        $photo = $out["photo"];
        $photoPath = '../img/photos/'.$photo;
        if (empty($_FILES['file']['name'])) {
            mysqli_query($connect, "UPDATE `contacts` SET `title` = '$title', `name` = '$name', `mail` = '$mail', `landline_phone` = '$landline_phone', `extension_phone` = '$extension_phone', `extension_phone` = '$extension_phone', `mobile_phone` = '$mobile_phone', `department` = '$department', `priority` = '$priority', `photo` = '$out[photo]' WHERE `contacts`.`id` = $id");
        } elseif (empty($photo)) {
            $file = $_FILES['file'];
            $fileName = time()."_".$file['name'];
            $filePath = '../img/photos/'.$fileName;

            move_uploaded_file($file['tmp_name'], $filePath);

            // Вставляем их в базу данных через запрос
            mysqli_query($connect, "UPDATE `contacts` SET `title` = '$title', `name` = '$name', `mail` = '$mail', `landline_phone` = '$landline_phone', `extension_phone` = '$extension_phone', `extension_phone` = '$extension_phone', `mobile_phone` = '$mobile_phone', `department` = '$department', `priority` = '$priority', `photo` = '$fileName' WHERE `contacts`.`id` = $id");   
        } elseif (!unlink($photoPath)) {
            $file = $_FILES['file'];
            $fileName = time()."_".$file['name'];
            $filePath = '../img/photos/'.$fileName;

            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                echo "Файл не загрузился";
                exit();
            }

            // Вставляем их в базу данных через запрос
            mysqli_query($connect, "UPDATE `contacts` SET `title` = '$title', `name` = '$name', `mail` = '$mail', `landline_phone` = '$landline_phone', `extension_phone` = '$extension_phone', `extension_phone` = '$extension_phone', `mobile_phone` = '$mobile_phone', `department` = '$department', `priority` = '$priority', `photo` = '$fileName' WHERE `contacts`.`id` = $id");   
        } else {
            $file = $_FILES['file'];
            $fileName = time()."_".$file['name'];
            $filePath = '../img/photos/'.$fileName;

            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                echo "Файл не загрузился";
                exit();
            }

            // Вставляем их в базу данных через запрос
            mysqli_query($connect, "UPDATE `contacts` SET `title` = '$title', `name` = '$name', `mail` = '$mail', `landline_phone` = '$landline_phone', `extension_phone` = '$extension_phone', `extension_phone` = '$extension_phone', `mobile_phone` = '$mobile_phone', `department` = '$department', `priority` = '$priority', `photo` = '$fileName' WHERE `contacts`.`id` = $id");   
        }
    }
?>
<script type="text/javascript">
    window.history.go(-2);
</script>
<?php 
    // Если добавляем отдел то выполняется этот код
    if (isset($_GET['department']) and $_GET['department']=='new') {
        // Берем данные из POST запроса
        $id = $_POST['id'];
        $name = $_POST['name'];
        $abbreviation = $_POST['abbreviation'];

        // И вставляем их в базу данных через запрос
        mysqli_query($connect, "INSERT INTO `department` (`name`, `abbreviation`) VALUES ('$name', '$abbreviation');");
    }
?>
<script type="text/javascript">
    window.history.go(-2);
</script>
<?php 
    // Если изменяем отдел то выполняется этот код
    if (isset($_GET['department']) and $_GET['department']=='update') {
        // Берем данные из POST запроса
        $id = $_POST['id'];
        $name = $_POST['name'];
        $abbreviation = $_POST['abbreviation'];

        // И вставляем их в базу данных через запрос
        mysqli_query($connect, "UPDATE `department` SET `name` = '$name', `abbreviation` = '$abbreviation' WHERE `department`.`id` = $id");
    }
?>
<script type="text/javascript">
    window.history.go(-2);
</script>