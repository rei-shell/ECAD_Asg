<?php
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include("header.php"); // Include the Page Layout header

function getProductImage($conn, $productID) {
    $qry = "SELECT ProductImage FROM Product WHERE ProductID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->bind_result($productImage);
    $stmt->fetch();
    $stmt->close();

    return $productImage;
}

function getTaxRate($conn, $currentDate) {
    $qry = "SELECT TaxRate FROM GST WHERE EffectiveDate <= ? ORDER BY EffectiveDate DESC LIMIT 1";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("s", $currentDate);
    $stmt->execute();
    $stmt->bind_result($taxRate);
    $stmt->fetch();
    $stmt->close();

    return $taxRate;
}

?>

<div class='container py-5'>
    <div class='row d-flex flex-wrap justify-content-between align-items-start'>
        <div class='col-lg-8'>
            <div class='card card-registration card-registration-2'>
                <div class='card-body p-0'>
                    <div class='row g-0'>
                        <div class='col-12'>
                            <div class='p-5'>
                                <div class='d-flex justify-content-between align-items-center mb-5'>
                                    <h1 class='fw-bold mb-0 text-black'>Shopping Cart</h1>
                                </div>
                                <hr class='my-4' />
                                <?php
                                if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
                                    // redirect to the login page if the session variable shopperid is not set
                                    header("Location: login.php");
                                    exit;
                                }

                                echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
                                if (isset($_SESSION["Cart"])) {
                                    include_once("mysql_conn.php");
                                   $qry = 'SELECT sc.*, p.Offered, p.OfferedPrice, (sc.Price * sc.Quantity) AS Total
											FROM ShopCartItem sc
											JOIN Product p ON sc.ProductID = p.ProductID
											WHERE sc.ShopCartID=?';

                                    $stmt = $conn->prepare($qry);
                                    $stmt->bind_param("i", $_SESSION["Cart"]);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $stmt->close();

                                    if ($result->num_rows > 0) {

										$row["ShipCharge"] = 0;                                      
										$subTotal = 0;


                                        while ($row = $result->fetch_array()) {
											$productImage = getProductImage($conn, $row["ProductID"]);
											$img = "./Images/Products/$productImage";


                                            echo "<div class='row mb-4 d-flex justify-content-between align-items-center'>";
                                            echo "<div class='col-md-2 col-lg-2 col-xl-2'>";
                                            echo "<img src='$img' class='img-fluid rounded-3' alt='$row[Name]'/>";
                                            echo "</div>";
                                            echo "<div class='col-md-3 col-lg-3 col-xl-3'>";
                                            echo "<h6 class='text-black mb-0'>$row[Name]</h6>";
                                            echo "</div>";
                                            echo "<div class='col-md-3 col-lg-3 col-xl-2 d-flex'>";
                                            echo "<form action='cartFunctions.php' method='post'>";
                                            echo "<div class='quantity-input'>";
                                        
                                            echo "<select name='quantity' onChange='this.form.submit()' class='form-control form-control-sm'>";
                                            for ($i = 1; $i <= 10; $i++) {
                                                $selected = ($i == $row["Quantity"]) ? "selected" : "";
                                                echo "<option value='$i' $selected>$i</option>";
                                            }
                                            echo "</select>";
                                        
                                            echo "</div>";
                                            echo "<input type='hidden' name='action' value='update'/>";
                                            echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
                                            echo "</form>";
                                        
                                            echo "</div>";
											 if ($row["Offered"] == 1) {
												$row["Total"] = $row["OfferedPrice"] * $row["Quantity"];

 												echo "<div class='col-md-3 col-lg-2 col-xl-2 offset-lg-1'>";
												echo "<h6 class='text-black mb-0'><del>$ " . number_format($row["Price"] * $row["Quantity"], 2) . "</del></h6>";							
												echo "<h6 class='text-success mb-0'>$ " . number_format($row["Total"], 2) . "</h6>";
												echo "</div>";



											} else {
												$row["Total"] = $row["Price"] * $row["Quantity"];

												echo "<div class='col-md-3 col-lg-2 col-xl-2 offset-lg-1'>";
												echo "<h6 class='mb-0'>$" . number_format($row["Total"], 2) . "</h6>";
												echo "</div>";
											}
                    
                                            echo "<div class='col-md-1 col-lg-1 col-xl-1 text-end'>";
                                            echo "<form action='cartFunctions.php' method='post'>";
                                            echo "<input type='hidden' name='action' value='remove'/>";
                                            echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
                                            echo "<input type='image' src='images/delete.png' title='Remove Item' style='width:25px; height:25px'/>";
                                            echo "</form>";
                                            echo "</div>";
                                            echo "</div>";
                                        
                                            $subTotal += $row["Total"];
                                        }


                                        echo "</div>"; // End of the container
                                        echo "</div>"; // End of card-body
                                        echo "</div>"; // End of card
                                        echo "</div>"; // End of col-lg-8
                                    } else {
										echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
										echo "</div>"; // End of container when the cart is empty
                                        echo "</div>"; // End of card-body when the cart is empty
                                        echo "</div>"; // End of card when the cart is empty
                                        echo "</div>"; 
                                        $conn->close(); // Close the database connection
                                    }

                                } else {
									echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
									echo "</div>"; // End of container when the cart is empty
                                    echo "</div>"; // End of card-body when the cart is empty
                                    echo "</div>"; // End of card when the cart is empty
                                    echo "</div>";
									$conn->close(); // Close the database connection

                                }
                                ?>

								
                            </div>
							
                        </div>
                    </div>

					<div class='col-lg-4 bg-grey'>
						<div class='p-3'>
							<h3 class='fw-bold mb-5 mt-2 pt-1'>Summary</h3>
							<hr class='my-4' />

						<div class='d-flex justify-content-between mb-4'>
						<h5 class='text-uppercase'>SubTotal</h5>
						<h5>$ <?php echo number_format($subTotal, 2); ?></h5>
						</div>

						<?php
						if ($subTotal > 200) {
								$row["ShipCharge"] = 0;
								echo "<div class='d-flex justify-content-between mb-4'>
								<h5 class='text-uppercase'>Shipping</h5>
								<h5>Free</h5>
							</div>"; // Adjust the text accordingly
						} else {

						echo "<h5 class='text-uppercase mb-3'>Shipping</h5>
							<div class='mb-4 pb-2'>
								<form method='post' action='shoppingCart.php'>";
						echo "<select class='form-control' name='shipping_option' onChange='this.form.submit()'>
								<option value='standard' " . (isset($_POST["shipping_option"]) == 'standard' ? 'selected' : '') . ">Standard Delivery (Next Day)  - $5.00</option>
								<option value='express' " . (isset($_POST["shipping_option"]) == 'express' ? 'selected' : '') . ">Express Delivery (2 hours) - $10.00</option>

							</select>
							</form>
						</div>";

						// Set the shipping charge based on the selected option
						if (isset($_POST["shipping_option"])) {
							if ($_POST["shipping_option"] == "standard") {
								$row["ShipCharge"] = 5;
							} else if ($_POST["shipping_option"] == "express") {
								$row["ShipCharge"] = 10;
							}
						}
						else{
								$row["ShipCharge"] = 5;
							}
					}

					$currentDate = date("Y-m-d");
					$taxRate = getTaxRate($conn, $currentDate);
					$taxAmount = ($subTotal * $taxRate) / 100;


					echo "<div class='d-flex justify-content-between mb-4'>
						<h5 class='text-uppercase'>Tax</h5>
						<h5>$"  . number_format($taxAmount, 2) ."</h5>
						</div>";

						

						$totalWithShipping = $subTotal + $row["ShipCharge"] + $taxAmount;

						// Update session subtotal including shipping fee
						$_SESSION["SubTotal"] = round($totalWithShipping, 2);		
						// Include the Page Layout footer
						?>
						<hr class='my-4' />
						<div class='d-flex justify-content-between mb-5'>
						<h5 class='text-uppercase'>Total price</h5>
						<h5>$ <?php echo number_format($totalWithShipping, 2); ?></h5>
						</div>
						<?php
						echo "<form method='post' action='checkoutProcess.php'>";
						echo "<input type='image' style='float:right;width:200px' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
						echo "</form></p>";
						?>
					</div>
					</div>
                </div>
				
            </div>
    </div>

				</div>
				</div>
				</div>


<?php
include("footer.php"); 
?>