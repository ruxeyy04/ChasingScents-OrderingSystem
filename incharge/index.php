<?php include('layouts/header.php') ?>
<div class="container-fluid p-0 sm_padding_15px">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="dashboard_header mb_50">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="dashboard_header_title">
                            <h3> Chasing Scents</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard_breadcam text-end">
                            <p><a href="index-2.html">Dashboard</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xl-8">
            <div class="white_box mb_30 table-responsive">
                <div class="box_header ">
                    <div class="main-title">
                        <h3 class="mb-0">Recent Orders</h3>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Order#</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Total Items</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // Calculate total rows
                        $total_rows_sql = "
                        SELECT COUNT(DISTINCT orders.order_id) AS total 
                        FROM orders
                        INNER JOIN userinfo ON orders.userid = userinfo.userid
                        ";


                        $total_rows_result = mysqli_query($conn, $total_rows_sql);
                        $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];


                        $total_rows_result = mysqli_query($conn, $total_rows_sql);
                        $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                        $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                        $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                        $pages = ceil($total_rows / $limit);
                        $offset = ($current_page - 1) * $limit;

                        // Sort by order_id
                        $sort_column = 'order_id';
                        $sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

                        $sql = "
                                    SELECT 
                                        orders.order_id,
                                        CONCAT(userinfo.fname, ' ', userinfo.lname) AS fullname,COUNT(orderdetail.order_id) AS total_items,
                                        payment.amount AS total_amount,
                                        payment.payment_type,
                                        orders.order_datetime,
                                        orders.status
                                    FROM 
                                        orders
                                    INNER JOIN 
                                        userinfo ON orders.userid = userinfo.userid
                                    INNER JOIN 
                                        payment ON orders.userid = payment.userid
                                    INNER JOIN 
                                        orderdetail  ON orders.order_id = orderdetail.order_id
                                ";



                        $sql .= " GROUP BY orders.order_id
                            ORDER BY $sort_column $sort_order
                            LIMIT $limit OFFSET $offset";

                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $order_id = $row['order_id'];
                                $fullname = $row['fullname'];
                                $total_amount = $row['total_amount'];
                                $payment_type = $row['payment_type'];
                                $order_datetime = date('F j, Y \a\t g:iA', strtotime($row['order_datetime']));
                                $status = $row['status'];
                                $totalitem = $row['total_items'];
                                $modal_id = 'updateOrder_' . $order_id;
                        ?>
                                <tr>
                                    <td><a href="#"><?= $order_id ?></a></td>
                                    <td><?= $fullname ?></td>
                                    <td><?= $totalitem ?></td>
                                    <td><?= $total_amount ?></td>
                                    <td><?= $status ?></td>

                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">No data available</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
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
        </div>
        <div class="col-lg-4">
            <div class="list_counter_wrapper white_box mb_30 p-0 card_height_100">
                <?php
                // Assuming you have already established a connection to your database

                // Count users
                $query = "SELECT COUNT(userid) AS user_count FROM userinfo";
                $result = mysqli_query($conn, $query);
                $user_count = mysqli_fetch_assoc($result)['user_count'];

                // Count orders
                $query = "SELECT COUNT(order_id) AS order_count FROM orders";
                $result = mysqli_query($conn, $query);
                $order_count = mysqli_fetch_assoc($result)['order_count'];

                // Calculate total sales
                $query = "SELECT SUM(amount) AS total_sales FROM payment";
                $result = mysqli_query($conn, $query);
                $total_sales = mysqli_fetch_assoc($result)['total_sales'];


                // Calculate total sales
                $query = "SELECT COUNT(status) AS completed_orders FROM orders WHERE status = 'Completed'";
                $result = mysqli_query($conn, $query);
                $completed_orders = mysqli_fetch_assoc($result)['completed_orders'];

                ?>
                <div class="single_list_counter">
                    <h3 class="deep_blue_2"><span class="counter deep_blue_2 "><?= $order_count ?></span></h3>
                    <p>Total Orders</p>
                </div>
                <div class="single_list_counter">
                    <h3 class="deep_blue_2">₱<span class="counter deep_blue_2"><?= number_format($total_sales, 2) ?></span></h3>
                    <p>Total Sales</p>
                </div>
                <div class="single_list_counter">
                    <h3 class="deep_blue_2"><span class="counter deep_blue_2"><?= $user_count ?></span></h3>
                    <p>Total Users</p>
                </div>
                <div class="single_list_counter">
                    <h3 class="deep_blue_2"><span class="counter deep_blue_2"><?= $completed_orders ?></span></h3>
                    <p>Completed Orders </p>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-12">
            <div class="white_box mb_30 table-responsive">
                <div class="box_header ">
                    <div class="main-title">
                        <h3 class="mb-0">Users</h3>
                    </div>
                </div>
                <table class="table table-hover">
                <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Usertype</th>
                            <th>Contact Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_rows_sql1 = "
                            SELECT COUNT(*) AS total 
                            FROM userinfo 
                        ";


                        $total_rows_result1 = mysqli_query($conn, $total_rows_sql1);
                        $total_rows1 = mysqli_fetch_assoc($total_rows_result1)['total'];

                        $limit1 = isset($_GET['limit']) ? $_GET['limit'] : 5;
                        $sorting1 = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                        $current_page1 = isset($_GET['pagee']) ? $_GET['pagee'] : 1;

                        $pages1 = ceil($total_rows1 / $limit1);
                        $offset1 = ($current_page1 - 1) * $limit1;

                        // Sort by user id
                        $sort_column1 = 'userinfo.userid';
                        $sort_order1 = ($sorting1 == 'desc') ? 'DESC' : 'ASC';

                        $sql1 = "
                            SELECT *
                            FROM userinfo
                        ";

                        $sql1 .= " ORDER BY $sort_column1 $sort_order1
                            LIMIT $limit1 OFFSET $offset1";

                        $result1 = mysqli_query($conn, $sql1);

                        $table_rows1 = '';

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result1)) {
                        ?>
                                <tr>
                                    <td><a href="#"><?= $row['userid'] ?></a></td>
                                    <td><?= $row['fname'] . ' ' . $row['lname'] ?></td>
                                    <td><?= $row['username'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['usertype'] ?></td>
                                    <td><?= $row['contact_number'] ?></td>

                                </tr>

                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">No data available</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $current_page1 == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagee' => $current_page1 - 1])); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $pages1; $i++) : ?>
                        <li class="page-item <?php echo $current_page1 == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagee' => $i])); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $current_page1 == $pages1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagee' => $current_page + 1])); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
        </div>
    </div>
</div>
<?php include('layouts/footer.php') ?>