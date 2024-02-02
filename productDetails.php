<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div class='container' style='width:90%; margin:auto;'>

<?php 
$pid=$_GET["pid"]; // Read Product ID from the query string

// Include the PHP file that establishes a database connection handle: $conn
include_once("mysql_conn.php"); 
$qry = "SELECT * FROM product WHERE ProductID = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// To Do 1:  Display Product information. Starting ....
while ($row = $result->fetch_array()) {
    // Display Page header -
    // Product's name is read from the "ProductTitle" column of "Product" table.
    echo "<div class='row'>";
    echo "<div class='col-sm-12' style='padding:5px'>";
    echo "<span class='page-title'>$row[ProductTitle]</span>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>"; // Start a new row

    // Left column - display the product's description,
    echo "<div class='col-md-9' style='padding:5px'>";
    echo "<p>$row[ProductDesc]</p>";

    // Left column - display the product's specification,
    $qry = "SELECT s.SpecName, ps.SpecVal
            FROM productspec ps
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID=?
            ORDER BY ps.priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid); //"i" - integer
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();
    while ($row2 = $result2->fetch_array()) {
        echo $row2["SpecName"].": ".$row2["SpecVal"]."<br/>";
    }
    echo "</div>"; // End of left column

    // Right column - display the product's image and price
    $img = "./Images/Products/$row[ProductImage]";
    echo "<div class='col-md-3' style='vertical-align-top; padding:5px'>";
    echo "<p><img src='$img' class='img-fluid'/></p>";

    // Determine if the product is on offer
    $offered = ($row["OfferedPrice"] != null && $row["OfferedPrice"] < $row["Price"]);
    $offeredPrice = $offered ? $row["OfferedPrice"] : $row["Price"];

    $formattedDisplayPrice = number_format($offeredPrice, 2);
    echo "Price:<span style='font-weight:bold;color:red;'>S$ $formattedDisplayPrice</span>";

    // To Do 2:  Create a Form for adding the product to the shopping cart. Starting ....
    echo "<form action='cartFunctions.php' method='post'>";
    echo "<input type='hidden' name='action' value='add'/>";
    echo "<input type='hidden' name ='product_id' value='$pid'/>";
    echo "<input type='hidden' name='offered' value='$offered'/>";
    echo "<input type='hidden' name='offeredPrice' value='$offeredPrice'/>";
    echo "Quantity: <input type='number' name='quantity' value='1' min='1' max='10' style='width:40px' required/>";
    echo "<button type='submit' class='btn btn-primary'>Add to Cart</button>";
    echo "</form>";
    echo "</div>"; // End of right column
}

// To Do 2:  Ending ....

$conn->close(); // Close the database connection
echo "</div>"; // End of right column
echo "</div>"; // End of row
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>

