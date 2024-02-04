<?php
// Detect the current session
session_start();

//Check if user has logged in
if (!isset($_SESSION["email"])) { 
	header ("Location: forgotPassword.php");
	exit;
}

// Include the Page Layout header
include("header.php");
?>
<main class="d-flex justify-content-center align-items-center">
    <div class="wrapper">
        <form method="post" action="checkResetPwd.php">
            <div class="row mb-4">
                <h5>Reset your password</h5> 
            </div>    

            <div class="row mb-4 row">
                <div class="col-lg-12">
                    <input class="form-control mb-3" name="password"  type="password" placeholder="Password" autofocus required />
                    <input class="form-control" name="re-password"  type="password" placeholder="Re-password" required />
                </div>
            </div>
            <!-- 4th row - Submit button-->
            <div class="mb-4 d-grid gap-2">
                <button class="btn btn-primary" name="submitbutton" type="submit">submit</button>
            </div>
        </form>
        <?php
    if(isset($_GET["errorMsg"])) {?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_GET["errorMsg"]; ?>
        </div>
    <?php } ?>
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