<?php include('layouts/header.php');
if (!isset($_SESSION['userid'])) {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
}
?>
<section class="blog-banner-area" id="category">
    <div class="container h-100">
        <div class="blog-banner">
            <div class="text-center">
                <h1>My Order</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Order</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<section class="cart_area">
    <div class="container">
        <form action="" method="get">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?> value="Pending">Pending</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Order Confirmed') echo 'selected'; ?> value="Order Confirmed">Order Confirmed</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'On the Way') echo 'selected'; ?> value="On the Way">On the Way</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Delivered') echo 'selected'; ?> value="Delivered">Delivered</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Completed') echo 'selected'; ?> value="Completed">Completed</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?> value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </form>
        <div class="cart_inner">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date Ordered</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Initialize the base query and parameters array
    $base_query = "
        SELECT 
            orders.order_id,
            orders.order_datetime,
            orders.status,
            COUNT(orderdetail.order_id) AS item_count,
            SUM(orderdetail.price_each) AS total_price
        FROM 
            orders 
        JOIN 
            orderdetail 
        ON 
            orders.order_id = orderdetail.order_id 
        WHERE 
            orders.userid = ?
        ";
    $params = [$userid];
    $param_types = "i"; // "i" for integer (userid)

    // Check if status is set and update the query and parameters accordingly
    if (!empty($status)) {
        $base_query .= " AND orders.status = ?";
        $params[] = $status;
        $param_types .= "s"; // "s" for string (status)
    }

    // Append the GROUP BY and ORDER BY clauses
    $base_query .= "
        GROUP BY 
            orders.order_id, 
            orders.order_datetime, 
            orders.status
        ORDER BY 
            orders.status DESC
    ";

    // Prepare the statement
    $stmt = $conn->prepare($base_query);

    // Bind the parameters
    $stmt->bind_param($param_types, ...$params);

    // Execute the query
    $stmt->execute();
    $cart_result = $stmt->get_result();
    ?>
                            <?php while ($row = $cart_result->fetch_assoc()) {
                                $order_datetime = $row['order_datetime']; // Example: '2023-05-26 13:00:00'
                                $timestamp = strtotime($order_datetime);
                                $formatted_date = date('F j, Y \a\t g:ia', $timestamp);
                            ?>
                                <tr>
                                    <td>
                                        <?= $row['order_id']; ?>
                                    </td>
                                    <td>
                                        <?= $formatted_date ?>
                                    </td>
                                    <td>
                                        <?= $row['status']; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#track<?= $row['order_id'] ?>">Info</button>
                                            <?php
                                            if ($row['status'] == 'Pending') { ?>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $row['order_id'] ?>">Cancel</button>
                                            <?php   }
                                            ?>
                                            <?php
                                            if ($row['status'] == 'Delivered') { ?>
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmdelivered<?= $row['order_id'] ?>">Order Confirm</button>
                                            <?php   }
                                            ?>
                                        </div>
                                        <div class="modal fade" id="delete<?= $row['order_id'] ?>" tabindex="-1" aria-labelledby="confirmdeliveredLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Cancel Order?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="" method="post">
                                                        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                                        <div class="modal-body">
                                                            Are you sure you want to cancel Order #<?= $row['order_id'] ?>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger" name="cancel_order">Cancel Order</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="confirmdelivered<?= $row['order_id'] ?>" tabindex="-1" aria-labelledby="confirmdeliveredLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmdeliveredLabel">Delivery Confirmation?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="" method="post">
                                                        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                                        <div class="modal-body">
                                                            Confirm Delivered Order #<?= $row['order_id'] ?>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger" name="confirm_order">Confirm Order</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="track<?= $row['order_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Order #<?= $row['order_id'] ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                $order_id =  $row['order_id'];
                                                $query = "SELECT o.order_id, o.order_datetime, o.status, i.prodname, i.price,od.price_each 
                                                        FROM orders o
                                                        JOIN orderdetail od ON o.order_id = od.order_id
                                                        JOIN products i ON od.prod_no = i.prod_no
                                                        WHERE o.order_id = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $order_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $order = $result->fetch_assoc();

                                                $formatted_date = date('F d, Y', strtotime($order['order_datetime']));

                                                $status = $order['status'];
                                                $ordered_class = $preparing_class = $on_the_way_class = $delivered_class = '';
                                                if ($status == 'Pending') {
                                                    $ordered_class = 'active';
                                                } elseif ($status == 'Order Confirmed') {
                                                    $ordered_class = $preparing_class = 'active';
                                                } elseif ($status == 'On the Way') {
                                                    $ordered_class = $preparing_class = $on_the_way_class = 'active';
                                                } elseif ($status == 'Delivered' || $status == 'Completed') {
                                                    $ordered_class = $preparing_class = $on_the_way_class = $delivered_class = 'active';
                                                }

                                                // Fetch all items for the order
                                                $query_items = "SELECT i.*, od.price_each, od.quantity AS order_quantity
                                            FROM orderdetail od 
                                            JOIN products i ON od.prod_no = i.prod_no 
                                            WHERE od.order_id = ?";
                                                $stmt_items = $conn->prepare($query_items);
                                                $stmt_items->bind_param("i", $order_id);
                                                $stmt_items->execute();
                                                $items_result = $stmt_items->get_result();
                                                ?>
                                                <div class="card1-body">
                                                    <article class="card1 border-0">
                                                        <div class="card1-body row">
                                                            <div class="col"> <strong>Status:</strong> <br> <?= $row['status'] ?> </div>
                                                            <div class="col"> <strong>Order #:</strong> <br> <?= $row['order_id'] ?> </div>
                                                        </div>
                                                    </article>
                                                    <div class="track">
                                                        <div class="step <?= $ordered_class ?>"> <span class="icon"> <i class="fa fa-spinner"></i> </span> <span class="text">Pending</span> </div>
                                                        <div class="step <?= $preparing_class ?>"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">
                                                                Order Confirmed</span> </div>
                                                        <div class="step  <?= $on_the_way_class ?>"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the
                                                                way </span> </div>
                                                        <div class="step <?= $delivered_class ?>"> <span class="icon"> <i class="fa fa-archive"></i> </span> <span class="text">Ready to Receive</span> </div>
                                                    </div>
                                                    <hr>
                                                    <ul class="row">
                                                        <?php while ($item = $items_result->fetch_assoc()) : ?>
                                                            <li class="col-md-4">
                                                                <figure class="itemside mb-3">
                                                                    <div class="aside"><img src="prodimg/<?= $item['image'] ?>" class="img-sm border"></div>
                                                                    <figcaption class="info align-self-center">
                                                                        <p class="title"><?= $item['prodname'] ?> <br> x<?= $item['order_quantity'] ?></p> <span class="text-muted">₱<?= number_format($item['price_each'], 2) ?>
                                                                        </span>
                                                                    </figcaption>
                                                                </figure>
                                                            </li>
                                                        <?php endwhile; ?>

                                                    </ul>
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php

                        } else {
                            echo '<meta http-equiv="refresh" content="0;url=login.php">';
                            exit;
                        }


                        ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];

    $query = "SELECT status FROM orders WHERE order_id = '$order_id'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order['status'] == 'Pending') {
        $update_query = "UPDATE orders SET status = 'Cancelled' WHERE order_id = '$order_id'";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->execute();
        $_SESSION['alert'] = "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Cancelled',
                    text: 'Order #$order_id has been canceled.',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/myorder.php';
                    }
                });
            </script>";
    } else {
        $_SESSION['alert'] = "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Order #$order_id cannot be canceled as it is already in progress.',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/myorder.php';
                    }
                });
            </script>";
    }
    echo '<meta http-equiv="refresh" content="0;url=myorder.php">';
    exit();
}

if (isset($_POST['confirm_order'])) {
    $order_id = $_POST['order_id'];

    // Update the order status to 'Delivered'
    $update_query = "UPDATE orders SET status = 'Completed' WHERE order_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("s", $order_id);
    $update_stmt->execute();
    $_SESSION['alert'] = "<script>
            Swal.fire({
                icon: 'success',
                title: 'Completed',
                text: 'Order #$order_id has been marked as delivered.',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/myorder.php';
                }
            });
        </script>";
    echo '<meta http-equiv="refresh" content="0;url=myorder.php">';
    exit();
}
$stmt->close();
$conn->close();
?>
<?php include('layouts/footer.php') ?>