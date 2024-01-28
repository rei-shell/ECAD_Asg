<?php
session_start(); //Detect the current session

// Read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$dob = $_POST["dob"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

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
   $Message = "<h3 style='color:red'> Email already exist</h3>";
}else{
    // Define the INSERT SQL statement
    $qry = "INSERT INTO Shopper(Name,BirthDate,Address,Country,Phone,Email,Password)
            VALUES(?, ?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($qry);
    // "sssssss" - 6 string parameters
    $stmt->bind_param("sssssss", $name,$dob, $address, $country, $phone, $email, $password);

    if ($stmt->execute()){ //SQL statement executed successfully
        // Retrieve the shopper ID assigned to the new shopper
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry);//Execute the SQL and get the returned result
        while ($row = $result->fetch_array()){
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        // Successful message and shopper ID
        $Message = "Registration successful!<br/>
        your shopperID in $_SESSION[ShopperID]<br/>";
        // Save the shopper Name in session variable
        $_SESSION["ShopperName"] = $name;
    }
    else { //error message
        $Message = "<h3 style='color:red'> Error in inserting record</h3>";
    }

    // Release the resource allocated for prepared statement
    $stmt->close();
}

// Close database connection
$conn->close();

// Display page Layout header with updated session state and links
include("header.php");
//Display message
echo $Message;
//Display page layout footer
include("footer.php");
?>