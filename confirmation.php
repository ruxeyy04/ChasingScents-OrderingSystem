<?php include('layouts/header.php') ?>
<!-- ================ start banner area ================= -->
<section class="blog-banner-area" id="category">
  <div class="container h-100">
    <div class="blog-banner">
      <div class="text-center">
        <h1>Order Confirmation</h1>
        <nav aria-label="breadcrumb" class="banner-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shop Category</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>
<!-- ================ end banner area ================= -->
<?php
if (!isset($_GET['orderid'])) {
  echo '<meta http-equiv="refresh" content="0;url=products.php">';
  exit();
}
$order_id = $_GET['orderid'];

$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$order_datetime = $row['order_datetime']; // Example: '2023-05-26 13:00:00'
$timestamp = strtotime($order_datetime);
$formatted_date = date('F j, Y \a\t g:ia', $timestamp);
if (!$row) {
  echo '<meta http-equiv="refresh" content="0;url=products.php">';
  exit();
}

$sql1 = "SELECT a.*, b.* FROM payment a INNER JOIN billing_details b ON a.pay_id=b.pay_id WHERE a.order_id = '$order_id'";
$result1 = $conn->query($sql1);
$pay = $result1->fetch_assoc();



?>
<!--================Order Details Area =================-->
<section class="order_details section-margin--small">
  <div class="container">
    <p class="text-center billing-alert">Thank you. Your order has been received.</p>
    <div class="row mb-5">
      <div class="col-md-6 col-xl-4 mb-4 mb-xl-0">
        <div class="confirmation-card">
          <h3 class="billing-title">Order Info</h3>
          <table class="order-rable">
            <tr>
              <td>Order number</td>
              <td>: <?= $order_id ?></td>
            </tr>
            <tr>
              <td>Date</td>
              <td>: <?= $formatted_date ?></td>
            </tr>
            <tr>
              <td>Total</td>
              <td>: ₱<?= number_format($pay['amount'], 2) ?></td>
            </tr>
            <tr>
              <td>Payment method</td>
              <td>: <?= $pay['payment_type'] ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-md-6 col-xl-4 mb-4 mb-xl-0">
        <div class="confirmation-card">
          <h3 class="billing-title">Billing Address</h3>
          <table class="order-rable">
            <tr>
              <td>Name</td>
              <td>: <?= $pay['name'] ?></td>
            </tr>
            <tr>
              <td>Address</td>
              <td>: <?= $pay['address'] ?></td>
            </tr>
            <tr>
              <td>City</td>
              <td>: <?= $pay['city'] ?></td>
            </tr>
            <tr>
              <td>Province</td>
              <td>: <?= $pay['province'] ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-md-6 col-xl-4 mb-4 mb-xl-0">
        <div class="confirmation-card">
          <h3 class="billing-title">Contact Info</h3>
          <table class="order-rable">
            <tr>
              <td>Name</td>
              <td>: <?= $userinfo['fname'] ?> <?= $userinfo['lname'] ?></td>
            </tr>
            <tr>
              <td>Contact</td>
              <td>: <?= $pay['phone'] ?></td>
            </tr>
            <tr>
              <td>Email</td>
              <td>: <?= $pay['email'] ?></td>
            </tr>
            <tr>
              <td>Postal Code</td>
              <td>: <?= $pay['zipcode'] ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="order_details_table">
      <h2>Order Details</h2>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Product</th>
              <th scope="col">Quantity</th>
              <th scope="col">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql2 = "SELECT a.*, a.quantity AS order_quant, b.prodname FROM orderdetail a INNER JOIN products b ON a.prod_no=b.prod_no WHERE a.order_id = '$order_id'";
            $result2 = $conn->query($sql2);
            $payment = $result1->fetch_assoc();
            ?>
            <?php
            while ($row = $result2->fetch_assoc()) {
            ?>
              <tr>
                <td>
                  <p><?= $row['prodname'] ?></p>
                </td>
                <td>
                  <h5>x <?= $row['order_quant'] ?></h5>
                </td>
                <td>
                  <p>₱<?= number_format($row['price_each'], 2) ?></p>
                </td>
              </tr>
            <?php } ?>

            <tr>
              <td>
                <h4>Subtotal</h4>
              </td>
              <td>
                <h5></h5>
              </td>
              <td>
                <p>₱<?= number_format($pay['amount'], 2) ?></p>
              </td>
            </tr>
            <tr>
              <td>
                <h4>Shipping</h4>
              </td>
              <td>
                <h5></h5>
              </td>
              <td>
                <p>Free Shipping</p>
              </td>
            </tr>
            <tr>
              <td>
                <h4>Total</h4>
              </td>
              <td>
                <h5></h5>
              </td>
              <td>
                <h4>₱<?= number_format($pay['amount'], 2) ?></h4>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<!--================End Order Details Area =================-->


<?php include('layouts/footer.php') ?>