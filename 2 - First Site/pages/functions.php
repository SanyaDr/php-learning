<?
// session_start();

function connect(
    $host = "localhost",
    $user = "root",
    $password = "SashaDr2006RusMyAdmin",
    $dbname = "first_site"
) {
    $link = new mysqli($host, $user, $password, $dbname);
    if ($link->connect_error) {
        die("Ошибка: " . $link->connect_error);
    }
    return $link;
}

//файл для хранения пользователей
// $users = "pages/users.txt";
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

    $hash_password = md5($pass);

    $ins = "insert into users(login, password, email) values('$name', '$hash_password', '$email')";
    $link = connect();
    $link->query($ins);
    $link->close();
    return true;
}

function LogInAccount()
{
    if (isset($_POST["loginbtn"])) {
        if (isset($_POST["login"]) && isset($_POST["password"])) {
            $login =  $_POST["login"];
            $password = $_POST["password"];

            $got_hash_password = md5($password);

            $link = connect();
            $sel = "select * from users where login='$login' and password='$got_hash_password'";

            $res = $link->query($sel);
            if ($row = $res->fetch_assoc()) {
                $_SESSION["registered-user"] = $login;
                header("Refresh:0");
                return true;
            } else {
                echo "<h3><span style='color: red;'>Логин или пароль ввведены неверно!</span></h3>";
                return false;
            }
        }
    }
}

function LogOut()
{
    unset($_SESSION["registered-user"]);
    header('Location: ' . $_SERVER['REQUEST_URI']);
}
