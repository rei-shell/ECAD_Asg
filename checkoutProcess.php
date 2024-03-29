<?php
ob_start();
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings
include_once("mysql_conn.php"); 

// Check if the form has been submitted (POST request)
if($_POST) {
    // To Do 6 (DIY): Check to ensure each product item saved in the associative
    // array is not out of stock
    if (isset($_SESSION['Items']) && is_array($_SESSION['Items'])) {
        foreach ($_SESSION['Items'] as $key => $item) {
            $pid = $item["productId"];
            $qty = $item["quantity"];
            $productName = $item["name"];

            $qryStock = "SELECT Quantity FROM Product WHERE ProductID=?";
            $stmtStock = $conn->prepare($qryStock);
            $stmtStock->bind_param("i", $pid);
            $stmtStock->execute();
            $resultStock = $stmtStock->get_result();
            $stmtStock->close();

            if ($resultStock->num_rows > 0) {
                $row = $resultStock->fetch_assoc();
                $currentStock = $row['Quantity'];

                if ($currentStock < $qty) {
                    echo "Product $pid : $productName is out of stock! <br />";
                    echo "Please return to the shopping cart to amend your purchase. <br />";
                    include("footer.php");
                    exit;
                }
            }
        }
    }
    // End of To Do 6

    $paypal_data = '';

    // Get all items from the shopping cart, concatenate to the variable $paypal_data
    // $_SESSION['Items'] is an associative array
    if (isset($_SESSION['Items']) && is_array($_SESSION['Items'])) {
        foreach ($_SESSION['Items'] as $key => $item) {
            $paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
            $paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($item["price"]);
            $paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
            $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
        }
    }

    // Data to be sent to PayPal
    $padata = '&CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
        '&PAYMENTACTION=Sale' .
        '&ALLOWNOTE=1' .
        '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
        '&PAYMENTREQUEST_0_AMT=' . urlencode($_SESSION["FinalTotal"]) .
        '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($_SESSION["SubTotal"]) . 
        '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) . 
        '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($_SESSION["Tax"]) . 	
        '&BRANDNAME=' . urlencode("Gifted Treasure") .
        $paypal_data .				
        '&RETURNURL=' . urlencode($PayPalReturnURL) .
        '&CANCELURL=' . urlencode($PayPalCancelURL);

    // We need to execute the "SetExpressCheckOut" method to obtain PayPal token
    $httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
                                       $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

    // Respond according to the message received from PayPal
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
        "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
        if ($PayPalMode == 'sandbox') {
            $paypalmode = '.sandbox';
        } else {
            $paypalmode = '';
        }

        // Redirect user to PayPal store with Token received.
        $paypalurl ='https://www' . $paypalmode . 
                    '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . 
                    $httpParsedResponseAr["TOKEN"];
        header('Location: ' . $paypalurl);
    } else {
        // Show error message
        echo "<div style='color:red'><b>SetExpressCheckOut failed : </b>" .
              urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . "</div>";
        echo "<pre>" . print_r($httpParsedResponseAr) . "</pre>";
    }
}

// Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if (isset($_GET["token"]) && isset($_GET["PayerID"])) {	
    // We will be using these two variables to execute the "DoExpressCheckoutPayment"
    // Note: we haven't received any payment yet.
    $token = $_GET["token"];
    $playerid = $_GET["PayerID"];
    $paypal_data = '';

    // Get all items from the shopping cart, concatenate to the variable $paypal_data
    // $_SESSION['Items'] is an associative array
    foreach ($_SESSION['Items'] as $key => $item) {
        $paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($item["price"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
    }

    // Data to be sent to PayPal
    $padata = '&TOKEN=' . urlencode($token) .
              '&PAYERID=' . urlencode($playerid) .
              '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
              $paypal_data .	
              '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($_SESSION["SubTotal"]) .
              '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($_SESSION["Tax"]) .
              '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) .
              '&PAYMENTREQUEST_0_AMT=' . urlencode($_SESSION["FinalTotal"]) .
              '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode);

    // We need to execute the "DoExpressCheckoutPayment" at this point 
    // to receive payment from the user.
    $httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
                                       $PayPalApiUsername, $PayPalApiPassword, 
                                       $PayPalApiSignature, $PayPalMode);

    // Check if everything went ok
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
        "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
        
        // To Do 5 (DIY): Update stock inventory in the product table 
        // after successful checkout
        foreach ($_SESSION['Items'] as $key => $item) {
            $pid = $item["productId"];
            $qty = $item["quantity"];

            $qryStock = "SELECT Quantity FROM Product WHERE ProductID=?";
            $stmtStock = $conn->prepare($qryStock);
            $stmtStock->bind_param("i", $pid);
            $stmtStock->execute();
            $resultStock = $stmtStock->get_result();
            $stmtStock->close();

            if ($resultStock->num_rows > 0) {
                $row = $resultStock->fetch_assoc();
                $currentStock = $row['Quantity'];

                $newStock = $currentStock - $qty;

                $qryUpdateStock = "UPDATE Product SET Quantity=? WHERE ProductID=?";
                $stmtUpdateStock = $conn->prepare($qryUpdateStock);
                $stmtUpdateStock->bind_param("ii", $newStock, $pid);
                $stmtUpdateStock->execute();
                $stmtUpdateStock->close();
            }
        }
        // End of To Do 5

        // To Do 2: Update shopcart table, close the shopping cart (OrderPlaced=1)
        $qry = "UPDATE ShopCart SET OrderPlaced=1, Quantity=?, SubTotal=?, ShipCharge=?, Tax=?, Total=? WHERE ShopCartID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iddddi", $_SESSION["NumCartItem"], $_SESSION["SubTotal"], $_SESSION["ShipCharge"], $_SESSION["Tax"], $_SESSION["FinalTotal"], $_SESSION["Cart"]); 
        $stmt->execute();
        $stmt->close();
        // End of To Do 2

        // We need to execute the "GetTransactionDetails" API Call at this point 
        // to get customer details
        $transactionID = urlencode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
        $nvpStr = "&TRANSACTIONID=" . $transactionID;
        $httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
                                           $PayPalApiUsername, $PayPalApiPassword, 
                                           $PayPalApiSignature, $PayPalMode);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
            "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

            // To Do 3: Insert an Order record with shipping information
            // Get the Order ID and save it in the session variable.
            $ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
            $ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
            
            if (isset($httpParsedResponseAr["SHIPTOSTREET2"])) {
                $ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
            }
            if (isset($httpParsedResponseAr["SHIPTOCITY"])) {
                $ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOCITY"]);
            }
            if (isset($httpParsedResponseAr["SHIPTOSTATE"])) {
                $ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
            }
            $ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]) . 
                            ' ' . urldecode($httpParsedResponseAr["SHIPTOZIP"]);
                
            $ShipCountry = urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
            $ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			

            $qry = "INSERT INTO orderdata (ShipName, ShipAddress, ShipCountry, ShipEmail, ShopCartID)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("ssssi", $ShipName, $ShipAddress, $ShipCountry, $ShipEmail, $_SESSION["Cart"]);
            $stmt->execute();
            $stmt->close();

            $qry = "SELECT LAST_INSERT_ID() AS OrderID";
            $result = $conn->query($qry);
            $row = $result->fetch_array();
            $_SESSION["OrderID"] = $row["OrderID"];
            // End of To Do 3

            $conn->close();
            
            // To Do 4A: Reset the "Number of Items in Cart" session variable to zero.
            $_SESSION["NumCartItem"] = 0;

            // To Do 4B: Clear the session variable that contains Shopping Cart ID.
            unset($_SESSION["Cart"]);

            // To Do 4C: Redirect shopper to the order confirmed page.
            header("Location: orderConfirmed.php");
            exit;
        } else {
            echo "<div style='color:red'><b>GetTransactionDetails failed:</b>" . 
                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '</div>';
            echo "<pre>" . print_r($httpParsedResponseAr) . "</pre>";
            $conn->close();
        }
    } else {
        echo "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>" . 
            urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '</div>';
        echo "<pre>" . print_r($httpParsedResponseAr) . "</pre>";
    }
}

include("footer.php"); // Include the Page Layout footer
ob_end_flush();
?>
