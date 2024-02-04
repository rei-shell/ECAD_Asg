<?php
session_start(); //Detect the current session


// If no form was submitted
if(!isset($_POST['signUpBtn'])){
    header ("Location: login.php");
    exit;
}

// Read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$dob = $_POST["dob"];
$password = $_POST["password"];
$pwdQuestion = $_POST["pwdQuestion"];
$pwdAnswer = $_POST["pwdAnswer"];

//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//check if email exist in the database
$qry = "select * from shopper where email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s",$email,);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
   header ("Location: login.php?errorEmail=Email aready Exist");
   exit;
}else{
    // Define the INSERT SQL statement
    $qry = "INSERT INTO Shopper(Name,BirthDate,Address,Country,Phone,Email,Password,PwdQuestion,PwdAnswer)
            VALUES(?, ?, ?, ?, ?, ?,?,?,?)";
    $stmt = $conn->prepare($qry);
    // "sssssss" - 9 string parameters
    $stmt->bind_param("sssssssss", $name,$dob, $address, $country, $phone, $email, $password,$pwdQuestion,$pwdAnswer);

    if ($stmt->execute()){ //SQL statement executed successfully
        // Retrieve the shopper ID assigned to the new shopper
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry);//Execute the SQL and get the returned result
        while ($row = $result->fetch_array()){
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        // Save the shopper Name in session variable
        $_SESSION["ShopperName"] = $name;
        // Include the Page Layout header
        include("header.php");
        ?>
        <!--Show sign up successful message-->
        <main class="d-flex justify-content-center align-items-center">
            <div class="wrapper">
                <div class="row mb-5">
                    <img src="https://cdn-icons-png.freepik.com/512/5610/5610944.png">
                </div>
                <div class="row mb-5">
                    <h5>Thank you for registering</h5>
                    <h6 class="opacity-50">You have successfully sign up for a new account.</h6>
                </div>
                <div class="row">
                    <a href="index.php" class="d-grid gap-2">                           
                        <button class="btn btn-primary btn-lg">Back to homepage</button>
                    </a>
                </div>
            </div>
        </main>

        <?php
    }
    else { //error message
        $Message = "<h3 style='color:red'> Error in inserting record</h3>";
    }

    // Release the resource allocated for prepared statement
    $stmt->close();
}

// Close database connection
$conn->close();
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