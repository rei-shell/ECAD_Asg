<?php
// Detect the current session
session_start();


// Read the answer from the input
$inputAnswer = $_POST["inputAnswer"];
$answer = $_POST["answer"];
$pwd = $_POST["pwd"];

//Check if user has logged in
if (!isset($_POST["inputAnswer"])) { 
	header ("Location: forgotPassword.php");
	exit;
}

if ($inputAnswer === $answer){
    // Include the Page Layout header
    include("header.php"); 
    ?>
    <main class="d-flex justify-content-center align-items-center">
        <div class="wrapper">
        <div class="row mb-2">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/unlock-5199507-4347698.png?f=webp">
        </div>
            <div class="row mb-4">
                <h5>Password Recovery</h5> 
            </div>
            <div class="row mb-4">
                <h6>Your password is:</h6>
                <h6><?php echo $pwd; ?> </h6>
            </div>
            <div class="row mb-4">
                <p>Reset password or direct to login page in <span id="timer"></span> s</p>
            </div>
            <div>
                <form method="post" action="resetPassword.php" class="mb-4 d-grid gap-2">
                    <button class="btn btn-primary" name="resetBtn" type="submit">Reset your password</button>
                </form>
            </div>
        </div>
    </main>
<?php
}else{
    header ("Location: checkSecurityEmail.php?errorMsg=Wrong Answer to security Question. Please try again!");
	exit;
}
include("footer.php"); 
?>
<script>
function countdownTimer(seconds, callback) {
    var timerElement = document.getElementById("timer");

    function updateTimer() {
        timerElement.innerHTML = seconds
        seconds--;

        if (seconds < 0) {
            clearInterval(timerInterval);
            // Execute the callback function
            if (typeof callback === 'function') {
                callback();
            }
        }
    }

    // Initial call to updateTimer
    updateTimer();

    // Update the timer every second
    var timerInterval = setInterval(updateTimer, 1000);
}

// Set the countdown time in seconds
var countdownTime = 10;

// Start the countdown timer with a callback to redirect
countdownTimer(countdownTime, function() {
    // Use JavaScript to redirect after the countdown
    window.location.href = "login.php";
});
</script>
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