<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div class='container' style='width:60%; margin:auto;'>
    <!-- Display Page Header - Category's name is read 
         from the query string passed from the previous page -->
    <div class='row' style='padding:5px'>
        <div class='col-12'>
            <span class='page-title'><?php echo $_GET['catName']; ?></span>
        </div>
    </div>

    <?php 
    // Include the PHP file that establishes the database connection handle: $conn
    include_once("mysql_conn.php");

    // To Do: Starting ....
    $cid = $_GET["cid"]; // Read Category ID from the query string

    // Form SQL to retrieve a list of products associated with the Category ID
    $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Offered, p.OfferedPrice
            FROM CatProduct cp 
            INNER JOIN product p ON cp.ProductID=p.ProductID
            WHERE cp.CategoryID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $cid); // "i" - integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Display each product in a row
    while ($row = $result->fetch_array()){
        echo "<div class='row' style='padding:5px'>"; // Start a new row

        // Left column - display a text link showing the product's name,
        // display the selling price in red in a new paragraph
        $product = "productDetails.php?pid={$row['ProductID']}";
        $formattedPrice = number_format($row["Price"], 2);
        $onOffer = $row["Offered"] == 1 ? "<span style='color:green;'>On Offer</span><br>" : "";
        echo "<div class='col-md-8'>"; // 67% of row width on medium and larger screens
        echo "<p>{$onOffer}<a href='{$product}'>{$row['ProductTitle']}</a></p>";
        
        if ($row["Offered"] == 1) {
            $formattedOfferedPrice = number_format($row["OfferedPrice"], 2);
            echo "Original Price: <del>S$ {$formattedPrice}</del><br>";
            echo "Offered Price: <span style='font-weight: bold; color:red;'>S$ {$formattedOfferedPrice}</span><br>";
        } else {
            echo "Price: <span style='font-weight: bold; color:red;'>S$ {$formattedPrice}</span>";
        }

        echo "</div>";

        // Right column - display the product's image
        $img = "./Images/products/{$row['ProductImage']}";
        echo "<div class='col-md-4'>"; // 33% of row width on medium and larger screens
        echo "<img src='{$img}' alt='Product Image' class='img-fluid'/>";
        echo "</div>";

        echo "</div>"; // end of a row
    }
    // To Do:  Ending ....

    $conn->close(); // Close the database connection
    echo "</div>"; // End of the container
    include("footer.php"); // Include the Page Layout footer
    ?>
