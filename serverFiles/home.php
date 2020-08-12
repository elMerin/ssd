<?php
	include 'session.php';

	$_SESSION['last_activity'] = time(); 
	$_SESSION['expire_time'] = 10*60; 

?>



<link rel="stylesheet" type="text/css" href="styleSheet.css">


<h1>Tickets</h1>




	<?php 
	unset($_SESSION['ticketID']);

	if(isset($_POST['submit'])){
		$_SESSION['ticketID'] = $_POST['ticketID'];
		header("location: editTicket.php");
	}



	include 'dbconnect.php';

	$tickets = false;
	$sql = mysqli_query($con, "SELECT id, finderID, assigneeID, projectID, description, creationDate, type, priority, status from ticket where status = 'open'");

	if ($sql-> num_rows > 0){
		$tickets = true;
		echo "<h3>Open tickets:</h3><table cellspacing='10'>
			<tr>
				<th></th>
				<th>Finder</th>
				<th>Assignee</th>
				<th>Project ID</th>
				<th>Description</th>
				<th>Creation Date</th>
				<th>Type</th>
				<th>Priority</th>
				<th>Status</th>
		</tr>";
		while ($row = $sql-> fetch_assoc()){
			echo "<tr><td><form action = 'home.php' method = 'POST'><input type='hidden' name='ticketID' value=" . htmlspecialchars($row["id"]) . "><br><input type = 'Submit' name = 'submit' value = 'Edit'></form></td><td>". get_username($row["finderID"]) ."</td><td>". 
			get_username($row["assigneeID"]) . "</td><td>" . htmlspecialchars($row["projectID"]) .  
				"</td><td>" . htmlspecialchars($row["description"]) .  "</td><td>" . htmlspecialchars($row["creationDate"]) .  "</td><td>" . htmlspecialchars($row["type"]) .  "</td><td>" . htmlspecialchars($row["priority"]) .  "</td><td>" . htmlspecialchars($row["status"]) . "</td><tr>";
		}
		echo '</table>';
	}
	else{
		
	}

	$sql = mysqli_query($con, "SELECT id, finderID, assigneeID, projectID, description, creationDate, type, priority, status from ticket where status = 'resolved'");

	if ($sql-> num_rows > 0){
		$tickets = true;
		echo "<h3>Resolved tickets:</h3><table cellspacing='10'>
			<tr>
				<th></th>
				<th>Finder</th>
				<th>Assignee</th>
				<th>Project ID</th>
				<th>Description</th>
				<th>Creation Date</th>
				<th>Type</th>
				<th>Priority</th>
				<th>Status</th>
		</tr>"; 
		while ($row = $sql-> fetch_assoc()){
			echo "<tr><td><form action = 'home.php' method = 'POST'><input type='hidden' name='ticketID' value=" . htmlspecialchars($row["id"]) . "><br><input type = 'Submit' name = 'submit' value = 'Edit'></form></td><td>". get_username($row["finderID"]) ."</td><td>". 
			get_username($row["assigneeID"]) . "</td><td>" . htmlspecialchars($row["projectID"]) .  
				"</td><td>" . htmlspecialchars($row["description"]) .  "</td><td>" . htmlspecialchars($row["creationDate"]) .  "</td><td>" . htmlspecialchars($row["type"]) .  "</td><td>" . htmlspecialchars($row["priority"]) .  "</td><td>" . htmlspecialchars($row["status"]) . "</td><tr>";
		}
		echo '</table>';
	}
	else{

	}

	$sql = mysqli_query($con, "SELECT id, finderID, assigneeID, projectID, description, creationDate, type, priority, status from ticket where status = 'closed'");

	if ($sql-> num_rows > 0){
		$tickets = true;
		echo "<h3>Closed tickets:</h3><table cellspacing='10'>
		<tr>
			<th></th>
			<th>Finder</th>
			<th>Assignee</th>
			<th>Project ID</th>
			<th>Description</th>
			<th>Creation Date</th>
			<th>Type</th>
			<th>Priority</th>
			<th>Status</th>
		</tr>"; 
		while ($row = $sql-> fetch_assoc()){
			echo "<td><form action = 'home.php' method = 'POST'><input type='hidden' name='ticketID' value=" . htmlspecialchars($row["id"]) . "><br><input type = 'Submit' name = 'submit' value = 'Edit'></form></td><td>". get_username($row["finderID"]) ."</td><td>". 
			get_username($row["assigneeID"]) . "</td><td>" . htmlspecialchars($row["projectID"]) .  
				"</td><td>" . htmlspecialchars($row["description"]) .  "</td><td>" . htmlspecialchars($row["creationDate"]) .  "</td><td>" . htmlspecialchars($row["type"]) .  "</td><td>" . htmlspecialchars($row["priority"]) .  "</td><td>" . htmlspecialchars($row["status"]) . "</td><tr>";
		}
		echo '</table>';
	}

	if(!$tickets){
		echo '<h3>No tickets</h3>';
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

<br>
<br>

<button onclick="window.location.href = 'createTicket.php';">Create Ticket</button> <br><br>
<?php  

if($_SESSION["userRole"] == "admin"){
	echo "<button onclick='window.location.href = \"createUser.php\";'>Create User</button><br><br><br>";
}




?>
<br><button onclick="window.location.href = 'logout.php';">Log out</button>
