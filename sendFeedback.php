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

<main class="d-flex justify-content-center align-items-center">
    <div class="wrapper">
        <form name="sendfeedback" method="post" onsubmit="return validateForm()">
        <h5>Give feedback</h5>
        <span>How would you rate your experience?</span>
            <div class="row mt-4">
                <div class="col-lg-12 mb-2">
                    <div class="row justify-content-between">
                        <div class="col-md-2">
                            <button type="button" class="btn btn-lights" value="1">1</button>
                            <span>Very poor</span>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-lights" value="2">2</button>
                            <span>Poor</span>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-lights" value="3">3</button>
                            <span>Ok</span>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-lights" value="4">4</button>
                            <span>Good</span>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-lights" value="5">5</button>
                            <span>Excellent</span>
                        </div>
                    </div>
                    <input class="form-control"  type="hidden" name="rank" id="rank" type="text" required />
                </div>
                <div class="col-lg-12 mb-2">
                    <label class="col-form-label" for="subject">Subject:</label>
                    <input class="form-control" name="subject" id="subject" type="text" required />
                </div>
                <div class="col-lg-12 mb-2">
                    <label class="col-form-label" for="content">Content:</label>
                    <textarea class="form-control" name="content" id="content" type="text" rows="3" required></textarea>
                </div>
            </div>
            <div class="mt-4 d-grid gap-2">
                <button class="btn btn-primary btn-lg" name="submitbutton" type="submit">submit</button>
            </div>
        </form>
        <div class="alert alert-danger mt-4 errorMsg" role="alert">
        </div>
    </div>
</main>
<?php
// Include the Page Layout footer
include("footer.php"); 
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
  <script src="js/jquery-3.3.1.min.js"></script>
<script>
    // Use jQuery to handle button clicks
    $(document).ready(function () {
        $('.errorMsg').hide();
            // Attach a click event handler to all buttons with class "btn-light"
            $('.btn-lights').click(function () {
                $('.btn-lights').removeClass('active');
                $(this).addClass('active');
                // Retrieve the value of the clicked button
                var buttonValue = $(this).val();
                $('#rank').val(buttonValue);
                // Proceed with any other actions based on the button value
            });
        });
    
        function validateForm() {
        if(document.sendfeedback.rank.value.trim()===""){
            $('.errorMsg').text("Please choose a rating.")
            $('.errorMsg').show();
            return false; //Cancel submission
        }
        alert("Your review is submitted!")
        return true;
    }
</script>
<style>
main{
    padding-top:100px;
    padding-bottom:50px;
}
.wrapper img{
    width:120px;
    margin:auto;
}
.wrapper{
    background-color: rgb(247, 243, 243);
    padding: 40px;
    border-radius: 15px;
    width:700px;
}

.wrapper h5{
    font-weight:700;
}
.wrapper a{
    color: black;
}
.wrapper span{
    font-weight:500;
    opacity:50%;
}

.btn-lights{
    width:100%;
    height:60px;
    font-weight:bold;
    font-size:large;
    background-color:#E8E8E8;
}
.btn-lights:hover{
    background-color:#f89cab;
    color:white;
}

.btn.btn-lights.active{
    background-color:#f89cab;
    color:white;
}

.col-md-2{
    text-align:center
}

</style>

<!--<div class="mb-3 row">
                <div class="col-sm-9">
                    <span class="page-title">Give feedback</span>
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
            4th row - Login button
            <div class="mb-3 row">
                <div class="col-sm-9 offset-sm-3">
                    <button class="btn btn-primary" name="submitbutton" type="submit">submit</button>
                </div>
            </div>-->