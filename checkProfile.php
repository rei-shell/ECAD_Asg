<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
// Reading inputs entered in previous page--profile
$name = $_POST["name"];
$email = $_POST["email"];
$dob = $_POST["dob"];
$phone = $_POST["phone"];
$address = $_POST["address"];
$country = $_POST["country"];
$pwd = $_POST["password"];
$repwd = $_POST["re-password"];
// Get the shopperid
$shopperid=$_SESSION["ShopperID"];

//check if password is null else update
if(empty($pwd)){
    $pwd=null;
}
//Check if email already exist & shopperID of the email must not be the current shopper
$qry = "select email from Shopper where email =? and shopperID !=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("si",$email,$shopperid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    echo "<h3 style='color:green'>Email already exist, please change to another one</h3>";
    exit;
}
// Define select SQL statement
$qry = "UPDATE Shopper SET name=?,birthdate=?,address=?,country=?,phone=?,email=?,password= COALESCE(?,password) WHERE ShopperID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("sssssssi",$name,$dob,$address,$country,$phone,$email,$pwd,$shopperid);
$stmt->execute();
$stmt->close();

echo "<h3 style='color:green'>Profile successfully updated!</h3>";
?>