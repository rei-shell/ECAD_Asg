<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- HTML Form to collect search keyword and submit it to the same page on the server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
    <form name="frmSearch" method="get" action="">
        <!-- 1st row for Page Title -->
        <div class="mb-3 row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Product Search</span>
            </div>
        </div> <!-- End of 1st row -->

        <!-- 2nd row for Product Title search -->
        <div class="mb-3 row">
            <label for="keywords" class="col-sm-3 col-form-label">Product Title:</label>
            <div class="col-sm-6">
                <input class="form-control" name="keywords" id="keywords" type="search" />
            </div>
            <div class="col-sm-3">
                <button type="submit">Search</button>
            </div>
        </div> <!-- End of 2nd row -->

        <!-- 3rd row for Price Range Inputs -->
        <div class="mb-3 row"> 
            <label for="minPrice" class="col-sm-3 col-form-label">Min Price:</label>
            <div class="col-sm-3">
                <input class="form-control" name="minPrice" id="minPrice" type="number" value="0" />
            </div>

            <label for="maxPrice" class="col-sm-3 col-form-label">Max Price:</label>
            <div class="col-sm-3">
                <input class="form-control" name="maxPrice" id="maxPrice" type="number" value="1000" />
            </div>
        </div> <!-- End of 3rd row -->

        <!-- ... (your existing code) ... -->

    </form>

    <?php
    // Include the PHP file that establishes a database connection handle: $conn
    include_once("mysql_conn.php"); 

    // Initialize an empty WHERE clause
    $whereClause = '';

    // Check if the search keyword is provided
    if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
        $SearchText = $_GET["keywords"];
        $whereClause .= "(ProductTitle LIKE '%$SearchText%' OR productDesc LIKE '%$SearchText%' OR Price LIKE '%$SearchText%' OR SpecVal LIKE '%$SearchText%')";
    }

    // Get the price range values or set default values
    $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : 0;
    $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : 1000;

    // Check if either the search keyword or price range is provided
    if ($whereClause !== '' || ($minPrice > 0 || $maxPrice < 1000)) {
        $whereClause .= ($whereClause !== '' ? " AND " : "") . "Price BETWEEN $minPrice AND $maxPrice";

        // Construct the SQL query
        $qry = "SELECT DISTINCT ProductTitle, p.ProductID
        FROM product p
        INNER JOIN catproduct cp ON p.ProductID = cp.ProductID
        INNER JOIN category c ON cp.CategoryID = c.CategoryID
        INNER JOIN productspec ps ON p.ProductID = ps.ProductID
        WHERE $whereClause
        ORDER BY ProductTitle";

        $result = $conn->query($qry);
        if ($result->num_rows == 0) {
            echo 'No results were found';
        } else {
            // Using a while loop to print each row of data
            while ($row = $result->fetch_array()) {
                $catproduct = "productDetails.php?pid=$row[ProductID]";
                echo "<p><a href=$catproduct>$row[ProductTitle]</a></p>";
            }
        }
    } else {
        echo 'Please provide either a search keyword or a price range.';
    }

    $conn->close();
    echo "</div>"; // End of container
    include("footer.php"); // Include the Page Layout footer
    ?>
