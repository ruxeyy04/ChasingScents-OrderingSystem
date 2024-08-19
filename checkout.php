<?php include('layouts/header.php') ?>
<!-- ================ start banner area ================= -->
<section class="blog-banner-area" id="category">
  <div class="container h-100">
    <div class="blog-banner">
      <div class="text-center">
        <h1>Product Checkout</h1>
        <nav aria-label="breadcrumb" class="banner-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>
<!-- ================ end banner area ================= -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_SESSION['userid'])) {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit;
  }



  // Generate the order ID
  $order_id = date('ymd') . strtoupper(bin2hex(random_bytes(4)));

  // Get cart items for the user
  $sql = "
          SELECT i.prod_no, i.price, c.quantity
          FROM carts c
          JOIN products i ON c.prod_no = i.prod_no
          WHERE c.userid = ?
      ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $userid);
  $stmt->execute();
  $result = $stmt->get_result();

  $subtotal = 0;
  $cart_items = [];
  while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $subtotal += $row['price'] * $row['quantity'];
  }

  // Assuming free shipping
  $total = $subtotal + $delivery_fee;

  // Insert into orders table
  $order_sql = "INSERT INTO orders (order_id, userid, order_datetime, status) VALUES (?, ?, ?, 'Pending')";
  $order_stmt = $conn->prepare($order_sql);
  $order_stmt->bind_param("sss", $order_id, $userid, $timestamp);
  $order_stmt->execute();

  // Insert into orderdetail table
  $orderdetail_sql = "INSERT INTO orderdetail (order_id, prod_no, quantity, price_each) VALUES (?,?, ?, ?)";
  $orderdetail_stmt = $conn->prepare($orderdetail_sql);
  foreach ($cart_items as $item) {
    $price_each = $item['price'] *  $item['quantity'];
    $orderdetail_stmt->bind_param("siii", $order_id, $item['prod_no'], $item['quantity'], $price_each);
    $orderdetail_stmt->execute();
  }

  // Insert into payment table
  $payment_sql = "INSERT INTO payment (pay_id, userid, order_id, payment_date, amount, payment_type) VALUES (?, ?, ?, NOW(), ?, ?)";
  $pay_id = strtoupper(bin2hex(random_bytes(4)));
  $payment_type = $_POST['payment_method'];
  $payment_stmt = $conn->prepare($payment_sql);
  $payment_stmt->bind_param("sssds", $pay_id, $userid, $order_id, $total, $payment_type);
  $payment_stmt->execute();

  $billing_sql = "INSERT INTO billing_details (billing_id, pay_id, userid,name, email, city, province, zipcode, phone, address, save_address, order_note, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $billing_id = strtoupper(bin2hex(random_bytes(4)));
  $name = $_POST['billing_name'];
  $note = $_POST['order_note'];
  $email = $_POST['billing_email'];
  $city = $_POST['city'];
  $province = $_POST['province'];
  $zipcode = $_POST['zipcode'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $save_address = $_POST['save_address'] ?? 0;
  $billing_stmt = $conn->prepare($billing_sql);
  $billing_stmt->bind_param("sssssssssssss", $billing_id, $pay_id, $userid, $name, $email, $city, $province, $zipcode, $phone, $address, $save_address, $note, $timestamp);
  $billing_stmt->execute();


  $clear_cart_sql = "DELETE FROM carts WHERE userid = ?";
  $clear_cart_stmt = $conn->prepare($clear_cart_sql);
  $clear_cart_stmt->bind_param("i", $userid);
  $clear_cart_stmt->execute();

  echo '<meta http-equiv="refresh" content="0;url=confirmation.php?orderid=' . $order_id . '">';
  exit;
}
?>

<!--================Checkout Area =================-->
<form action="" method="post">
  <section class="checkout_area section-margin--small">
    <div class="container">
      <?php
      $billing_sql = "SELECT *
                                            FROM billing_details 
                                            WHERE userid = ?
                                            ORDER BY date_created DESC 
                                            LIMIT 1";
      $billing_stmt = $conn->prepare($billing_sql);
      $billing_stmt->bind_param("i", $userid);
      $billing_stmt->execute();
      $billing_result = $billing_stmt->get_result();

      $billing_details = $billing_result->fetch_assoc();
      if ($billing_details) {
        if ($billing_details['save_address'] != 1) {
          unset($billing_details);
        }
      }

      ?>
      <div class="billing_details">
        <div class="row">
          <div class="col-lg-8">
            <h3>Billing Details</h3>
            <div class="row contact_form">
              <div class="col-md-12 form-group p_star">
                <input type="text" class="form-control" name="billing_name" required value="<?php echo isset($billing_details['name']) ? htmlspecialchars($billing_details['name']) : $userinfo['fname'] . ' ' . $userinfo['lname']; ?>">
              </div>
              <div class="col-md-12 form-group">
                <input type="text" class="form-control" name="address" value="<?php echo isset($billing_details['address']) ? htmlspecialchars($billing_details['address']) : ''; ?>" placeholder="Address" required>
              </div>
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" name="billing_email" required placeholder="Email" value="<?php echo isset($billing_details['email']) ? htmlspecialchars($billing_details['email']) : $userinfo['email']; ?>">
              </div>
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" name="phone" placeholder="Contact Number" value="<?php echo isset($billing_details['phone']) ? htmlspecialchars($billing_details['phone']) : $userinfo['contact_number']; ?>">
              </div>
              <div class="col-md-12 form-group p_star">
                <input type="text" class="form-control" name=" city" placeholder="City" value="<?php echo isset($billing_details['city']) ? htmlspecialchars($billing_details['city']) : '' ?>">
              </div>
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" name="province" placeholder="Province" value="<?php echo isset($billing_details['province']) ? htmlspecialchars($billing_details['province']) : '' ?>">
              </div>
              <div class="col-md-6 form-group p_star">
                <input type="text" class="form-control" name="zipcode" placeholder="Zipcode" value="<?php echo isset($billing_details['zipcode']) ? htmlspecialchars($billing_details['zipcode']) : '' ?>">
              </div>
              <div class="col-md-12 form-group mb-0">
                <div class="creat_account">
                  <input type="checkbox" id="f-option3" name="save_address" value="1" <?php echo isset($billing_details['save_address']) ? 'checked' : '' ?>>
                  <label for="f-option3">Save Address?</label>
                </div>
                <textarea class="form-control" name="order_note" rows="1" placeholder="Order Notes"><?php echo isset($billing_details['order_note']) ? htmlspecialchars($billing_details['order_note']) : ''; ?></textarea>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <?php
            if (isset($_SESSION['userid'])) {

              $cart_query = "SELECT carts.*, products.*, carts.quantity AS cart_quantity
                                        FROM carts 
                                        INNER JOIN products ON carts.prod_no = products.prod_no 
                                        WHERE carts.userid = ?";
              $stmt = $conn->prepare($cart_query);
              $stmt->bind_param("i", $userid);
              $stmt->execute();
              $cart_result = $stmt->get_result();

              $subtotal = 0;
            ?>
            <?php     } else {
              echo '<meta http-equiv="refresh" content="0;url=login.php">';
              exit;
            }

            ?>
            <div class="order_box">
              <h2>Your Order</h2>
              <ul class="list">
                <li><a href="#">
                    <h4>Product <span>Total</span></h4>
                  </a></li>
                <?php while ($row = $cart_result->fetch_assoc()) {
                  $item_total = $row['price'] * $row['cart_quantity'];
                  $subtotal += $item_total;
                ?>
                <li><a href="#"> <?=$row['prodname']?> <span class="middle">x <?=$row['cart_quantity']?></span> <span class="last">₱<?= number_format($item_total, 2) ?></span></a></li>
                <?php } ?>
                
              </ul>
              <ul class="list list_2">
                <li><a href="#">Subtotal <span>₱<?php echo number_format($subtotal, 2); ?></span></a></li>
                <li><a href="#">Shipping <span>₱0.00</span></a></li>
                <li><a href="#">Total <span>₱<?php echo number_format($subtotal, 2); ?></a></li>
              </ul>
              <div class="payment_item">
                <div class="radion_btn">
                  <input type="radio" id="f-option5" name="payment_method" name="Check Payments" required>
                  <label for="f-option5">Check payments</label>
                  <div class="check"></div>
                </div>
                <p>Please send a check to Store Name, Store Street, Store Town, Store State / County,
                  Store Postcode.</p>
              </div>
              <div class="payment_item active">
                <div class="radion_btn">
                  <input type="radio" id="f-option6" name="payment_method" value="PayPal/Credit Card" required>
                  <label for="f-option6">Paypal </label>
                  <img src="img/product/card.jpg" alt="">
                  <div class="check"></div>
                </div>
                <p>Pay via PayPal; you can pay with your credit card if you don’t have a PayPal
                  account.</p>
              </div>
              <div class="payment_item active">
                <div class="radion_btn">
                  <input type="radio" id="f-option7" name="payment_method" value="GCash" required>
                  <label for="f-option7">GCash </label>
                  <img src="img/product/gcashlogo.png" alt="" height="30">
                  <div class="check"></div>
                </div>
                <p>Pay via GCash; you can scan with your gcash acount </p>
              </div>
              <div class="text-center">
                <button class="button button-paypal" type="submit">Confirm Payment</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</form>
<!--================End Checkout Area =================-->


<?php include('layouts/footer.php') ?>