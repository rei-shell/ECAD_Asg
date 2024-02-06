<?php
// Detect the curent session
session_start();
// Include the pay layout header
include("header.php");

// Error from login fail
if (isset($_GET['errorMsg'])) {
    $errorMsg = $_GET['errorMsg'];
} else {
    $errorMsg=null;
}

//Error when email not founded
if (isset($_GET['errorEmail'])) {
    $errorEmail = $_GET['errorEmail'];
} else {
    $errorEmail=null;
}

?>
<!-- Create a cenrally located container -->
<main>
    <section>
        <div class="container" id="main">

            <!--Sign up section-->
            <div class="sign-up">
                <form name="register" action="addMember.php" method="post" onsubmit="return validateSignUpForm()">
                    <div class="title">
                        <h1>Get started</h1>
                        <p>Set up an account to become our member!</p>
                    </div>
                    <div class="row input-detail">
                        <div class="col-lg-6">
                            <input class="form-control" type="text" name="name" placeholder="Name" required>
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-lg-6">
                            <input class="form-control" type="email" name="email" placeholder="Email" required>
                            <span class="text-danger"><?php echo $errorEmail?></span>
                        </div>
                        <div class="col-lg-4">
                            <input  class ="form-control" type="text" name="phone" placeholder="Phone">
                            <span  class="text-danger"></span>
                        </div>
                        <div class="col-lg-4">
                            <input class="dob form-control" type="date" name="dob" id="dob" placeholder="BirthDate">
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-lg-4">
                            <select class="form-select" type="text" name="country">
                                <option selected>Select Country</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Singapore">Malaysia</option>
                                <option value="Singapore">Indonesia</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <input class ="form-control" type="text" name="address" placeholder="Address">
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-lg-6">
                            <input class ="form-control" type="password" name="password" placeholder="Password" required>
                            <span  class="text-danger"></span>
                        </div>
                        <div class="col-lg-6">
                            <input class ="form-control" type="password" name="password2" placeholder="Retype Password" required>
                            <span  class="text-danger"></span>
                        </div>
                        <div class="col-lg-6">
                            <input class ="form-control" type="text" name="pwdQuestion" placeholder="Password Question">
                            <span  class="text-danger"></span>
                        </div>
                        <div class="col-lg-6">
                            <input class ="form-control" type="text" name="pwdAnswer" placeholder="Password Answer">
                            <span  class="text-danger"></span>
                        </div>
                    </div>
                    <button type="submit" name="signUpBtn" class="btn btn-primary btn-lg" id="signUpBtn">Sign Up</button>
                </form>
            </div>

            <!--Sign in section-->
            <div class="sign-in">
                <form name="signin" action="checkLogin.php" method="post">
                    <div class="title">
                        <h1>Sign in</h1>
                        <p>Welcome back!</p>
                    </div>
                    <input class="form-control" type="email" name="email" placeholder="Email" autofocus required>  
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                    <span style="color:red;" id="loginMsg"><?php echo $errorMsg?></span>
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    <a href="forgotPassword.php">Forgot password? Click here.</a>
                </form>
            </div>

            <div class="overlay-container">
                <div class="overlay">

                    <div class="overlay-left">
                        <h1>It's nice to have you back</h1>
                        <p>To stay connected, simply log in with your personal information.</p>
                        <button type="submit" class="btn btn-dark btn-lg ms-2" id="signIn">Sign In</button>
                    </div>

                    <div class="overlay-right">
                        <h1>No account?</h1>
                        <p>Create an account and start your jounery with us</p>
                        <button type="submit" class="btn btn-dark btn-lg ms-2" id="signUp">Create account</button>
                    </div>

                </div>
            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img class="mb-3 mt-3" src="https://cdn-icons-png.flaticon.com/512/725/725105.png"/>
                        <h2 class="text-success mb-3">Success</h2>
                        <p class="mb-3">Woohoo, you have sign up successfully!</p>
                        <button type="button" class="btn btn-success mb-3" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
    </main>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/loginAndRegister.js"></script>
<?php
//Include the page layout footer
include("footer.php");
?>

<!--js to validate sign up form-->
<script type="text/javascript">
function validateSignUpForm()
{
    // To Do 1 - Check if password matched
    if (document.register.password.value != document.register.password2.value){
        $('input[name="password"]').next('span').text('Passwords not matched!');
        return false; // Cancel submission
    }
	// To Do 2 - Check if telephone number entered correctly
	// Singapore telephone number consists of 8 digits,start with 6, 8 or 9
    if(document.register.phone.value != ""){
        var str = document.register.phone.value;
        if(str.length != 8){        
            $('input[name="phone"]').next('span').text('Please enter a 8-digit phone number.');
            return false
        }
        else if (str.substr(0,1) != "6" &&
                 str.substr(0,1) != "8" &&
                 str.substr(0,1) != "9" ){                    
            $('input[name="phone"]').next('span').text('Phone number in Singapore should start with 6, 8 or 9.');
            return false; //Cancel submission
        }
    }
    /*// get dob from the input
    var dob = new Date($("#dob").val());
    // Get the current date
    var currentDate = new Date();
    // To Do 3 - check if date of birth is valid
    if (dob == "Invalid Date" || dob > currentDate){
        $('input[name="dob"]').next('span').text('Invalid date');
        return false//Cancel submission
    }*/

    return true;  // No error found
}
if($('input[name="email"]').next('span').text() == 'Email aready Exist'){
        const main = $('#main');
        main.addClass('right-panel-active');
    }
</script>

