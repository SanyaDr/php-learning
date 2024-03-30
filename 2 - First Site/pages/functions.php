<?
// session_start();

//файл для хранения пользователей
$users = "pages/users.txt";
function register($name, $email, $pass)
{
    //trim обрезаеt лишние символы(пробелыь переносы строк, табуляции)
    //htmlspecialchars конвертирует содержимое в html для предотвращения выполнения скриптов
    $name = trim(htmlspecialchars($name));
    $email = trim(htmlspecialchars($email));
    $pass = trim(htmlspecialchars($pass));

    if ($name == "" || $email == "" || $pass == "") {
        echo "<h3 class='text-danger'>Заполните все обязательные поля!</h3>";
        return false;
    }

    //если логин или пароль не соответствуют допустимому диапазону длины
    if (strlen($name) < 2) {
        echo "<h3 class='text-danger'>Логин должен содержать не менее 2 символов!</h3>";
        return false;
    } else if (strlen($name) > 15) {
        echo "<h3 class='text-danger'>Логин должен содержать не более 15 символов!</h3>";
        return false;
    }

    if (strlen($pass) < 5) {
        echo "<h3 class='text-danger'>Пароль должен содержать не менее 5 символов!</h3>";
        return false;
    } else if (strlen($pass) > 15) {
        echo "<h3 class='text-danger'>Пароль должен содержать не более 15 символов!</h3>";
        return false;
    }

    //получаем глобальную переменную
    global $users;

    //открываем файл users.txt
    $file = fopen($users, "a+");
    //считываем построчно содержимое файла, по 128 символов
    while ($line = fgets($file, 128)) {
        //из строки извлекаем подстроку, которая содержит логин, до знака :
        $readname = substr($line, 0, strpos($line, ":"));
        //если такой логин уже есть в файле
        if ($readname == $name) {
            echo "<h3 class='text-danger'>Данное имя пользователя уже занято!</h3>";
            return false;
        }
    }

    //формируем строку для нового пользователя
    $line = $name . ":" . md5($pass) . ":" . $email . "\n";
    //записываем в файл
    fputs($file, $line);
    fclose($file);
    return true;
}

function LogInAccount()
{
    if (isset($_POST["loginbtn"])) {
        if (isset($_POST["login"]) && isset($_POST["password"])) {
            $login =  $_POST["login"];
            $password = $_POST["password"];

            global $users;
            $file = fopen($users, "r");
            while ($line = fgets($file, 128)) {
                // echo "line->   $line";
                $readname = substr($line, 0, strpos($line, ":"));
                // echo "<br>readname->   $readname <br>";
                if ($readname == $login) {
                    $left = substr($line, strlen($readname) + 1);
                    // echo "left->   $left <br>";
                    $password = md5($password);
                    $right = substr($left, 0, strpos($left, ":"));
                    // echo "right->   $right<br>";
                    if ($right == $password) {
                        echo "<div class='text-success'>Авторизация успешна</div>";
                        $_SESSION["registered-user"] = $login;
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        return true;
                    } else {
                        echo "<div class='text-danger'>Неверный пароль</div>";
                        unset($_SESSION["registered-user"]);
                        return false;
                    }
                }
            }
            echo "<div class='text-danger'>Аккаунт с указанным логином не найден!</div>";
            unset($_SESSION["registered-user"]);
            return false;
        }
    }
}

function LogOut()
{
    unset($_SESSION["registered-user"]);
    header('Location: ' . $_SERVER['REQUEST_URI']);
}
