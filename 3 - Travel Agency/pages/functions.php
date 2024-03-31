<?
//в консрукторе подключения указаны необходимые параметры для выполнения подключения в серверу БД
function connect(
    $host = "localhost",
    $user = "root",
    $pass = "SashaDr2006RusMyAdmin",
    $dbname = "travel_agency"
) {
    //создание объекта подключения, который будет использоваться при работе с БД
    $link = new mysqli($host, $user, $pass, $dbname);
    if ($link->connect_error) {
        die("Ошибка: " . $link->connect_error);
    }
    return $link;
}

function register($login, $pass, $email)
{
    //удаление лишних символов из строки(пробелыб переносы строк, табуляции и т.д.)
    $login = trim(htmlspecialchars($login));
    $pass = trim(htmlspecialchars($pass));
    $email = trim(htmlspecialchars($email));

    //проверка заполнения полей формы и соблюдения условий длины логина и пароля
    if ($login == "" || $pass == "" || $email == "") {
        echo "<h3><span style='color: red;'>Заполните все поля!</span></h3>";
        return false;
    }
    if (strlen($login) < 2 || strlen($login) > 30) {
        echo "<h3><span style='color: red;'>Длина логина должна быть от 2 до 30 символов!</span></h3>";
        return false;
    }
    if (strlen($pass) < 6 || strlen($pass) > 30) {
        echo "<h3><span style='color: red;'>Длина пароля должна быть от 6 до 30 символов!</span></h3>";
        return false;
    }
    //хэшированный пароль
    $hash_pass = md5($pass);

    $ins = "insert into users(login, pass, email, roleid) values('$login', '$hash_pass', '$email', 2)";
    $link = connect(); //получение объекта подключения
    $link->query($ins); //выполнение запроса
    $link->close(); //закрытие подключения
    return true;
}

function login($login, $pass)
{
    $login = trim(htmlspecialchars($login));
    $pass = trim(htmlspecialchars($pass));

    if ($login == "" || $pass == "") {
        echo "<h3><span style='color: red;'>Заполните все поля!</span></h3>";
        return false;
    }
    if (strlen($login) < 2 || strlen($login) > 30) {
        echo "<h3><span style='color: red;'>Длина логина должна быть от 2 до 30 символов!</span></h3>";
        return false;
    }
    if (strlen($pass) < 6 || strlen($pass) > 30) {
        echo "<h3><span style='color: red;'>Длина пароля должна быть от 6 до 30 символов!</span></h3>";
        return false;
    }

    //хэшированный пароль
    $hash_pass = md5($pass);

    $link = connect();
    $sel = "select * from users where login='$login' and pass='$hash_pass'";
    //query возвращает объект, который кроме данных хранит служебную информацию о выполнении запроса(кол-во обработанных строк, нумерацию строк и т.д.)
    //чтобы извлечь данные, необходимо использовать метод fetch_assoc, который возвращает данные в виде ассоциативного масива
    $res = $link->query($sel);
    if ($row = $res->fetch_assoc()) {
        $_SESSION["user"] = $login;
        //если у пользователя роль админа
        if ($row["roleid"] == 1) {
            $_SESSION["admin"] = $login;
        }
        return true;
    } else {
        echo "<h3><span style='color: red;'>Пользователь не найден!</span></h3>";
        return false;
    }
}
