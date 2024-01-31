<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 

//Check if user has logged in
if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Query to show all the past feedback by other users
$qry = "SELECT * FROM feedback";
$result = $conn->query($qry);

while($row=$result->fetch_array()){
    echo "Subject: ".$row["Subject"]."</br>";
    echo "Content: ".$row["Content"]."</br>";
    echo $row["Rank"]." Out of 5"."</br>";
    echo "Post created on: ".$row["DateTimeCreated"];
}

$conn->close();
?>