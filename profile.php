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
// Get the shopperid
$shopperid=$_SESSION["ShopperID"];

//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
//Define a select query to retreive the information for the user.
$qry = "SELECT * FROM Shopper WHERE ShopperID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i",$shopperid); //"i" - integer
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
while ($row = $result->fetch_array()) {
    $name=$row["Name"];
    $email=$row["Email"];
    $dob=$row["BirthDate"];
    $phone=$row["Phone"];
    $address=$row["Address"];
    $country=$row["Country"];
}
?>
<script type="text/javascript">
function validateForm() {
    var email = $("#email").val();
    var name = $("#name").val();
    var phone = $("#phone").val();
    var dob = new Date($("#dob").val());
    // Get the current date
    var currentDate = new Date();

    if (name == "" || name == null){
        alert("Name cannot leave empty")
        return false//Cancel submission
    }else if (email == "" || email == null){
        alert("Invalid email address")
        return false//Cancel submission
    }
    else if (phone.length != 8) {
        alert("Please enter a 8-digit phone number.")
        return false//Cancel submission
    }else if (phone.substr(0,1) != "6" &&
        phone.substr(0,1) != "8" &&
        phone.substr(0,1) != "9" ){
        alert("Phone number in Singapore should start with 6, 8 or 9.");
        return false; //Cancel submission
    }else if (dob == "Invalid Date" || dob > currentDate){
        alert("Invalid date of birth");
        return false//Cancel submission
    }
    // Phone number is valid
    return true; // Allow submission
    alert(phone)
}
</script>

<form action="checkProfile.php" method="post" onsubmit="return validateForm()">
    <!-- 1st row header row -->
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Edit my profile</span>
        </div>
    </div>

    <!-- 2nd row - name -->
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="name">
            Name
        </label>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="name" id="name" value="<?php echo htmlspecialchars($name)?>" require/>
        </div>
    </div>

    <!-- 2nd row - Entry of email address -->
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email">
            Email address:
        </label>
        <div class="col-sm-9">
            <input class="form-control" type="email" name="email" id="email" value="<?php echo htmlspecialchars($email)?>"require/>
        </div>
    </div>

    <!-- 2nd row - dob -->
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="dob">
            Date Of Birth
        </label>
        <div class="col-sm-9">
            <input class="form-control" type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($dob)?>"/>
        </div>
    </div>

    <!-- 2nd row - phone -->
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="phone">
            Phone No
        </label>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($phone)?>"/>
        </div>
    </div>

    <!-- 2nd row - address -->
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="address">
            Address
        </label>
        <div class="col-sm-9">
            <textarea class="form-control" name="address" id="address" rows="4"><?php echo htmlspecialchars($address)?></textarea>
        </div>
    </div>

    
    <!-- 2nd row - country -->
    <div class="mb-3 row">
        <label for="country" class="col-sm-3 col-form-label">Country</label>
        <div class="col-sm-9">
            <input class="form-control" list="countriesOption" name="country" id="country" placeholder="Type to search for a country" value="<?php echo htmlspecialchars($country)?>">
            <datalist id="countriesOption">
            <option value="Singapore">
            <option value="Malaysia">
            <option value="Vietnam">
            <option value="Indonesia">
            <option value="Thailand">
            </datalist>
        </div>
    </div>
    
    <!-- 3rd row - Entry of password -->
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="password">
           New Password
        </label>
        <div class="col-sm-9">
            <input class="form-control" type="password" name="password" id="password"/>
        </div>
    </div>

    <!-- 3rd row - re-Entry of password -->
        <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="re-password">
            Re-enter New Password
        </label>
        <div class="col-sm-9">
            <input class="form-control" type="password" name="re-password" id="re-password"/>
        </div>
    </div>

    <!-- 4th row - Login button-->
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </div>

</form>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>