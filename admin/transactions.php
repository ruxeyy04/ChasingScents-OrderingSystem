<?php include('layouts/header.php') ?>
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="dashboard_header mb_50">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="dashboard_header_title">
                            <h3> Transactions</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard_breadcam text-end">
                            <p><a href="index-2.html">Dashboard</a> <i class="fas fa-caret-right"></i> Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Transactions</h4>
                    <div class="box_right d-flex lms_block">
                        <div class="d-md-flex gap-4 align-items-center me-1">
                            <form class="mb-3 mb-md-0" method="GET" action="">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <select class="form-select" name="sort" onchange="this.form.submit()">
                                            <option <?php if (isset($_GET['sort']) && $_GET['sort'] == 'asc') echo 'selected'; ?> value="asc">Asc</option>
                                            <option <?php if (isset($_GET['sort']) && $_GET['sort'] == 'desc') echo 'selected'; ?> value="desc">Desc</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" name="limit" onchange="this.form.submit()">
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '5') echo 'selected'; ?> value="5">5</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '10') echo 'selected'; ?> value="10">10</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '20') echo 'selected'; ?> value="20">20</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '30') echo 'selected'; ?> value="30">30</option>
                                            <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '50') echo 'selected'; ?> value="50">50</option>
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
                                <th>Payment ID</th>
                                <th>Full Name</th>
                                <th>Payment Method</th>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $search = isset($_GET['search']) ? $_GET['search'] : '';

                            $total_rows_sql = "
                            SELECT COUNT(*) AS total 
                            FROM payment 
                            INNER JOIN userinfo ON payment.userid = userinfo.userid
                            ";
                            if (!empty($search)) {
                                $total_rows_sql .= " WHERE userinfo.username LIKE '%$search%' OR CONCAT(userinfo.fname, ' ', userinfo.lname) LIKE '%$search%'";
                            }

                            $total_rows_result = mysqli_query($conn, $total_rows_sql);
                            $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                            $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                            $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                            $pages = ceil($total_rows / $limit);
                            $offset = ($current_page - 1) * $limit;

                            // Sort by payment id
                            $sort_column = 'payment.pay_id';
                            $sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

                            $sql = "
                                SELECT payment.*, userinfo.username, userinfo.fname, userinfo.lname, orders.order_id, orders.status 
                                FROM payment 
                                INNER JOIN userinfo ON payment.userid = userinfo.userid
                                INNER JOIN orders ON payment.order_id = orders.order_id
                            ";

                            if (!empty($search)) {
                                // Add WHERE clause for search if a search query is provided
                                $sql .= " WHERE userinfo.username LIKE '%$search%' OR CONCAT(userinfo.fname, ' ', userinfo.lname) LIKE '%$search%'";
                            }

                            $sql .= " ORDER BY $sort_column $sort_order
                            LIMIT $limit OFFSET $offset";

                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td><a href="#"><?= $row['pay_id'] ?></a></td>
                                        <td>
                                            <?= $row['fname'] . ' ' . $row['lname'] ?>
                                        </td>
                                        <td><?= $row['payment_type'] ?></td>
                                        <td><?= $row['order_id'] ?></td>
                                        <td><?= $row['status'] ?></td>
                                        <td><?= date('F j, Y', strtotime($row['payment_date'])) ?></td>
                                        <td><?= $row['amount'] ?></td>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-floating mb-4">
                        <input type="text" name="fname" class="form-control form-control-sm" required />
                        <label for="fname">First Name</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="lname" class="form-control form-control-sm" required />
                        <label for="lname">Last Name</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="username" class="form-control form-control-sm" required />
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="email" name="email" class="form-control form-control-sm" required />
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="contact_number" class="form-control form-control-sm" required />
                        <label for="contact_number">Contact Number</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control form-control-sm" required />
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-select form-select-sm" name="gender" id="gender" required>
                            <option disabled selected>--- Select Gender ---</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <label for="gender">Gender</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-select form-select-sm" name="usertype" id="usertype" required>
                            <option disabled selected>--- Select Usertype ---</option>
                            <option value="Client">Client</option>
                            <option value="Incharge">Incharge</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <label for="usertype">Usertype</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    // Connect to the database (make sure $conn is defined somewhere before this block)
    // $conn = new mysqli($servername, $username, $password, $dbname);

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']); // Add contact number
    $gender = mysqli_real_escape_string($conn, $_POST['gender']); // Add gender
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $usertype = mysqli_real_escape_string($conn, $_POST['usertype']);


    // Insert into userinfo table
    $insert_info_query = "
        INSERT INTO userinfo (fname, lname, username, email, contact_number, gender, password, usertype) 
        VALUES ('$fname', '$lname', '$username', '$email', '$contact_number', '$gender', '$password', '$usertype')
    ";

    // Execute the query
    if (mysqli_query($conn, $insert_info_query)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'New user added successfully!',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'error',
            title: 'Error adding user info: " . mysqli_error($conn) . "',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $midname = mysqli_real_escape_string($conn, $_POST['midname']); // Add midname
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']); // Add contact number
    $gender = mysqli_real_escape_string($conn, $_POST['gender']); // Add gender
    $usertype = mysqli_real_escape_string($conn, $_POST['usertype']);
    $resetpassword = mysqli_real_escape_string($conn, $_POST['resetpassword']);

    // Construct the update query for userinfo
    $update_info_query = "
        UPDATE userinfo 
        SET fname = '$fname', midname = '$midname', lname = '$lname', username = '$username', 
            email = '$email', contact_number = '$contact_number', gender = '$gender', usertype = '$usertype'";

    // If resetpassword is provided, include it in the update query without hashing
    if (!empty($resetpassword)) {
        $update_info_query .= ", password = '$resetpassword'";
    }

    $update_info_query .= " WHERE userid = '$userid'";

    // Execute the query
    if (mysqli_query($conn, $update_info_query)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'User information updated successfully!',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'error',
            title: 'Error updating user information: " . mysqli_error($conn) . "',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    }
}
?>
<?php include('layouts/footer.php') ?>