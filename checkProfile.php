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
$isUpdated;
//Email is existing in use
if ($result->num_rows > 0) {
    $isUpdated = false;
}
else{
    // Define select SQL statement
    $qry = "UPDATE Shopper SET name=?,birthdate=?,address=?,country=?,phone=?,email=?,password= COALESCE(?,password) WHERE ShopperID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("sssssssi",$name,$dob,$address,$country,$phone,$email,$pwd,$shopperid);
    $stmt->execute();
    $stmt->close();
    $isUpdated = true;
}if($isUpdated==true){
?>
    <main class="d-flex justify-content-center align-items-center">
        <div class="wrapper">
        <div class="row mb-4">
            <img src="https://static-00.iconduck.com/assets.00/success-icon-512x512-qdg1isa0.png">
        </div>
            <div class="row mb-4">
                <h5>Profile updated successfully!</h5> 
            </div>
            <div>
                <a href="profile.php">
                    <button class="btn btn-primary" type="submit">Back to profile</button>
                </a>
            </div>
        </div>
    </main>
<?php
    }else{ ?>
    <main class="d-flex justify-content-center align-items-center">
        <div class="wrapper">
        <div class="row mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/6659/6659895.png">
        </div>
            <div class="row mb-4">
                <h5>Email already in used, change another one.</h5> 
            </div>
            <div>
                <a href="profile.php">
                    <button class="btn btn-primary" type="submit">Back to profile</button>
                </a>
            </div>
        </div>
    </main>
        
<?php
}
?>
<style>
main{
    height:100vh;
}
.wrapper img{
    width:120px;
    margin:auto;
}
.wrapper{
    background-color: rgb(247, 243, 243);
    padding: 40px;
    border-radius: 15px;
    text-align:center;
    width:700px;
}

.wrapper h5{
    font-weight:700;
}
.wrapper a{
    color: black;
}
.wrapper span{
    font-weight:bold;
}
</style>