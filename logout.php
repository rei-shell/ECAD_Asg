<?php
//Start or resume user's session
session_start();

// reset session
session_destroy();
// Redirect to home page
header("Location: login.php"); 
exit();
?>