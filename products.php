<?php include('layouts/header.php') ?>
<!-- ================ start banner area ================= -->
<section class="blog-banner-area" id="category">
  <div class="container h-100">
    <div class="blog-banner">
      <div class="text-center">
        <h1>Products</h1>
        <nav aria-label="breadcrumb" class="banner-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>
<!-- ================ end banner area ================= -->
<?php
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
$ml = isset($_GET['ml']) ? $_GET['ml'] : '';

$total_rows_sql = "SELECT COUNT(*) AS total FROM products ";

if (!empty($search)) {
  $total_rows_sql .= " WHERE prodname LIKE '%$search%'";
}
if (!empty($type)) {
  $total_rows_sql .= " WHERE type='$type'";
}
if (!empty($gender)) {
  $total_rows_sql .= " WHERE gender='$gender'";
}
if (!empty($ml)) {
  $total_rows_sql .= " WHERE ml='$ml'";
}



if (!empty($search)) {
  $total_items_query = "SELECT COUNT(*) as total FROM products WHERE prodname LIKE ?";

  $stmt = $conn->prepare($total_items_query);
  $like_search_term = "%" . $search . "%";
  $stmt->bind_param("s", $like_search_term);
} else {
  $total_items_query = "SELECT COUNT(*) as total FROM products";
  if (!empty($type)) {
    $total_items_query .= " WHERE type = '$type'";
  }
  if (!empty($gender)) {
    $total_items_query .= " WHERE gender = '$gender'";
  }
  if (!empty($ml)) {
    $total_items_query .= " WHERE ml = '$ml'";
  }
  $stmt = $conn->prepare($total_items_query);
}


$stmt->execute();
$total_items_result = $stmt->get_result();
$total_items_row = $total_items_result->fetch_assoc();

$total_items = $total_items_row['total'];
$stmt->close();

$total_pages = ceil($total_items / $limit);

$total_rows_result = mysqli_query($conn, $total_rows_sql);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

$sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$pages = ceil($total_rows / $limit);
$offset = ($page - 1) * $limit;

// Sort by prod_no
$sortby_column  = isset($_GET['sortby']) ? $_GET['sortby'] : 'prodname';
$sql = "SELECT * FROM products";

if (!empty($search)) {
  $sql .= " WHERE prodname LIKE '%$search%'";
}
if (!empty($type)) {
  $sql .= " WHERE type='$type'";
}
if (!empty($gender)) {
  $sql .= " WHERE gender='$gender'";
}
if (!empty($ml)) {
  $sql .= " WHERE ml='$ml'";
}


$sql .= " ORDER BY $sortby_column $sorting
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

$start_index = $offset + 1;
$end_index = min($offset + $limit, $total_rows);


?>

<!-- ================ category section start ================= -->
<section class="section-margin--small mb-5">
  <div class="container">
    <div class="row">
      <div class="col-xl-3 col-lg-4 col-md-5">
        <div class="sidebar-categories">
          <div class="head">Type</div>
          <ul class="main-categories">
            <li class="common-filter">
              <?php
              $query = "SELECT `type` FROM products";
              $result1 = $conn->query($query);

              $wordCount = array();

              while ($row = $result1->fetch_assoc()) {
                $prod_name = strtolower($row['type']);
                // Split prod_name into words
                $words = preg_split('/\s+/', $prod_name, -1, PREG_SPLIT_NO_EMPTY);
                // Count occurrences of each word
                foreach ($words as $word) {
                  // Capitalize the first letter of each word
                  $word = ucwords($word);
                  if (!isset($wordCount[$word])) {
                    $wordCount[$word] = 1;
                  } else {
                    $wordCount[$word]++;
                  }
                }
              }

              arsort($wordCount);
              ?>
              <form action="#">
                <ul>
                  <?php foreach ($wordCount as $word => $count) : ?>
                    <li class="filter-list"><input class="pixel-radio" type="radio" id="<?= $word ?>" name="type" value="<?= $word ?>" onclick="this.form.submit()" <?php if (isset($_GET['type']) && $_GET['type'] == $word) echo 'checked'; ?>><label for="<?= $word ?>"><?= $word ?><span> (<?= $count ?>)</span></label></li>

                  <?php endforeach; ?>

                </ul>
              </form>
            </li>
          </ul>
        </div>
        <div class="sidebar-filter">
          <div class="top-filter-head">Product Filters</div>
          <div class="common-filter">
            <div class="head">Gender</div>
            <?php
            $query = "SELECT `gender` FROM products";
            $result1 = $conn->query($query);

            $wordCount = array();

            while ($row = $result1->fetch_assoc()) {
              $prod_name = strtolower($row['gender']);
              // Split prod_name into words
              $words = preg_split('/\s+/', $prod_name, -1, PREG_SPLIT_NO_EMPTY);
              // Count occurrences of each word
              foreach ($words as $word) {
                // Capitalize the first letter of each word
                $word = ucwords($word);
                if (!isset($wordCount[$word])) {
                  $wordCount[$word] = 1;
                } else {
                  $wordCount[$word]++;
                }
              }
            }

            arsort($wordCount);
            ?>
            <form action="#">
              <ul>
                <?php foreach ($wordCount as $word => $count) : ?>
                  <li class="filter-list"><input class="pixel-radio" type="radio" id="<?= $word ?>" name="gender" value="<?= $word ?>" onclick="this.form.submit()" <?php if (isset($_GET['gender']) && $_GET['gender'] == $word) echo 'checked'; ?>><label for="<?= $word ?>"><?= $word ?><span> (<?= $count ?>)</span></label></li>

                <?php endforeach; ?>

              </ul>
            </form>
          </div>
          <div class="common-filter">
            <div class="head">Milliliter </div>
            <?php
            $query = "SELECT `ml` FROM products";
            $result1 = $conn->query($query);

            $wordCount = array();

            while ($row = $result1->fetch_assoc()) {
              $prod_name = strtolower($row['ml']);
              // Split prod_name into words
              $words = preg_split('/\s+/', $prod_name, -1, PREG_SPLIT_NO_EMPTY);
              // Count occurrences of each word
              foreach ($words as $word) {
                // Capitalize the first letter of each word
                $word = ucwords($word);
                if (!isset($wordCount[$word])) {
                  $wordCount[$word] = 1;
                } else {
                  $wordCount[$word]++;
                }
              }
            }

            arsort($wordCount);
            ?>
            <form action="#" method="get">
              <ul>
                <?php foreach ($wordCount as $word => $count) : ?>
                  <li class="filter-list"><input class="pixel-radio" type="radio" id="<?= $word ?>" name="ml" value="<?= $word ?>" onclick="this.form.submit()" <?php if (isset($_GET['ml']) && $_GET['ml'] == $word) echo 'checked'; ?>><label for="<?= $word ?>"><?= $word ?>ml<span> (<?= $count ?>)</span></label></li>

                <?php endforeach; ?>
              </ul>
            </form>
          </div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8 col-md-7">
        <!-- Start Filter Bar -->
        <script>
          function updateSort(select) {
            const params = select.value.split("&");
            const url = new URL(window.location.href);
            params.forEach(param => {
              const [key, value] = param.split("=");
              url.searchParams.set(key, value);
            });
            window.location.href = url.toString();
          }
        </script>
        <div class="filter-bar d-flex flex-wrap align-items-center">
          <div class="sorting">
            <form action="" method="get">
              <select id="sortOptions" name="sortby" onchange="updateSort(this)">
                <option value="sortby=prodname&sort=asc" <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'prodname' && isset($_GET['sort']) && $_GET['sort'] == "asc") echo 'selected'; ?>>Name (Ascending)</option>
                <option value="sortby=prodname&sort=desc" <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'prodname' && isset($_GET['sort']) && $_GET['sort'] == "desc") echo 'selected'; ?>>Name (Descending)</option>
                <option value="sortby=price&sort=asc" <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == "asc") echo 'selected'; ?>>Price (Ascending)</option>
                <option value="sortby=price&sort=desc" <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == "desc") echo 'selected'; ?>>Price (Descending)</option>
              </select>
            </form>

          </div>
          <div class="sorting mr-auto">
            <form action="" method="get">
              <select name="limit" onchange="this.form.submit()">
                <option value="6" <?= isset($_GET['limit']) && $_GET['limit'] == '6' ? 'selected' : '' ?>>Show 6</option>
                <option value="12" <?= isset($_GET['limit']) && $_GET['limit'] == '12' ? 'selected' : '' ?>>Show 12</option>
                <option value="18" <?= isset($_GET['limit']) && $_GET['limit'] == '18' ? 'selected' : '' ?>>Show 18</option>
              </select>
            </form>

          </div>
          <div>
            <form action="" method="get">
              <div class="input-group filter-bar-search">
                <input type="text" placeholder="Search" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <div class="input-group-append">
                  <button type="submit"><i class="ti-search"></i></button>
                </div>
              </div>
            </form>

          </div>
        </div>



        <?php
        $products = [];
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
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
        <!-- End Filter Bar -->
        <!-- Start Best Seller -->
        <section class="lattest-product-area pb-40 category-list">
          <div class="row">
            <?php
            if (!empty($products)) {
              foreach ($products as $row) {
                $modal_id = 'perfume_' . $row['prod_no'];
                $prod_no = $row['prod_no'];
                $is_in_cart = false;

                if (isset($_SESSION['userid'])) {
                  $userid = $_SESSION['userid'];
                  $cart_check_sql = "SELECT * FROM carts WHERE prod_no = $prod_no AND userid = $userid";
                  $cart_check_result = mysqli_query($conn, $cart_check_sql);
                  $is_in_cart = mysqli_num_rows($cart_check_result) > 0;
                  if ($is_in_cart) {
                    $cart_row = mysqli_fetch_assoc($cart_check_result);
                    $cart_id = $cart_row['cart_id'];
                  }
                } ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card text-center card-product">
                    <div class="card-product__img">
                      <img class="card-img" src="prodimg/<?= $row['image'] ?>" alt="Product" height="250" loading="lazy">
                      <ul class="card-product__imgOverlay">
                        <li><button data-toggle="modal" data-target="#<?= $modal_id ?>"><i class="ti-search"></i></button></li>
                        <li><button onclick="window.location = 'addcart.php?prod_no=<?= $row['prod_no'] ?>'"><i class="ti-shopping-cart"></i></button></li>

                      </ul>
                    </div>
                    <div class="card-body">
                      <p><?= $row['type'] ?></p>
                      <h4 class="card-product__title"><a href="product-details.php?prod_no=<?= $row['prod_no'] ?>"><?= $row['prodname'] ?></a></h4>
                      <p class="card-product__price">₱<?= $row['price'] ?></p>
                    </div>
                  </div>
                </div>
            <?php  }
            }
            ?>


          </div>
        </section>
        <!-- End Best Seller -->
        <div class="d-flex justify-content-center">
          <nav class="mt-4">
            <ul class="pagination justify-content-center">
              <?php if ($page > 1) : ?>
                <?php
                $prev_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $page - 1]));
                ?>
                <li class="page-item">
                  <a class="page-link" href="<?= $prev_page_url ?>" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                  </a>
                </li>
              <?php endif; ?>

              <?php if ($total_pages <= 5) : ?>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                  <?php
                  $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                  ?>
                  <li class="page-item <?= $page == $i ? 'active' : ''; ?>">
                    <a class="page-link " href="<?= $page_url ?>"><?= $i; ?></a>
                  </li>
                <?php endfor; ?>
              <?php else : ?>
                <?php if ($page <= 3) : ?>
                  <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <?php
                    $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                    ?>
                    <li class="page-item <?= $page == $i ? 'active' : ''; ?>">
                      <a class="page-link " href="<?= $page_url ?>"><?= $i; ?></a>
                    </li>
                  <?php endfor; ?>
                  <li><span>...</span></li>
                  <?php
                  $last_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $total_pages]));
                  ?>
                  <li class="page-item">
                    <a class="page-link " href="<?= $last_page_url ?>"><?= $total_pages; ?></a>
                  </li>
                  <li><a href="<?= $last_page_url ?>"><?= $total_pages; ?></a></li>
                <?php elseif ($page >= $total_pages - 2) : ?>
                  <?php
                  $first_page_url = '?' . http_build_query(array_merge($_GET, ['page' => 1]));
                  ?>
                  <li class="page-item ">
                    <a class="page-link " href="<?= $first_page_url ?>">1</a>
                  </li>
                  <li><span>...</span></li>
                  <?php for ($i = $total_pages - 2; $i <= $total_pages; $i++) : ?>
                    <?php
                    $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                    ?>
                    <li class="page-item <?= $page == $i ? 'active' : ''; ?>">
                      <a class="page-link " href="<?= $page_url ?>"><?= $i; ?></a>
                    </li>
                  <?php endfor; ?>
                <?php else : ?>
                  <?php
                  $first_page_url = '?' . http_build_query(array_merge($_GET, ['page' => 1]));
                  ?>
                  <li><a href="<?= $first_page_url ?>">1</a></li>
                  <li class="page-item ">
                    <a class="page-link " href="<?= $first_page_url ?>">1</a>
                  </li>
                  <li><span>...</span></li>
                  <?php for ($i = $page - 1; $i <= $page + 1; $i++) : ?>
                    <?php
                    $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                    ?>
                    <li class="page-item <?= $page == $i ? 'active' : ''; ?>">
                      <a class="page-link " href="<?= $page_url ?>"><?= $i; ?></a>
                    </li>
                  <?php endfor; ?>
                  <li><span>...</span></li>
                  <?php
                  $last_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $total_pages]));
                  ?>
                  <li class="page-item ">
                    <a class="page-link " href="<?= $last_page_url ?>"><?= $total_pages; ?></a>
                  </li>
                <?php endif; ?>
              <?php endif; ?>

              <?php if ($page < $total_pages) : ?>
                <?php
                $next_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $page + 1]));
                ?>
                <li class="page-item ">
                  <a class="page-link" href="<?= $next_page_url ?>" aria-label="Next">
                    <span aria-hidden="true">»</span>
                  </a>
                </li>
              <?php endif; ?>
            </ul>

          </nav>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
if (!empty($products)) {
  foreach ($products as $row) {
    $modal_id = 'perfume_' . $row['prod_no'];
    $prod_no = $row['prod_no'];
    $is_in_cart = false;

    if (isset($_SESSION['userid'])) {
      $userid = $_SESSION['userid'];
      $cart_check_sql = "SELECT * FROM carts WHERE prod_no = $prod_no AND userid = $userid";
      $cart_check_result = mysqli_query($conn, $cart_check_sql);
      $is_in_cart = mysqli_num_rows($cart_check_result) > 0;
      if ($is_in_cart) {
        $cart_row = mysqli_fetch_assoc($cart_check_result);
        $cart_id = $cart_row['cart_id'];
      }
    } ?>

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
                    <span class="mr-3">₱<?= $row['price'] ?></span><span><i class="fa fa-check <?= $row['status'] !== 'Available' ? 'text-danger' : 'text-success' ?>"></i><?= $row['status'] ?></span> <br>

                  </div>
                  <div class="mt-2">
                    <p class="m-0">Gender: <?= $row['gender'] ?></p>
                    <p>Milliliter: <?= $row['ml'] ?></p>
                  </div>
                  <p><?= $row['description'] ?></p>
                    <div class="product-quantity mt-4">
                      <form action="addcart.php" method="get" class=" d-flex align-items-center">
                        <div class="product_count m-0">
                          <span class="mr-3">Quantity</span>
                          <input type="number" name="quantity"  id="sst<?= $row['prod_no'] ?>" maxlength="12" value="1" title="Quantity:" class="input-text qty" min="1">
                          <button class="increase items-count" type="button" onclick="increaseQuantity(<?= $row['prod_no'] ?>)">
                            <i class="lnr lnr-chevron-up"></i>
                          </button>
                          <button class="reduced items-count" type="button" onclick="decreaseQuantity(<?= $row['prod_no'] ?>)">
                            <i class="lnr lnr-chevron-down"></i>
                          </button>
                        </div>
                        <button class="btn btn-primary ml-2 border-0" type="submit" name="prod_no" value="<?= $row['prod_no'] ?>">Add to cart</button>
                      </form>
                    </div>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal end -->
<?php  }
}
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
<!-- ================ category section end ================= -->
<?php include('layouts/footer.php') ?>