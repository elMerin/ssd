<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: home.php");
        exit;
    }

    include("captcha/simple-php-captcha.php");
    $_SESSION['captcha'] = simple_php_captcha();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styleSheet.css">
</head>
<body>
        <div id="main" align="center">
            <h1>Login</h1>
            <form action = "loginCheck.php" method = "POST">
                Username <input type = "text" name = "username" autocomplete="off" maxlength="128" required><br>
                Password <input type = "password" name = "password" maxlength="128" required><br><br>
                <?php
                    echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';
                ?><br><br>
                Captcha Code <input type = "text" name = "captcha" autocomplete="off" maxlength="128" required><br><br>
                <input type = "Submit" name = "submit"><br>
                <?php
                    if(isset($_SESSION["error"])){
                        $error = $_SESSION["error"];
                        echo "<span>$error</span>";
                    }
                ?> 
            </form>
        </div>
</body>
</html>

<?php
    unset($_SESSION["error"]);
?>



