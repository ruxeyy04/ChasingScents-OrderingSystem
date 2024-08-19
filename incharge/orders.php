<?php include('layouts/header.php') ?>
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="dashboard_header mb_50">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="dashboard_header_title">
                            <h3> Orders</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard_breadcam text-end">
                            <p><a href="index-2.html">Dashboard</a> <i class="fas fa-caret-right"></i> Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Order Table</h4>
                    <div class="box_right d-flex lms_block">
                        <div class="d-md-flex gap-4 align-items-center me-1">
                            <form class="mb-3 mb-md-0" method="GET" action="">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <select class="form-select" name="sort" onchange="this.form.submit()">
                                        <option <?php if (isset($_GET['sort']) && $_GET['sort'] == 'asc') echo 'selected'; ?> value="asc">Asc</option>
                                        <option <?php if (isset($_GET['sort']) && $_GET['sort'] == 'desc') echo 'selected'; ?> value="desc">Desc</option>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" name="limit" onchange="this.form.submit()">
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '5') echo 'selected'; ?> value="5">5</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '10') echo 'selected'; ?> value="10">10</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '20') echo 'selected'; ?> value="20">20</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '30') echo 'selected'; ?> value="30">30</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '50') echo 'selected'; ?> value="50">50</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
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

                        </div>
                    </div>
                </div>
                <div class="QA_table mb_30 table-responsive">

                    <table class="table lms_table_active">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Full Name</th>
                                <th>Total</th>
                                <th>Payment Method</th>
                                <th>Date</th>
                                <th>Order Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $status = isset($_GET['status']) ? $_GET['status'] : '';

                // Calculate total rows
                $total_rows_sql = "
    SELECT COUNT(DISTINCT orders.order_id) AS total 
    FROM orders
    INNER JOIN userinfo ON orders.userid = userinfo.userid
";
                $where_clauses = [];

                if (!empty($search)) {
                    $where_clauses[] = "(
        orders.order_id LIKE '%$search%' OR 
        CONCAT(userinfo.fname, ' ', userinfo.lname) LIKE '%$search%'
    )";
                }

                if (!empty($status)) {
                    $where_clauses[] = "orders.status = '$status'";
                }

                if (!empty($where_clauses)) {
                    $total_rows_sql .= " WHERE " . implode(' AND ', $where_clauses);
                }

                $total_rows_result = mysqli_query($conn, $total_rows_sql);
                $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'asc';
                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                $pages = ceil($total_rows / $limit);
                $offset = ($current_page - 1) * $limit;

                // Sort by order_id, order_datetime, and custom status order
                $sort_order = ($sorting == 'asc') ? 'asc' : 'desc';

                // Retrieve orders with buyer's full name, total amount, payment method, order date, and status
                $sql = "
                        SELECT 
                            orders.order_id, 
                            CONCAT(userinfo.fname, ' ', userinfo.lname) AS fullname, 
                            payment.amount AS total_amount, 
                            payment.payment_type, 
                            orders.order_datetime, 
                            orders.status 
                        FROM 
                            orders 
                        INNER JOIN 
                            userinfo 
                        ON 
                            orders.userid = userinfo.userid 
                        INNER JOIN 
                            payment 
                        ON 
                            orders.order_id = payment.order_id 
                        ";

                                        if (!empty($where_clauses)) {
                                            $sql .= " WHERE " . implode(' AND ', $where_clauses);
                                        }

                                        $sql .= "
                            GROUP BY orders.order_id
                            ORDER BY 
                                FIELD(orders.status, 'Pending', 'Order Confirmed', 'On the Way', 'Delivered', 'Completed', 'Cancelled') $sort_order,
                                orders.order_datetime desc
                            LIMIT $limit OFFSET $offset
                        ";

                // echo $sql;
                $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $order_id = $row['order_id'];
                                    $fullname = $row['fullname'];
                                    $total_amount = $row['total_amount'];
                                    $payment_type = $row['payment_type'];
                                    $order_datetime = date('F j, Y \a\t g:iA', strtotime($row['order_datetime']));
                                    $status = $row['status'];
                                    $modal_id = 'updateOrder_' . $order_id;
                            ?>
                                    <tr>
                                        <td><a href="#"><?= $order_id ?></a></td>
                                        <td><?= $fullname ?></td>
                                        <td>₱<?= number_format($total_amount, 2) ?></td>
                                        <td><?= $payment_type ?></td>
                                        <td><?= $order_datetime ?></td>
                                        <td><?= $status ?></td>
                                        <td class="text-center d-flex">
                                            <button class="btn btn-success me-1" data-bs-toggle="modal" data-bs-target="#<?= $modal_id ?>" <?= $status == 'Completed' || $status == 'Cancelled'  ? 'disabled' : '' ?>>Update</button>
                                        </td>
                                    </tr>

                                    <!-- Modal for updating order -->
                                    <div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Update Order #<?= $order_id ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action="">
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Order Status</label>
                                                            <select class="form-control" name="status">
                                                                <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?> <?= $status == 'Order Confirmed' || $status == 'On the Way' || $status == 'Delivered' || $status == 'Cancelled' ? 'disabled' : '' ?>>Pending</option>
                                                                <option value="Order Confirmed" <?= $status == 'Order Confirmed' ? 'selected' : '' ?> <?= $status == 'On the Way' || $status == 'Delivered' || $status == 'Cancelled' ? 'disabled' : '' ?>>Order Confirmed</option>
                                                                <option value="On the Way" <?= $status == 'On the Way' ? 'selected' : '' ?> <?= $status == 'Delivered' || $status == 'Cancelled' ? 'disabled' : '' ?>>On the Way</option>
                                                                <option value="Delivered" <?= $status == 'Delivered' ? 'selected' : '' ?> <?= $status == 'Cancelled' ? 'disabled' : '' ?>>Delivered</option>
                                                                <option value="Cancelled" <?= $status == 'Cancelled' ? 'selected' : '' ?>  <?= $status == 'Order Confirmed' || $status == 'On the Way' || $status == 'Delivered' || $status == 'Cancelled' ? 'disabled' : '' ?> >Cancelled</option>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                                        <button type="submit" class="btn btn-primary" name="update_order">Save changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">No data available</td></tr>';
                            }
                            ?>
                        </tbody>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_order'])) {
                            $order_id = $_POST['order_id'];
                            $status = $_POST['status'];

                            $update_sql = "UPDATE orders SET status = ? WHERE order_id = ?";
                            if ($stmt = mysqli_prepare($conn, $update_sql)) {
                                mysqli_stmt_bind_param($stmt, 'ss', $status, $order_id);
                                if (mysqli_stmt_execute($stmt)) {
                                    $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Order status updated successfully.',
                                });
                            </script>";
                                    echo '<meta http-equiv="refresh" content="0;url=orders.php">';
                                    exit();
                                } else {
                                    $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Error updating order status: " . mysqli_error($conn) . "',
                                });
                            </script>";
                                    echo '<meta http-equiv="refresh" content="0;url=orders.php">';
                                    exit();
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                $_SESSION['alert'] = "<script>
                            Toast.fire({
                                icon: 'success',
                                title: 'Error preparing statement: " . mysqli_error($conn) . "',
                            });
                        </script>";
                                echo '<meta http-equiv="refresh" content="0;url=orders.php">';
                                exit();
                            }
                        }
                        ?>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page - 1])); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $pages; $i++) : ?>
                <li class="page-item <?php echo $current_page == $i ? 'active' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo $current_page == $pages ? 'disabled' : ''; ?>">
                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page + 1])); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="addItemTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemTitle">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-floating mb-4">
                        <input type="text" name="prod_name" class="form-control form-control-sm" required />
                        <label for="prod_name">Product</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="number" name="price" step=".01" class="form-control form-control-sm" required />
                        <label for="price">Price</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="number" name="quantity" class="form-control form-control-sm" required />
                        <label for="quantity">Quantity</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="number" name="ml" class="form-control form-control-sm" required />
                        <label for="quantity">ML</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-select form-select-sm" name="type" id="type" required>

                            <option value="Citrus">Citrus</option>
                            <option value="Floral">Floral</option>
                            <option value="Fresh">Fresh</option>
                            <option value="Vanilla">Vanilla</option>
                            <option value="Woody">Woody</option>
                        </select>
                        <label for="type">Type</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-select form-select-sm" name="gender" id="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <label for="type">Gender</label>
                    </div>
                    <div class="form-floating mb-4">
                        <textarea class="form-control form-control-sm" name="desc" style="height: 100px" required></textarea>
                        <label for="desc">Description</label>
                    </div>
                    <div class="form-group mb-4">
                        <label>Product Image</label>
                        <div class="input-group col-xs-12">
                            <span class="input-group-append">
                                <input type="file" class="file-upload-browse" id="productImages" name="photo" accept="image/*" onchange="previewImage(event)">
                            </span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <img id="imagePreview" class="img-thumbnail" alt="Image Preview" style="display: none;">
                    </div>

                    <script>
                        function previewImage(event) {
                            var input = event.target;
                            var imagePreview = document.getElementById('imagePreview');

                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    imagePreview.src = e.target.result;
                                    imagePreview.style.display = 'block';
                                }
                                reader.readAsDataURL(input.files[0]);
                            } else {
                                imagePreview.style.display = 'none';
                            }
                        }
                    </script>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-primary">Save changes</button>
                </div>
            </form>



        </div>
    </div>
</div>
<?php
if (isset($_POST['save'])) {

    // Check if a file is uploaded
    if ($_FILES['photo']['name'] != '') {
        // File uploaded, handle file processing
        $file_name = $_FILES['photo']['name'];
        $file_temp = $_FILES['photo']['tmp_name'];
        $file_type = $_FILES['photo']['type'];
        $file_size = $_FILES['photo']['size'];
        $file_error = $_FILES['photo']['error'];

        // Move uploaded file to desired location
        $upload_dir = '../prodimg/'; // Adjust directory path as necessary
        $target_file = $upload_dir . basename($file_name);

        // Move the file to the uploads directory
        if (move_uploaded_file($file_temp, $target_file)) {
            $prod_img = basename($file_name);
        } else {
            // Handle file upload error
            echo '<script>alert("Sorry, there was an error uploading your file."); window.location.replace("products.php");</script>';
            exit; // Terminate script execution
        }
    } else {
        $prod_img = "default.jpg";
    }
    // Retrieve other form data
    $prod_name = mysqli_real_escape_string($conn, $_POST['prod_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quant = mysqli_real_escape_string($conn, $_POST['quantity']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $ml = mysqli_real_escape_string($conn, $_POST['ml']);
    $sql = "INSERT INTO `products`(`prodname`, `description`, `type`, `price`, `quantity`, `ml`, `gender`, `image`, `created_at`) VALUES ('$prod_name', '$desc', '$type', '$price', '$quant', '$ml','$gender','$prod_img', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("New Product inserted successfully."); window.location.replace("products.php");</script>';
    } else {
        echo '<script>alert("Error: ' . $sql . '<br>' . mysqli_error($conn) . '"); window.location.replace("products.php");</script>';
    }

    mysqli_close($conn);
}
if (isset($_POST['update'])) {
    // Retrieve form data
    $prod_no = $_POST['prod_no'];
    $prod_name = mysqli_real_escape_string($conn, $_POST['prod_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quant = mysqli_real_escape_string($conn, $_POST['quantity']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $ml = mysqli_real_escape_string($conn, $_POST['ml']);

    if ($_FILES['photo']['name'] != '') {
        $file_name = $_FILES['photo']['name'];
        $file_temp = $_FILES['photo']['tmp_name'];
        $file_type = $_FILES['photo']['type'];
        $file_size = $_FILES['photo']['size'];
        $file_error = $_FILES['photo']['error'];

        $upload_dir = '../prod_img/';
        $target_file = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_temp, $target_file)) {
            $prod_img = basename($file_name);
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    } else {
        $prod_img = $_POST['existing_image'];
    }
    // `products`(`prod_no`, `prodname`, `description`, `type`, `price`, `quantity`, `ml`, `gender`, `image`, `created_at`) 
    $sql = "UPDATE products SET prodname = '$prod_name', `description` = '$desc', `type` = '$type', price = '$price', quantity = '$quant', ml = '$ml', gender = '$gender', `image` = '$prod_img' WHERE prod_no = '$prod_no'";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Product updated successfully."); window.location.replace("products.php");</script>';
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
if (isset($_POST['delete'])) {
    $item_id = $_POST['delete'];
    $delete_sql = "DELETE FROM products WHERE prod_no = $item_id";
    if (mysqli_query($conn, $delete_sql)) {
        echo '<script>alert("Product deleted successfully."); window.location.replace("products.php");</script>';
    } else {
        echo '<script>alert("Error deleting Product."); window.location.replace("products.php");</script>';
    }
}

?>
<?php include('layouts/footer.php') ?>