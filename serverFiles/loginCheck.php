<?php
    session_start();


    include 'dbconnect.php';

    if(isset($_POST['submit'])){

        if($_POST['captcha'] != $_SESSION['captcha']['code']){
            $_SESSION["error"] = "Wrong captcha code";
            header("location: login.php");
            exit;
        }

        $stmt = $con->prepare("select password, id, role from user where BINARY username= BINARY ?");
        $stmt->bind_param("s",$_POST['username']);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($row=$result->fetch_assoc()){

            if(password_verify($_POST['password'], $row['password'])){
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["userID"] = $row['id'];
                $_SESSION["userRole"] = $row['role'];
                $_SESSION["username"] = $row['username'];  
                header("location:home.php");
            }
            else
                $_SESSION["error"] = "username/password incorrect";
                header("location: login.php");

                
        }
        
        else            
            $_SESSION["error"] = "username/password incorrect";
            header("location: login.php");
            

    }


?>