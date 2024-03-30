<!DOCTYPE html>
<html lang="en">
<?
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Site</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <header class="col-sm-12 col-md-12 col-lg-12">
                <div style="display: flex;">

                    <?
                    if (!isset($_SESSION["registered-user"])) { ?>
                        <form method="post">
                            <div class="input-group">
                                <span class="input-group-text">Login and password</span>
                                <input type="text" name="login" aria-label="First name" placeholder="Login" class="form-control">
                                <input type="text" name="password" aria-label="Last name" placeholder="Password" class="form-control">
                                <button class="btn btn-primary" name="loginbtn" type="submit" id="button-addon2">Submit</button>
                            </div>
                        </form>
                    <?
                    } else {
                    ?>
                        <div style="display:grid;">
                            <div style="display: inline;">
                                <? $logn = $_SESSION["registered-user"];
                                echo "<div>Привет, $logn</div>";
                                ?>
                            </div>
                            <div style="display: inline;">
                                <form method="post">
                                    <button class="btn btn-outline-secondary" name="logoutbtn">Выйти</button>
                                </form>
                            </div>
                        </div>
                    <?
                    }
                    ?>
                </div>

            </header>
        </div>
        <div class="row">
            <nav class="col-sm-12 col-md-12 col-lg-12">
                <?php include_once("pages/menu.php") ?>
                <?php include_once("pages/functions.php") ?>
            </nav>
        </div>
        <div class="row">
            <section class="col-sm-12 col-md-12 col-lg-12">
                <?
                if (isset($_GET["page"])) {
                    $page = $_GET["page"];
                    if ($page == 1) include_once("pages/home.php");
                    if ($page == 2) include_once("pages/upload.php");
                    if ($page == 3) include_once("pages/gallery.php");
                    if ($page == 4) include_once("pages/registration.php");
                } else {
                    include_once("pages/home.php");
                }
                ?>
            </section>
        </div>
    </div>
    <!-- 
    <form method="post">
        <button name="getFromSesbtn">получить из сессии</button>
    </form> -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

<?

if (isset($_POST["loginbtn"])) {
    LogInAccount();
}
if (isset($_POST["logoutbtn"])) {
    LogOut();
}
// if (isset($_POST["getFromSesbtn"])) {
//     $logn = $_SESSION["registered-user"];
//     echo "<div>Из сессии получено: $logn</div>";
// }

?>


</html>