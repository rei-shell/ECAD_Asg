<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
<form name="frmSearch" method="get" action="">
    <div class="mb-3 row"> <!-- 1st row -->
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Product Search</span>
        </div>
    </div> <!-- End of 1st row -->
    <div class="mb-3 row"> <!-- 2nd row -->
        <label for="keywords" 
               class="col-sm-3 col-form-label">Product Title:</label>
        <div class="col-sm-6">
            <input class="form-control" name="keywords" id="keywords" 
                   type="search" />
        </div>
        <div class="col-sm-3">
            <button type="submit">Search</button>
        </div>
    </div>  <!-- End of 2nd row -->
</form>

<?php
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php"); 
// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    
    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
    $SearchText = $_GET["keywords"];

    $qry = "SELECT ProductTitle, p.ProductID
    FROM product p
    INNER JOIN catproduct cp ON p.ProductID = cp.ProductID
    INNER JOIN category c ON cp.CategoryID = c.CategoryID
    INNER JOIN productspec ps ON p.ProductID = ps.ProductID
    WHERE ProductTitle LIKE '%$SearchText%' OR productDesc LIKE '%$SearchText%' OR Price LIKE '%$SearchText%' OR SpecVal LIKE '%$SearchText%'
    ORDER BY ProductTitle";

    $result = $conn->query($qry);
    if ($result->num_rows == 0) {
        echo 'No result were found';
    }
    else{
        // Using while loop to print each row of data
        while ($row = $result->fetch_array()) {
            $catproduct = "productDetails.php?pid=$row[ProductID]";
            echo"<p><a href=$catproduct>$row[ProductTitle]</a></p>";
        }
    }
    $conn->close();

    
	// To Do (DIY): End of Code
}
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>