<?

include_once("functions.php");
$link = connect();

$ct1 = "
create table users(
        id int not null auto_increment primary key,
        login varchar(128),
        email varchar(256),
        password varchar(128) 
)";

if ($link->query(($ct1))) {
  echo "Таблица Users успешно создана <br>";
} else {
  echo "Ошибка: " . $link->error;
}

$link->close();
