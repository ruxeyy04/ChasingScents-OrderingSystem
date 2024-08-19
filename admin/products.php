<?php include('layouts/header.php') ?>
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="dashboard_header mb_50">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="dashboard_header_title">
                            <h3> Products</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard_breadcam text-end">
                            <p><a href="index-2.html">Dashboard</a> <i class="fas fa-caret-right"></i> Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Table</h4>
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
                        <div class="add_button ms-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#addItem" class="btn_1">Add Product</a>
                        </div>
                    </div>
                </div>
                <div class="QA_table mb_30 table-responsive">

                    <table class="table lms_table_active">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Gender</th>
                                <th scope="col">ML</th>
                                <!--<th scope="col">Quantity</th>-->
                                <th scope="col">Price</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            <?php
                            // Assuming you have sanitized the input to prevent SQL injection
                            $search = isset($_GET['search']) ? $_GET['search'] : '';

                            $total_rows_sql = "SELECT COUNT(*) AS total FROM products";
                            if (!empty($search)) {
                                // Modify the total_rows_sql to include the search filter
                                $total_rows_sql .= " WHERE prodname LIKE '%$search%'"; // Modify this according to your search criteria
                            }

                            $total_rows_result = mysqli_query($conn, $total_rows_sql);
                            $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                            $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                            $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                            $pages = ceil($total_rows / $limit);
                            $offset = ($current_page - 1) * $limit;

                            // Sort by prod_no
                            $sort_column = 'status';
                            $sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

                            $sql = "SELECT products.* FROM products";

                            if (!empty($search)) {
                                // Add WHERE clause for search if a search query is provided
                                $sql .= " WHERE prodname LIKE '%$search%'"; // Modify this according to your search criteria
                            }

                            $sql .= " ORDER BY $sort_column $sort_order
          LIMIT $limit OFFSET $offset";

                            $result = mysqli_query($conn, $sql);

                            $table_rows = '';
                            

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $modal_id = 'editItem_' . $row['prod_no'];
                            ?>
                                    <tr>
                                        <th scope="row"> <a href="#" class="question_content"><?= $row['prod_no'] ?></a></th>
                                        <td><?= $row['prodname'] ?></td>
                                        <td><?= $row['type'] ?></td>
                                        <td><?= $row['gender'] ?></td>
                                        <td><?= $row['ml'] ?>ml</td>
                                        <!--<td><?= $row['quantity'] ?></td>-->
                                        <td><?= $row['price'] ?></td>
                                        <td class="<?php echo ($row['status'] == 'Unavailable') ? 'text-danger fw-bold' : 'text-success fw-bold'; ?>">
                                            <?= ($row['status'] == 'Unavailable') ? 'Not Available' : 'Available'; ?>
                                        </td>

                                        <td><?= date('F j, Y', strtotime($row['created_at'])) ?></td>
                                        <td class="text-center d-flex"><button class="btn btn-info me-1" data-bs-toggle="modal" data-bs-target="#<?= $modal_id ?>">Edit</button>
                                            <form method="post" action="" class="delete-form"><input type="hidden" name="delete" value="<?= $row['prod_no'] ?>"><button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button></form>
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    <div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="<?= $modal_id ?>Label" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="<?= $modal_id ?>Label">Edit Product</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="prod_no" value="<?= $row['prod_no'] ?>">
                                                        <input type="hidden" name="existing_image" value="<?= $row['image'] ?>">
                                                        <div class="form-floating mb-4">
                                                            <input type="text" name="prod_name" value="<?= $row['prodname'] ?>" class="form-control form-control-sm" required />
                                                            <label for="prod_name">Product</label>
                                                        </div>
                                                        <div class="form-floating mb-4">
                                                            <input type="number" name="price" value="<?= $row['price'] ?>" step=".01" class="form-control form-control-sm" required />
                                                            <label for="price">Price</label>
                                                        </div>
                                                        <div class="form-floating mb-4">
                                                            <input type="number" name="quantity" value="<?= $row['quantity'] ?>" class="form-control form-control-sm" required />
                                                            <label for="quantity">Quantity</label>
                                                        </div>
                                                        <div class="form-floating mb-4">
                                                            <input type="number" name="ml" value="<?= $row['ml'] ?>" class="form-control form-control-sm" required />
                                                            <label for="quantity">ML</label>
                                                        </div>
                                                        <div class="form-floating mb-4">
                                                            <select class="form-select form-select-sm" name="type" id="type" required>
                                                                <option value="Citrus" <?= $row['type'] == "Citrus" ? 'selected' : '' ?>>Citrus</option>
                                                                <option value="Floral" <?= $row['type'] == "Floral" ? 'selected' : '' ?>>Floral</option>
                                                                <option value="Fresh" <?= $row['type'] == "Fresh" ? 'selected' : '' ?>>Fresh</option>
                                                                <option value="Vanilla" <?= $row['type'] == "Vanilla" ? 'selected' : '' ?>>Vanilla</option>
                                                                <option value="Woody" <?= $row['type'] == "Woody" ? 'selected' : '' ?>>Woody</option>
                                                            </select>
                                                            <label for="type">Type</label>
                                                        </div>
                                                        <div class="form-floating mb-4">
                                                            <select class="form-select form-select-sm" name="gender" id="gender" required>
                                                                <option value="Male" <?= $row['gender'] == "Male" ? 'selected' : '' ?>>Male</option>
                                                                <option value="Female" <?= $row['gender'] == "Female" ? 'selected' : '' ?>>Female</option>
                                                            </select>
                                                            <label for="type">Gender</label>
                                                        </div>
                                                        <div class="form-floating mb-4">
                                                            <textarea class="form-control form-control-sm" name="desc" style="height: 100px" required><?= $row['description'] ?></textarea>
                                                            <label for="desc">Description</label>
                                                        </div>
                                                        <div class="form-floating mb-4">
                                                            <select class="form-select form-select-sm" name="status" id="status" required>
                                                                <option value="Available" <?= $row['status'] == "Available" ? 'selected' : '' ?>>Available</option>
                                                                <option value="Unavailable" <?= $row['status'] == "Unavailable" ? 'selected' : '' ?>>Unavailable</option>
                                                            </select>
                                                            <label for="type">Status</label>
                                                        </div>
                                                        <div class="form-group mb-4">
                                                            <label>Product Image</label>
                                                            <div class="input-group col-xs-12">
                                                                <span class="input-group-append">
                                                                    <input type="file" class="file-upload-browse" id="productImages" name="photo" accept="image/*" onchange="previewImagee<?= $modal_id ?>(event)">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-center">
                                                        <img id="imagePreviewe<?= $modal_id ?>" src="../prodimg/<?= $row['image'] ?>" class="img-thumbnail" alt="Image Preview">
                                                    </div>
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                    <script>
                function previewImagee<?= $modal_id ?>(event) {
                    var input = event.target;
                    var imagePreview = document.getElementById('imagePreviewe<?= $modal_id ?>');

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
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No data available</td></tr>';
                            }
                            ?>

                        </tbody>
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
        $upload_dir = 'prodimg/'; // Adjust directory path as necessary
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
$status = mysqli_real_escape_string($conn, $_POST['status']);
    if ($_FILES['photo']['name'] != '') {
        $file_name = $_FILES['photo']['name'];
        $file_temp = $_FILES['photo']['tmp_name'];
        $file_type = $_FILES['photo']['type'];
        $file_size = $_FILES['photo']['size'];
        $file_error = $_FILES['photo']['error'];

        $upload_dir = 'prod_img/';
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
    $sql = "UPDATE products SET prodname = '$prod_name', `description` = '$desc', `type` = '$type', price = '$price', quantity = '$quant', ml = '$ml', gender = '$gender', `image` = '$prod_img', status = '$status' WHERE prod_no = '$prod_no'";

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