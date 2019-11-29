<?php
	include 'session.php';
?>

<link rel="stylesheet" type="text/css" href="styleSheet.css">

<div id="main">
    <h1>Create new ticket</h1>
    <form method = "POST">
        Assign to: <select name = "assignee">
        <?php 
        	include 'dbconnect.php';
			$sql = mysqli_query($con, "SELECT username FROM user");
			while ($row = $sql->fetch_assoc()){
				echo "<option value=" . htmlspecialchars($row['username']) . ">" . htmlspecialchars($row['username']) . "</option>";
			}
		?>
		</select><br>
        Project ID: <input type = "number" name = "projectID" maxlength="5" required><br>
        Description: <input type = "text" name = "description" maxlength="128" required><br>
        Type: <select name="type">
						<option value="development">Development</option>
						<option value="testing">Testing</option>
						<option value="production">Production</option>
 	 			</select>
 	 	Priority: <select name="priority">
						<option value="low">Low</option>
						<option value="medium">Medium</option>
						<option value="high">High</option>
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

<br>
<br>
<?php

	unset($_SESSION["error"]);

    include 'dbconnect.php';

    if(isset($_POST['submit'])){

    	$stmt = $con->prepare("SELECT id from user WHERE ? = username");
        $stmt->bind_param("s",$assignee);
        $assignee = filter_var($_POST['assignee'], FILTER_SANITIZE_STRING);
        $stmt->execute();
        $result = $stmt->get_result();

    	if ($row=$result->fetch_assoc()){
			$assigneeID = $row['id'];		

			$stmt = $con->prepare("INSERT INTO ticket(finderID, assigneeID, projectID, description, creationDate, type, priority)
	        VALUES(?, ?, ?, ?, CURDATE(), ?, ?)");
	        $stmt->bind_param("ssssss",$finder,$assignee, $projectID, $description, $type, $priority);

	        $finder = filter_var($_SESSION["userID"], FILTER_SANITIZE_NUMBER_INT);
	        $assignee = filter_var($assigneeID, FILTER_SANITIZE_NUMBER_INT);
	        $projectID = filter_var($_POST['projectID'], FILTER_SANITIZE_NUMBER_INT);
	        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
	        $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
	        $priority = filter_var($_POST['priority'], FILTER_SANITIZE_STRING);

	        if($stmt->execute()){
	        	$_SESSION["error"] = "New ticket created successfully";
	        }
	        else{
	        	$_SESSION["error"] = "Could not create new ticket";
	        	//echo 'error preparing statement: ' . $con->error . $_SESSION["userID"];
	        	//exit;
	        }
	        header("location: createTicket.php");
		}

        
    }

    function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

?>  
<button onclick="window.location.href = 'home.php';">Tickets</button>
