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
?>

<form name="sendfeedback" method="post">
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Submit a feedback</span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="subject">Subject:</label>
        <div class="col-sm-9">
            <input class="form-control" name="subject" id="subject" type="text" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="content">Content:</label>
        <div class="col-sm-9">
            <input class="form-control" name="content" id="content" type="text" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="rank">Rank:</label>
        <div class="col-sm-9">
            <input class="form-control" name="rank" id="rank" type="text" required /> (required)
        </div>
    </div>
    <!-- 4th row - Login button-->
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary" name="submitbutton" type="submit">submit</button>
        </div>
    </div>
</form>

<?php
if(isset($_POST['submitbutton'])){
    // Read the data input
    $shopperid = $_SESSION["ShopperID"];
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    $rank = $_POST["rank"];
    $dateTimecreated = date("Y-m-d");

    //Include the PHP file that establishes database connection handle: $conn
    include_once("mysql_conn.php");

    //Query to show all the past feedback by other users
    $qry = "INSERT INTO feedback(ShopperID,Subject,Content,Rank,DateTimeCreated) VALUES(?,?,?,?,?)";
    $stmt = $conn->prepare($qry);
    // "sssssss" - 6 string parameters
    $stmt->bind_param("issis", $shopperid , $subject, $content, $rank, $dateTimecreated);

    if ($stmt->execute()){//SQL statement executed successfully

        echo "Feedback submitted successfully <br/>";
    }
    $conn->close();
}
?>