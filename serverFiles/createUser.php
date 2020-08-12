<?php
	include 'session.php';

	if( $_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) {
		$_SESSION['expired'] = true;
    	header('Location: logout.php'); 
	} else{ 
    	$_SESSION['last_activity'] = time(); 
	}

	if(isset($_SESSION["userRole"]) && $_SESSION["userRole"] != "admin"){
        header("location: home.php");
        exit;
    }
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styleSheet.css">
</head>
<body>
        <div id="main">
            <h1>Create User</h1>
            <form action = "createUser.php" method = "POST">
                First Name <input type = "text" name = "firstName" autocomplete="off" maxlength="45" required><br>
                Last Name <input type = "text" name = "lastName" autocomplete="off" maxlength="45" required><br>
                Username <input type = "text" name = "username" autocomplete="off" maxlength="45" pattern="[a-zA-Z0-9]{1,45}" title="Username can only consist of letters and numbers." required>
                <?php
                    if(isset($_SESSION["usernameError"])){
                        $usernameError = $_SESSION["usernameError"];
                        echo "<span>$usernameError</span>";
                    }
                ?><br>
                Password <input type = "password" name = "password" maxlength="128" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required><br>
                Role: <select name="role">
						<option value="user">User</option>
						<option value="admin">Admin</option>
 	 			</select>
                <input type = "Submit" name = "submit"><br> 
                <?php  
			        if(isset($_SESSION["error"])){
				        $error = $_SESSION["error"];
				        echo "<span>$error</span><br>";
					}
        		?>
            </form>
        </div>
</body>
</html>

<?php  
	unset($_SESSION["error"]);
	unset($_SESSION["usernameError"]);

    if(isset($_POST['submit'])){
    	include 'dbconnect.php';

    	$stmt = $con->prepare("select * from user WHERE username = ?");
        $stmt->bind_param("s",$_POST['username']);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result-> num_rows > 0){
			$_SESSION["usernameError"] = "User already exists";
			header("location: createUser.php");
		}
		else{
			$stmt = $con->prepare("INSERT INTO user(username, password, role, firstName, lastName)
	        VALUES(?, ?, ?, ?, ?)");
	        $stmt->bind_param("sssss", $username, $password, $role, $firstName, $lastName);

	        $firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);
	        $lastName = filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
	        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
	        $password = password_hash(filter_var($_POST['password'], FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
	        $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);

	        if($stmt->execute()){
	        	$_SESSION["error"] = "New user created successfully";
	        }
	        else{
	        	$_SESSION["error"] = "Could not create new user";
	        }

	        header("location: createUser.php");
	    }

    }


?>
<br><br>
<button onclick="window.location.href = 'home.php';">Tickets</button>