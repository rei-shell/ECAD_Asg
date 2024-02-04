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

<main class="d-flex justify-content-center align-items-center">
    <div class="wrapper container">
        <form name="checkProfile" action="checkProfile.php" method="post" onsubmit="return validateForm()" style="padding-left:50px; padding-right:50px;">
            <!-- 1st row header row -->
            <div class="mb-3 row">
                <div class="col-sm-9">
                    <span class="page-title">Edit my profile</span>
                </div>
            </div>
            <div class="row">

                <!-- 2nd row - name -->
                <div class="mb-3 col-lg-6">
                    <label class="col-form-label" for="name">Name</label>
                    <input class="form-control" type="text" name="name" id="name" value="<?php echo htmlspecialchars($name)?>" require/>
                </div>

                <!-- 2nd row - Entry of email address -->
                <div class="mb-3 col-lg-6">
                    <label class="col-form-label" for="email">Email address:</label>
                    <input class="form-control" type="email" name="email" id="email" value="<?php echo htmlspecialchars($email)?>"require/>
                </div>

                <!-- 2nd row - dob -->
                <div class="mb-3 col-lg-4">
                    <label class="col-form-label" for="dob">Date Of Birth</label>
                    <input class="form-control" type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($dob)?>"/>
                </div>

                <!-- 2nd row - phone -->
                <div class="mb-3 col-lg-4">
                    <label class="col-form-label" for="phone">Phone No</label>
                    <input class="form-control" type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($phone)?>"/>
                </div>
            
                <!-- 2nd row - country -->
                <div class="mb-3 col-lg-4">
                    <label for="country" class="col-form-label">Country</label>
                    <input class="form-control" list="countriesOption" name="country" id="country" placeholder="Type to search for a country" value="<?php echo htmlspecialchars($country)?>">
                    <datalist id="countriesOption">
                    <option value="Singapore">
                    <option value="Malaysia">
                    <option value="Vietnam">
                    <option value="Indonesia">
                    <option value="Thailand">
                    </datalist>
                </div>

                <!-- 2nd row - address -->
                <div class="mb-3 col-lg-12">
                    <label class="col-form-label" for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" rows="2"><?php echo htmlspecialchars($address)?></textarea>
                </div>
                
                <!-- 3rd row - Entry of password -->
                <div class="mb-3">
                    <label class="col-form-label" for="password">New Password</label>
                    <input class="form-control" type="password" name="password" id="password"/>
                </div>

                <!-- 3rd row - re-Entry of password -->
                <div class="mb-4">
                    <label class="col-form-label" for="re-password">Re-enter New Password</label>
                    <input class="form-control" type="password" name="re-password" id="re-password"/>
                </div>

                <!-- 4th row - Login button-->
                <div class="mb-3 mt-4 d-grid gap-2">
                    <button class="btn btn-primary btn-lg" type="submit">Save</button>
                </div>                          
            </div>

        </form>
    </div>
</main>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>
<script type="text/javascript">
function validateForm() {

    //Check name
    if(document.checkProfile.name.value.trim()===""){
        alert("Name cannot leave empty");
        return false; //Cancel submission
    }

    //Check email
    if(document.checkProfile.email.value.trim()===""){
        alert("Email cannot leave empty");
        return false; //Cancel submission
    }

    //Check dob
    if(document.checkProfile.dob.value !== null){
        if(new Date(document.checkProfile.dob.value) > new Date())
        {
            alert("Invalid DOB");
            return false; //Cancel submission
        }
    }
    //Check phone
    if(document.checkProfile.phone.value.trim() != ""){
        var str = document.checkProfile.phone.value.trim();
        if(str.length != 8){        
            alert('Please enter a 8-digit phone number.');
            return false
        }
        else if (str.substr(0,1) != "6" &&
                 str.substr(0,1) != "8" &&
                 str.substr(0,1) != "9" ){                    
            alert('Phone number in Singapore should start with 6, 8 or 9.');
            return false; //Cancel submission
        }
    }

    //

    return true; //no error in the form
}
</script>
<style>
main{
    background-color: rgb(247, 243, 243);
}
.wrapper{
    padding: 40px;
    border-radius: 15px;
}

</style>