<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>
<!--Slider section-->
<section class="slider_section">
      <div class="slider_container">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-7">
                    <div class="detail-box">
                      <h1>
                        Welcome To <br>
                        GIFTED TREAURES
                      </h1>
                      <p>
                       We are having sales from 1 Feb to 21 Feb!
                      </p>
                      <a href="category.php">
                        Browse shop
                      </a>
                    </div>
                  </div>
                  <div class="col-md-5 ">
                    <div class="img-box">
                      <img src="https://cdn3d.iconscout.com/3d/premium/thumb/promotion-6684140-5523033.png?f=webp" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item ">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-7">
                    <div class="detail-box">
                      <h1>
                         Not a member yet? <br>
                         Sign up now!
                      </h1>
                      <p>
                        New item has arrived!
                      </p>
                      <a href="register.php">
                        Sign up
                      </a>
                    </div>
                  </div>
                  <div class="col-md-5 ">
                    <div class="img-box">
                      <img src="https://cdni.iconscout.com/illustration/premium/thumb/sign-up-6333618-5230178.png?f=webp" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
           
          </div>
          <div class="carousel_btn-box">
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
              <span class="sr-only">Previous</span>
            </a>
            <img src="images/line.png" alt="" />
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <i class="fa fa-arrow-right" aria-hidden="true"></i>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- end slider section -->

      <!-- shop section -->

  <section class="shop_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Product Offers
        </h2>
      </div>
      <div class="row">
          <?php
          include_once("mysql_conn.php");
          //Check if email already exist & shopperID of the email must not be the current shopper
          $qry = "SELECT * FROM product WHERE offered = 1";
          $result = $conn->query($qry); 
          while ($row = $result->fetch_array()) {
               echo '<div class="col-sm-6 col-md-4 col-lg-3">
                         <div class="box">
                         <a href="productDetails.php?pid=' . $row["ProductID"] . '">
                         <div class="img-box">
                              <img src="images/Products/' . $row["ProductImage"] . '" alt="">
                         </div>
                         <div class="detail-box">
                              <h6>
                              ' . $row["ProductTitle"] . '
                              </h6>
                         </div>
                         <div class="detail-qty d-flex justify-content-between">
                              <h6>
                              Quantity:
                              </h6>
                              <h6>
                              '."X". $row["Quantity"] . '
                              </h6>
                         </div>
                         <div class="detail-price d-flex justify-content-between">
                              <h6>
                              <span>
                              ' ."$". $row["OfferedPrice"] . '
                              </span>
                              </h6>
                              <h6>
                              <s>'."$" . $row["Price"] . '</s>
                              </h6>
                         </div>
                         </a>
                         </div>
                    </div>';
          };
          ?>
      <div class="btn-box">
        <a href="category.php">
          View All Products
        </a>
      </div>
    </div>
  </section>

  <!-- end shop section -->

     <!-- Shop section -->
     <section class="client_section layout_padding">   
          <div class="container">
               <div class="heading_container heading_center">
                    <h2>
                         Our Categories
                    </h2>
               </div>
               <div class="row text-center categories">
                    <div class="col-lg-4 cat1">
                         <a href="catProduct.php?cid=2&catName=Gifts"><h6>Gifts</h6></a>
                    </div>
                    <div class="col-lg-4 cat2">
                         <a href="catProduct.php?cid=1&catName=Flowers"><h6>Flowers</h6></a>
                    </div>
                    <div class="col-lg-4 cat3">
                         <a href="catProduct.php?cid=3&catName=Hampers"><h6>Hampers</h6></a>
                    </div>
               </div>
          </div>
     </section>

  <!--Review section-->
  <section class="client_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Customer Review
        </h2>
      </div>
    </div>
    <div class="container px-0">
      <div id="customCarousel2" class="carousel  carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
          <?php
               include_once("mysql_conn.php");
               //Check if email already exist & shopperID of the email must not be the current shopper
               $qry = "SELECT f.*,s.Name FROM feedback f
               INNER JOIN shopper s ON f.ShopperID = s.ShopperID;";
               $result = $conn->query($qry); 
               $i = 0;
               while ($row = $result->fetch_array()) {
                    $rank = $row["Rank"];
                    $date = $row["DateTimeCreated"];
                    $dateTime = new DateTime($date);
                    $formattedDate = $dateTime->format("M Y");
                    $review = "";
                    if($i===0){
                         for ($i = 1; $i <= $rank; $i++) {
                              $review.='<i class="fas fa-star" style="color: yellow;"></i>';
                         }
                         echo'<div class="carousel-item active">
                         <div class="box">
                         <div class="client_info">
                         <div class="client_name">
                              <h5>
                              '.$row["Name"].'
                              </h5>
                              <h6>
                              '.$review.'
                              </h6>
                         </div>
                         <i class="fas fa-thumbs-up"></i>
                         </div>
                         <p>
                         '.$row["Content"].'
                         </p>
                         <p class="fw-bold opacity-50">
                         '.$formattedDate.'
                         </p>
                         </div>
                    </div>  ';
                    $i++;
                    }else{
                         for ($i = 1; $i <= $rank; $i++) {
                              $review.='<i class="fas fa-star" style="color: yellow;"></i>';
                         }
                         echo'<div class="carousel-item">
                         <div class="box">
                         <div class="client_info">
                         <div class="client_name">
                              <h5>
                              '.$row["Name"].'
                              </h5>
                              <h6>
                              '.$review.'
                              </h6>
                         </div>
                         <i class="fas fa-thumbs-up"></i>
                         </div>
                         <p>
                         '.$row["Content"].'
                         </p>
                         <p class="fw-bold opacity-50">
                         '.$formattedDate.'
                         </p>
                         </div>
                    </div>  ';
                    $i++;
                    }
               };
               $conn->close();
          ?>
               
        </div>
        <div class="carousel_btn-box">
          <a class="carousel-control-prev" href="#customCarousel2" role="button" data-slide="prev">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#customCarousel2" role="button" data-slide="next">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- end client section -->
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="js/custom.js"></script>

<?php 
// Include the Page Layout footer
include("footer.php"); 
?>
