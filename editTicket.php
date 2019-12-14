<?php
	include 'session.php';

	if( $_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) {
		$_SESSION['expired'] = true;
    	header('Location: logout.php'); 
	} else{ 
    	$_SESSION['last_activity'] = time(); 
	}
?>


<link rel="stylesheet" type="text/css" href="styleSheet.css">


<h1>Edit ticket</h1>

<table cellspacing="10">
	<tr>
		<th>Finder</th>
		<th>Assignee</th>
		<th>Project ID</th>
		<th>Description</th>
		<th>Creation Date</th>
		<th>Type</th>
		<th>Priority</th>
		<th>Status</th>
	</tr>
	<?php 
	include 'dbconnect.php';
	unset($_SESSION['status']);

	if(isset($_POST['delete'])){
		$stmt = $con->prepare("delete from ticket where id = ?");
        $stmt->bind_param("s",$_SESSION['ticketID']);
        $stmt->execute();
        
        header('location: home.php');
	}

	if(isset($_POST['update'])){
		$stmt = $con->prepare("update ticket set status = ? where id = ?");
        $stmt->bind_param("ss",$_POST['stat'],$_SESSION['ticketID']);
        $stmt->execute();
        
        header('location: editTicket.php');
	}

	if(isset($_SESSION['ticketID'])){

		$stmt = $con->prepare("select id, finderID, assigneeID, projectID, description, creationDate, type, priority, status from ticket where id = ?");
        $stmt->bind_param("s",$_SESSION['ticketID']);

        $stmt->execute();
        $result = $stmt->get_result();

		if($row = $result-> fetch_assoc()){
			$_SESSION['status'] = $row['status'];
			echo "<tr><td>". get_username($row["finderID"]) ."</td><td>". 
			get_username($row["assigneeID"]) . "</td><td>" . htmlspecialchars($row["projectID"]) .  
				"</td><td>" . htmlspecialchars($row["description"]) .  "</td><td>" . htmlspecialchars($row["creationDate"]) .  "</td><td>" . htmlspecialchars($row["type"]) .  "</td><td>" . htmlspecialchars($row["priority"]) .  "</td><td><br><form action='editTicket.php' method='POST'>" . get_status()
				. "</td><td><input type = 'Submit' name = 'update' value = 'Update'></form></td><tr>";
		}
		
	}



	function get_status(){
		include 'dbconnect.php';
		$stmt = $con->prepare("select status from ticket where id = ?");
        $stmt->bind_param("s",$_SESSION['ticketID']);

        $stmt->execute();
        $result = $stmt->get_result();

        if($row = $result-> fetch_assoc()){

			if(htmlspecialchars($row["status"])=='open'){
				return "<select name = 'stat'>
					  <option value='open' selected>open</option>
					  <option value='resolved'>resolved</option>
					  <option value='closed'>closed</option>
				</select>";
			}
			else if (htmlspecialchars($row["status"])=='resolved'){
				return "<select name = 'stat'>
					  <option value='open'>open</option>
					  <option value='resolved' selected>resolved</option>
					  <option value='closed'>closed</option>
				</select>";
			}
			else if(htmlspecialchars($row["status"])=='closed'){
				return "<select name = 'stat'>
					  <option value='open'>open</option>
					  <option value='resolved'>resolved</option>
					  <option value='closed' selected>closed</option>
				</select>";
			}
		}
	}



	function get_username($id) {
		include 'dbconnect.php';
		$sql = mysqli_query($con, "SELECT username from user WHERE id = '$id'");

		if ($sql-> num_rows > 0){
			while ($row = $sql-> fetch_assoc()){
				return $row["username"];
			}
		}
		
    }
	?>
</table>

<?php  

	unset($_SESSION["error"]);

	if(isset($_POST['addComment']) && isset($_SESSION['ticketID'])){
		$stmt = $con->prepare("INSERT INTO comment(ticketID, userID, content, creationDate)
        VALUES(?, ?, ?, CURRENT_TIMESTAMP())");
        $stmt->bind_param("sss", $ticketID, $userID, $content);

        $ticketID = filter_var($_SESSION["ticketID"], FILTER_SANITIZE_NUMBER_INT);
        $userID = filter_var($_SESSION["userID"], FILTER_SANITIZE_NUMBER_INT);
        $content = filter_var($_POST['commentField'], FILTER_SANITIZE_STRING);

        if($stmt->execute()){
        	$_SESSION["error"] = "New comment created successfully";
        }
        else{
        	$_SESSION["error"] = "Could not create new comment";
        }
        
        header("location: editTicket.php");

	}
	if($_SESSION["userRole"] == "admin"){
		echo "<form action = 'editTicket.php' method = 'POST'><input type = 'Submit' name = 'delete' value = 'Delete ticket'></form>";
	}
?>

<h2>Comments</h2><br>

<form action = 'editTicket.php' method = 'POST'>

<input type = "text" name = "commentField" autocomplete="off" maxlength="255" required 
<?php  

	if(isset($_SESSION['status']) && $_SESSION['status'] == 'closed'){
		echo 'disabled';
	}
?>>
<input type = "Submit" name = "addComment" 
<?php  
	if(isset($_SESSION['status']) && $_SESSION['status'] == 'closed'){
		echo 'disabled';
	}
?>><br>
	<?php  
	    if(isset($_SESSION["error"])){
	        $error = $_SESSION["error"];
	        echo "<span>$error</span><br>";
		}
	?>
</form><br>

<?php

	$stmt = $con->prepare("select userID, content, creationDate from comment where ticketID = ?");
    $stmt->bind_param("s",$_SESSION['ticketID']);

    $stmt->execute();
    $result = $stmt->get_result();

	if ($result-> num_rows > 0){
		echo '---------------------------------------------------';
		while ($row = $result-> fetch_assoc()){
			echo '<br> <div> Comment by: ' . htmlspecialchars(get_username($row["userID"])) . '&nbsp;&nbsp;&nbsp;&nbsp;Posted on: ' . htmlspecialchars($row["creationDate"]) . '<br><br>' . htmlspecialchars($row["content"]) . '</div>--------------------------------------------------- <br>';

		}
	}


?>



<br><br>
<button onclick="window.location.href = 'home.php';">Tickets</button>