<?php include('layouts/header.php') ?>
<!--================ Hero banner start =================-->
<section class="hero-banner">
  <div class="container">
    <div class="row no-gutters align-items-center pt-60px">
      <div class="col-5 d-none d-sm-block">
        <div class="hero-banner__img">
          <img class="img-fluid" src="/images/header-img2.png" alt="">
        </div>
      </div>
      <div class="col-sm-7 col-lg-6 offset-lg-1 pl-4 pl-md-5 pl-lg-0">
        <div class="hero-banner__content">
          <h4>Welcome to</h4>
          <h1>Chasing Scents</h1>
          <p>Discover "Chasing Scents"—captivating fragrances that blend nature’s finest aromas into enchanting, long-lasting perfumes. Embrace a world where every scent tells an unforgettable story.</p>
          <a class="button button-hero" href="products.php">Browse Now</a>
        </div>
      </div>
    </div>
  </div>
</section>
<!--================ Hero banner start =================-->

<!--================ Hero Carousel start =================-->
<section class="section-margin mt-0">
  <div class="owl-carousel owl-theme hero-carousel">
    <div class="hero-carousel__slide">
      <img src="/images/home/pic1.png" alt="" class="img-fluid">
      <a href="#!" class="hero-carousel__slideOverlay">
        <h3>Men's Perfume</h3>
        <p>Men Perfumme Preference</p>
      </a>
    </div>
    <div class="hero-carousel__slide">
      <img src="/images/home/pic2.png" alt="" class="img-fluid">
      <a href="#!" class="hero-carousel__slideOverlay">
        <h3>Women's Perfume</h3>
        <p>Women Perfumme Preference</p>
      </a>
    </div>
    <div class="hero-carousel__slide">
      <img src="/images/home/pic3.png" alt="" class="img-fluid">
      <a href="#!" class="hero-carousel__slideOverlay">
        <h3>Unisex Perfume</h3>
        <p>All Type of Perfume</p>
      </a>
    </div>
  </div>
</section>
<!--================ Hero Carousel end =================-->

<!-- ================ trending product section start ================= -->
<section class="section-margin calc-60px">
  <div class="container">
    <div class="section-intro pb-60px">
      <h2><span class="section-intro__style">Items</span></h2>
    </div>
    <?php
    $sql = "SELECT * FROM products LIMIT 8";
    $result = mysqli_query($conn, $sql);
    ?>
    <div class="row">
      <?php
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $modal_id = 'fries_' . $row['prod_no'];
          $prod_no = $row['prod_no'];
          // Check if the user is logged in
          if (isset($_SESSION['userid'])) {
            // Check if the product is in the user's cart
            $cart_check_sql = "SELECT * FROM carts WHERE prod_no = $prod_no AND userid = $userid";
            $cart_check_result = mysqli_query($conn, $cart_check_sql);
            $is_in_cart = mysqli_num_rows($cart_check_result) > 0;
            if ($is_in_cart) {
              $cart_row = mysqli_fetch_assoc($cart_check_result);
              $cart_id = $cart_row['cart_id'];
            }
          } else {
            $is_in_cart = false;
          }
      ?>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="prodimg/<?= $row['image'] ?>" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button data-toggle="modal" data-target="#<?= $modal_id ?>"><i class="ti-search"></i></button></li>
                  <li><button onclick="window.location = 'addcart.php?prod_no=<?= $row['prod_no'] ?>'"><i class="ti-shopping-cart"></i></button></li>
                  <!--<?php if ($is_in_cart) { ?>-->
                  <!--  <li><button onclick="window.location = 'removecart.php?cart_id=<?= $cart_id ?>'"><i class="ti-eraser"></i></button></li>-->
                  <!--<?php } else { ?>-->
                    
                  <!--<?php } ?>-->
                  
                </ul>
              </div>
              <div class="card-body">
                <p><?= $row['type'] ?></p>
                <h4 class="card-product__title"><a href="product-details.php?prod_no=<?= $row['prod_no'] ?>"><?= $row['prodname'] ?></a></h4>
                <p class="card-product__price">₱<?= $row['price'] ?></p>
              </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row mb-4">
                    <div class="col-md-5 col-sm-5 col-xs-12">
                      <!-- Thumbnail Large Image start -->
                      <div class="tab-content">
                        <div id="pro-1" class="tab-pane fade show active">
                          <div style="background-image: url('prodimg/<?= $row['image'] ?>'); background-size: cover; background-position: center; height: 270px; width: 100% !important;"></div>
                        </div>
                      </div>
                      <!-- Thumbnail Large Image End -->
                      <!-- Thumbnail Image End -->
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                      <div class="modal-pro-content">
                        <h3><?= $row['prodname'] ?></h3>
                        <div class="product-price-wrapper mb-3 mt-3">
                          <span class="mr-3">₱<?= $row['price'] ?></span><span><i class="fa fa-check <?= $row['status'] !== 'Available' ? 'text-danger' : 'text-success' ?>"></i><?= $row['status'] ?></span>
                        </div>
                        <p><?= $row['description'] ?></p>
                        <?php if ($is_in_cart) { ?>
                          <div class="product-quantity mt-4">
                            <form action="removecart.php" method="get">
                              <button class="btn btn-danger" type="submit" name="cart_id" value="<?= $cart_id ?>" class="m-0">Remove to cart</button>
                            </form>

                          </div>

                        <?php } else { ?>
                          <div class="product-quantity mt-4">
                            <form action="addcart.php" method="get" class=" d-flex align-items-center">
                              <div class="form-group m-0">
                                <input type="number" class="form-control" name="quantity" id="formquantity" placeholder="Quantity" min="1" value="1" required>
                              </div>
                              <button class="btn btn-success ml-2" type="submit" name="prod_no" value="<?= $row['prod_no'] ?>">Add to cart</button>
                            </form>
                          </div>
                        <?php } ?>


                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal end -->
      <?php
        }
      } else {
        $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'info',
                                    title: 'No Data',
                                });
                            </script>";
      }
      ?>

    </div>
  </div>
</section>
<!-- ================ trending product section end ================= -->
<section class="section-margin calc-60px">
  <div class="container">
    <div class="section-intro pb-60px">
      <h2 class="text-center"><span class="section-intro__style">Contact Us</span></h2>
    </div>
    <div class="row">
      <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
        <div class="media contact-info">
          <span class="contact-info__icon"><i class="ti-home"></i></span>
          <div class="media-body">
            <h3>H.T Feliciano St. Aguada</h3>
            <p>Ozamiz City, Misamis Occidental</p>
          </div>
        </div>
        <div class="media contact-info">
          <span class="contact-info__icon"><i class="ti-headphone"></i></span>
          <div class="media-body">
            <h3><a href="tel:454545654">(088) 521 2221</a></h3>
            <p>Mon to Fri 9am to 6pm</p>
          </div>
        </div>
        <div class="media contact-info">
          <span class="contact-info__icon"><i class="ti-email"></i></span>
          <div class="media-body">
            <h3><a href="mailto:support@colorlib.com">chasingscents@gmail.com</a></h3>
            <p>Send us your query anytime!</p>
          </div>
        </div>
      </div>
      <div class="col-md-8 col-lg-9">
        <form action="#/" class="form-contact contact_form" action="#" id="contactForm" novalidate="novalidate">
          <div class="row">
            <div class="col-lg-5">
              <div class="form-group">
                <input class="form-control" name="name" id="name" type="text" placeholder="Enter your name">
              </div>
              <div class="form-group">
                <input class="form-control" name="email" id="email" type="email" placeholder="Enter email address">
              </div>
              <div class="form-group">
                <input class="form-control" name="subject" id="subject" type="text" placeholder="Enter Subject">
              </div>
            </div>
            <div class="col-lg-7">
              <div class="form-group">
                <textarea class="form-control different-control w-100" name="message" id="message" cols="30" rows="5" placeholder="Enter Message"></textarea>
              </div>
            </div>
          </div>
          <div class="form-group text-center text-md-right mt-3">
            <button type="submit" class="button button--active button-contactForm">Send Message</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php include('layouts/footer.php') ?>