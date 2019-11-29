<?php
	include 'session.php';
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

	if(isset($_SESSION['ticketID'])){

		$stmt = $con->prepare("select id, finderID, assigneeID, projectID, description, creationDate, type, priority, status from ticket where id = ?");
        $stmt->bind_param("s",$_SESSION['ticketID']);

        $stmt->execute();
        $result = $stmt->get_result();

		if($row = $result-> fetch_assoc()){
			echo "<tr><td>". get_username($row["finderID"]) ."</td><td>". 
			get_username($row["assigneeID"]) . "</td><td>" . htmlspecialchars($row["projectID"]) .  
				"</td><td>" . htmlspecialchars($row["description"]) .  "</td><td>" . htmlspecialchars($row["creationDate"]) .  "</td><td>" . htmlspecialchars($row["type"]) .  "</td><td>" . htmlspecialchars($row["priority"]) .  "</td><td>" . htmlspecialchars($row["status"]) . "</td><tr>";
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
        VALUES(?, ?, ?, CURDATE())");
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

?>

<h2>Comments</h2><br>

<form action = 'editTicket.php' method = 'POST'>

<input type = "text" name = "commentField" autocomplete="off" maxlength="255" required>
<input type = "Submit" name = "addComment"><br>
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