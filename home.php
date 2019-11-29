<?php
	include 'session.php';
?>



<link rel="stylesheet" type="text/css" href="styleSheet.css">


<h1>Tickets</h1>




<table cellspacing="10">
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
	</tr>
	<?php 
	unset($_SESSION['ticketID']);

	if(isset($_POST['submit'])){
		$_SESSION['ticketID'] = $_POST['ticketID'];
		header("location: editTicket.php");
	}



	include 'dbconnect.php';

	$sql = mysqli_query($con, "SELECT id, finderID, assigneeID, projectID, description, creationDate, type, priority, status from ticket");

	if ($sql-> num_rows > 0){
		while ($row = $sql-> fetch_assoc()){


			echo "<tr><td><form action = 'home.php' method = 'POST'><input type='hidden' name='ticketID' value=" . htmlspecialchars($row["id"]) . "><br><input type = 'Submit' name = 'submit' value = 'Edit'></form></td><td>". get_username($row["finderID"]) ."</td><td>". 
			get_username($row["assigneeID"]) . "</td><td>" . htmlspecialchars($row["projectID"]) .  
				"</td><td>" . htmlspecialchars($row["description"]) .  "</td><td>" . htmlspecialchars($row["creationDate"]) .  "</td><td>" . htmlspecialchars($row["type"]) .  "</td><td>" . htmlspecialchars($row["priority"]) .  "</td><td>" . htmlspecialchars($row["status"]) . "</td><tr>";
		}
		echo "</table>";
	}
	else{
		echo "<tr><td>No tickets</td></tr>";
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
