<?php
// Detect the current session
session_start();

if(!isset($_POST["email"])){
	header("Location: login.php");
	exit;
}

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
// Define select SQL statement
$qry = "SELECT Name, ShopperID, Email, Password from shopper";
$result = $conn->query($qry);

$isfound = false;

while ($row = $result->fetch_array()){
	// To Do 1 (Practical 2): Validate login credentials with database
	if (($email == $row['Email']) && ($pwd==$row['Password'])) {
		// Save user's info in session variables
		$_SESSION["ShopperName"] = $row['Name'];
		$_SESSION["ShopperID"] = $row['ShopperID'];
		
		// To Do 2 (Practical 4): Get active shopping cart
		$shopperID = $_SESSION["ShopperID"];
		$qry = "SELECT * FROM ShopCart WHERE ShopperID=? AND OrderPlaced=0";
		$stmt =  $conn->prepare($qry);
		$stmt->bind_param("i",$shopperID);
		$stmt -> execute();
		$result =  $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$shopcartID = $row["ShopCartID"];
			$_SESSION["Cart"] = $shopcartID;
		};
		$stmt -> close();

		$qry = "SELECT count(*) as 'NumCartItem' FROM ShopCartItem where ShopCartID=?";
		$stmt =  $conn->prepare($qry);
		$stmt->bind_param("i",$_SESSION["Cart"]);
		$stmt -> execute();
		$result = $stmt->get_result();  // Get the result set
		if ($result) {
			$row = $result->fetch_assoc();  // Fetch the first row of the result set
			$_SESSION["NumCartItem"] = $row['NumCartItem'];  // Set the session variable to the count
		} 
		// Redirect to home page
		header("Location: index.php");
		exit;
	}
}

if ($isfound == false) {
	header("Location: login.php?errorMsg=Invalid Login Credentials");
	exit;
}
	
// Include the Page Layout footer
include("footer.php");
?>