<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>

<h2>Password Security Retrieval</h2>
<p>There are 2 steps into retrieving your password. You are at the first step. Please enter your email to load your security questions</p>

<form name="forgotPassword" action="checkSecurityEmail.php?" method="post">

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email">Email Address:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" type="text" required />
        </div>
    </div>
    <!-- 4th row - Submit button-->
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary" name="submitbutton" type="submit">submit</button>
        </div>
    </div>

</form>