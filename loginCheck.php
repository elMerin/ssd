<?php
    session_start();

    $error = "username/password incorrect";

    include 'dbconnect.php';

    if(isset($_POST['submit'])){

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
                $_SESSION["error"] = $error;
                header("location: login.php");
                
        }
        
        else            
            $_SESSION["error"] = $error;
            header("location: login.php");
            

    }

    function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

?>