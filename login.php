<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: home.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styleSheet.css">
    <title></title>
</head>
<body>
        <div id="main" align="center">
            <h1>Login</h1>
            <form action = "loginCheck.php" method = "POST">
                Username <input type = "text" name = "username" autocomplete="off" maxlength="128" required><br>
                Password <input type = "password" name = "password" maxlength="128" required><br>
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



