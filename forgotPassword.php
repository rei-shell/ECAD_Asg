<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
if (isset($_GET['errorMsg'])) {
    $errorMsg = $_GET['errorMsg'];
    // Now $errorMsg contains the value 'invalid'
    // You can use it as needed, for example, to display an error message.
} else {
    // No error message provided in the query string
    $errorMsg=null;
}
?>

<main class="d-flex justify-content-center align-items-center">
    <div class="wrapper">
        <div class="row mb-2">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/forgot-security-password-4571936-3805757.png?f=webp">
        </div>
        <div class="row mb-4">
            <h5>Forgot Password?</h5>
            <p>No worries, we'll send you reset instructions.</p>
        </div>
        <div class="row mb-4">
            <form name="forgotPassword" action="checkSecurityEmail.php?" method="post">
                <div class="mb-4">
                    <input class="form-control" name="email" id="email" type="text" placeholder="Email" autofocus required />
                </div>
                    <!-- 4th row - Submit button-->
                <div class="mb-4 d-grid gap-2">
                    <button class="btn btn-primary" name="submitbutton" type="submit">submit</button>
                </div>
                <a href="login.php"><i class="fas fa-long-arrow-alt-left"></i> Back to log in</a>
            </form>
        </div>        
            <?php if (isset($_GET['errorMsg'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMsg?>
                </div>
            <?php }?>
    </div>
</main>
<?php
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
    width:700px;
    text-align:center;
}
.wrapper h5{
    font-weight:700;
}
a{
    color: black;
}
</style>