<?php
// Detect the current session
session_start();

//Check if user has logged in
if (!isset($_POST["email"]) && !isset($_SESSION["email"])) { // Check if email-address has entered
	// redirect to login page if no email was entered
	header ("Location: forgotPassword.php");
	exit;
}
// Read the email address from the input
if (!isset($_POST["email"])){
    $email = $_SESSION["email"];
}else{
    $email = $_POST["email"];
}

//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Query to show all the past feedback by other users
$qry = "SELECT * FROM `shopper` WHERE email=? AND (PwdQuestion IS NOT NULL && PwdAnswer IS NOT NULL)";

//Prepare the prepare and ready the query
$stmt = $conn->prepare($qry);
// "sssssss" - 6 string parameters
$stmt->bind_param("s", $email);
//Execute it
$stmt->execute();
//to retrieve the result set.
$result = $stmt->get_result();
$stmt->close();
//check if email is found.
if ($result->num_rows>0){
    while ($row = $result->fetch_array()) {
        $pwdQn =  $row["PwdQuestion"];
        $pwdAnswer = $row["PwdAnswer"];
        $pwd = $row["Password"];
    }
    //create session for the email when found
    $_SESSION["email"] = $email;
    // Include the Page Layout header
include("header.php"); 
?>
<main class="d-flex justify-content-center align-items-center">
    <div class="wrapper">
        <div class="row mb-2">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/lock-2872329-2389488.png?f=webp">
        </div>
        
        <div class="row mb-4">
            <h5>Password Security Retrieval</h5>   
        </div>

        <div class="row mb-4">            
            <div class="mb-4">
                <p class="fw-bold">Your security question is:</p>
                <p><?php echo $pwdQn?></p>
            </div>
            <form method="post" action="passwordRetrieval.php">
                <div class="mb-4 row">
                    <div class="col-lg-12">
                        <input class="form-control" name="inputAnswer" id="inputAnswer" type="text" placeholder="Answer" autofocus required />
                        <input type="hidden" name="answer" id="answer" type="text" value="<?php echo$pwdAnswer?>"/>
                        <input type="hidden" name="pwd" id="pwd" type="text" value="<?php echo$pwd?>"/>
                        <input type="hidden" name="email" id="email" type="text" value="<?php echo$email?>"/>
                    </div>
                </div>
                <!-- 4th row - Submit button-->
                <div class="mb-4 d-grid gap-2">
                    <button class="btn btn-primary" name="submitbutton" type="submit">submit</button>
                </div>
            </form>
        </div>

        <?php
        if(isset($_GET["errorMsg"])) {?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_GET["errorMsg"]; ?>
            </div>
        <?php } ?>

    </div>
</main>
<?php
$conn->close();
}else{
    header("Location: forgotPassword.php?errorMsg=Invalid Email-address");
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
a{
    color: black;
}
</style>
