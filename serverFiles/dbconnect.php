
<?php
    $con = mysqli_connect("localhost","root","","ticketingdb");

    if ($con-> connect_error){
        die("Connection failed");
    }
?>