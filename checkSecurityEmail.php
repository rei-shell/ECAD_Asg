<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 

//Check if user has logged in
if (! isset($_POST["email"])) { // Check if email-address has entered
	// redirect to login page if no email was entered
	header ("Location: forgotPassword.php");
	exit;
}
// Read the email address from the input
$email = $_POST["email"];

//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Query to show all the past feedback by other users
$qry = "SELECT * FROM Shopper WHERE email=?";

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
?>
    <h2>Password Security Retrieval</h2>
    <p>There are 2 steps into retrieving your password. <br>You are at the first step. <br>Please enter your email to load your security questions</p>
    <p>Your security question is:</br><?php echo $pwdQn?></p>
    <form method="post" action="passwordRetrieval.php">
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="inputAnswer">Answer:</label>
            <div class="col-sm-9">
                <input class="form-control" name="inputAnswer" id="inputAnswer" type="text" required />
                <input type="hidden" name="answer" id="answer" type="text" value="<?php echo$pwdAnswer?>"/>
                <input type="hidden" name="pwd" id="pwd" type="text" value="<?php echo$pwd?>"/>
                <input type="hidden" name="email" id="email" type="text" value="<?php echo$email?>"/>
            </div>
        </div>
        <!-- 4th row - Submit button-->
        <div class="mb-3 row">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary" name="submitbutton" type="submit">submit</button>
            </div>
        </div>
    </form>
<?php
}else{
    echo "Invalid email-address";
}
$conn->close();
?>
