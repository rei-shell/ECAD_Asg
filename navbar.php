<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<a href='login.php'><span>Login</span></a>";
$content3 = "Gifted Treasure<br/>";
$profile = "";
$cart = "";
$feedback="";

if(isset($_SESSION["ShopperName"])) { 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
	$content1 = "Welcome back, <b>$_SESSION[ShopperName]</b>";
    $content2 = "<a href='logout.php'>Logout</a>";
	//To Do 2 (Practical 4) - 
    //Display number of item in cart
    if (isset($_SESSION["NumCartItem"])){
        $cart.='<a href="shoppingCart.php"><i class="fa fa-shopping-bag position-relative" aria-hidden="true">  
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            '.$_SESSION["NumCartItem"].'
        <span class="visually-hidden">unread messages</span></i></a>';
    }
    $profile.="<a href='profile.php'><i class='fa fa-user' aria-hidden='true'></i></a>";
    $feedback .= "<li class='nav-item " . (basename($_SERVER['PHP_SELF']) == 'sendFeedback.php' ? 'active' : '') . "'>
    <a class='nav-link' href='sendFeedback.php'>
    Feedback
    </a>
</li>";

}
?>

<header class="header_section">
    <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.php">
            <span>
            <?php echo $content3; ?>
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'category.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="category.php">
                Shop
                </a>
            </li>
            <?php echo $feedback?>
            </ul>
            <div class="user_option">
                <span class="mr-4"><?php echo $content1; ?></span>
                <a href="search.php">
                    <button class="btn nav_search-btn"  type="submit">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </a>
                <?php echo $cart?>
                <?php echo $profile?>
                <?php echo $content2; ?>
            </div>
        </div>
    </nav>
</header>
    <!-- end header section -->

