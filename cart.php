<?php include('layouts/header.php') ?>
<?php
// Prepare and execute the SQL statement
$sql = "SELECT COUNT(*) AS count FROM carts WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Check if the order ID exists
if ($row['count'] == 0) { ?>
    <!-- ================ start banner area ================= -->
    <section class="blog-banner-area" id="category">
        <div class="container h-100">
            <div class="blog-banner">
                <div class="text-center">
                    <h1>Your Cart is Empty</h1>
                    <button class="button primary-btn mt-3" onclick="location.replace('products.php')">Explore</button>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ end banner area ================= -->


<?php } else { ?>
    <section class="blog-banner-area" id="category">
        <div class="container h-100">
            <div class="blog-banner">
                <div class="text-center">
                    <h1>Cart List</h1>
                    <nav aria-label="breadcrumb" class="banner-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ end banner area ================= -->
    <!--================Cart Area =================-->
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
        <section class="cart_area">
            <div class="container">
                <div class="cart_inner">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="" method="post">

                                
                                <?php while ($row = $cart_result->fetch_assoc()) {
                                    $item_total = $row['price'] * $row['cart_quantity'];
                                    $subtotal += $item_total;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="prodimg/<?= $row['image'] ?>" alt="" height="100" width="100">
                                                </div>
                                                <div class="media-body">
                                                    <p><?= $row['prodname'] ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>₱<?= $row['price'] ?></h5>
                                        </td>
                                        <td>
                                            <div class="product_count">
                                                <span class="mr-3">Quantity</span>
                                                <input type="number" name="quantity[<?= $row['cart_id'] ?>]" id="sst<?= $row['cart_id'] ?>" maxlength="12" value="<?= $row['cart_quantity'] ?>" title="Quantity:" class="input-text qty" min="1">
                                                <button class="increase items-count" type="button" onclick="increaseQuantity(<?= $row['cart_id'] ?>)">
                                                    <i class="lnr lnr-chevron-up"></i>
                                                </button>
                                                <button class="reduced items-count" type="button" onclick="decreaseQuantity(<?= $row['cart_id'] ?>)">
                                                    <i class="lnr lnr-chevron-down"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>₱<?php echo number_format($item_total, 2); ?></h5>
                                        </td>
                                        <td>
                                            <div class="checkout_btn_inner d-flex align-items-center">
                                                <a class="gray_btn text-success" href="addcart.php?prod_no=<?= $row['prod_no'] ?>"><i class="ti-shopping-cart"></i></a>
                                                <a class="gray_btn text-danger" href="removecart.php?cart_id=<?= $row['cart_id'] ?>"><i class="ti-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <tr class="bottom_button">
                                    <td>
                                        <button class="button" type="submit" name="update_cart">Update Cart</button>
                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                </form>
                                <tr>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <h5>Subtotal</h5>
                                    </td>
                                    <td>
                                        <h5>₱<?php echo number_format($subtotal, 2); ?></h5>
                                    </td>
                                </tr>
                                <tr class="shipping_area">
                                    <td class="d-none d-md-block">

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <h5>Shipping</h5>
                                    </td>
                                    <td>
                                        <div class="shipping_box">
                                            <ul class="list">
                                                <li class="active"><a href="#!">Free Shipping</a></li>
                                            </ul>

                                        </div>
                                    </td>
                                </tr>
                                <tr class="out_button_area">
                                    <td class="d-none-l">

                                    </td>
                                    <td class="">

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>

                                    <td>
                                        <div class="checkout_btn_inner d-flex align-items-center">
                                            <a class="gray_btn" href="products.php">Continue Shopping</a>
                                            <a class="primary-btn ml-2" href="checkout.php">Proceed to checkout</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    <?php
        if (isset($_POST['update_cart'])) {
            foreach ($_POST['quantity'] as $cart_id => $quantity) {

                $update_stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE cart_id = ? AND userid = ?");
                $update_stmt->bind_param("iii", $quantity, $cart_id, $userid);
                $update_stmt->execute();
                $update_stmt->close();
            }
            $_SESSION['alert'] = "<script>
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'Successfully Updated Cart',
                                        });
                                    </script>";
            echo '<meta http-equiv="refresh" content="0;url=cart.php">';
            exit;
        }
    } else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit;
    } ?>
    <?php
    ?>

    <!--================End Cart Area =================-->
<?php }
?>




<script>
    function increaseQuantity(val) {
        var result = document.getElementById('sst' + val);
        var sst = parseInt(result.value);
        if (!isNaN(sst)) {
            result.value = sst + 1;
        }
        return false;
    }

    function decreaseQuantity(val) {
        var result = document.getElementById('sst' + val);
        var sst = parseInt(result.value);
        if (!isNaN(sst) && sst > 1) {
            result.value = sst - 1;
        }
        return false;
    }
</script>

<?php include('layouts/footer.php') ?>