<?php
// Detect the current session
session_start();

//Check if user has logged in
if (!isset($_SESSION["email"]) || !isset($_POST["password"])|| !isset($_POST["re-password"])) { 
	header ("Location: forgotPassword.php");
	exit;
}

// Include the Page Layout header
include("header.php");

//Get Post of from reset password input page
$email = $_SESSION["email"];
$pwd = $_POST["password"];
$rePwd = $_POST["re-password"];

//Check if password is match
if($pwd === $rePwd){
    //Include the PHP file that establishes database connection handle: $conn
    include_once("mysql_conn.php");
    //Query to show all the past feedback by other users
    $qry = "UPDATE Shopper SET password =? WHERE email=?";
    //Prepare the prepare and ready the query
    $stmt = $conn->prepare($qry);
    // "sssssss" - 6 string parameters
    $stmt->bind_param("ss", $pwd,$email);
    //Execute it
    $stmt->execute();

    if ($stmt->affected_rows > 0){
        // Clear the email session
        unset($_SESSION["email"]);
        ?>

            <main class="d-flex justify-content-center align-items-center">
                <div class="wrapper">
                    <div class="row mb-4">
                        <img src="https://cdn3d.iconscout.com/3d/premium/thumb/successfully-approve-5331611-4659611.png">
                    </div>
                    <div class="row mb-4">
                        <h6 class="opacity-50">Your password has been reset</h6>
                        <h5>Successfully</h5>
                    </div>
                    <div class="row">
                        <a href="login.php" class="mb-4 d-grid gap-2">                           
                            <button class="btn btn-primary">Back to login</button>
                        </a>
                    </div>
                </div>
            </main>

        <?php
        }
    $stmt->close();
    $conn->close();
}
else{
    header ("Location: resetPassword.php?errorMsg=Password not matched");
	exit;
}
include("footer.php"); 
?>
<style>
main{
    height:100vh;
}
.wrapper img{
    width:180px;
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