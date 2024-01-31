<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 


// Read the answer from the input
$inputAnswer = $_POST["inputAnswer"];
$answer = $_POST["answer"];
$pwd = $_POST["pwd"];
$email = $_POST["email"];

//Check if user has logged in
if (! isset($_POST["inputAnswer"])) { 
	header ("Location: forgotPassword.php");
	exit;
}

if ($inputAnswer === $answer){
    echo $pwd;
}else{
    echo "Wrong Answer to security Question. Please try again.";
    echo $email;
?>
    <form action="checkSecurityEmail.php?" method="post">
        <input type="hidden" name="email" id="email" type="text" value="<?php echo$email?>"/>
        <button class="btn btn-primary" name="submitbutton" type="submit">Return to previous page.</button>
    </form>
<?php
}

?>