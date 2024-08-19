<?php include('layouts/header.php') ?>
<!-- ================ start banner area ================= -->
<section class="blog-banner-area" id="blog">
	<div class="container h-100">
		<div class="blog-banner">
			<div class="text-center">
				<h1>Product Details</h1>
				<nav aria-label="breadcrumb" class="banner-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Product Details</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</section>
<!-- ================ end banner area ================= -->

<?php

$prod_no = isset($_GET['prod_no']) ? (int)$_GET['prod_no'] : null;

if (!$prod_no) {
	echo '<meta http-equiv="refresh" content="0;url=products.php">';
	exit;
}

// Fetch product details
$product_query = "SELECT * FROM products WHERE prod_no = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $prod_no);
$stmt->execute();
$product_result = $stmt->get_result();
$data = $product_result->fetch_assoc();


if (!$data) {
	echo '<meta http-equiv="refresh" content="0;url=products.php">';
	exit;
}

$in_cart = false;
if ($userid) {
	$cart_check_query = "SELECT * FROM carts WHERE prod_no = ? AND userid = ?";
	$stmt = $conn->prepare($cart_check_query);
	$stmt->bind_param("ii", $prod_no, $userid);
	$stmt->execute();
	$cart_check_result = $stmt->get_result();
	$cart_row = $cart_check_result->fetch_assoc();
	$in_cart = $cart_check_result->num_rows > 0;
	$cart_id = $in_cart ? $cart_row['cart_id'] : null;
}

?>
<!--================Single Product Area =================-->
<div class="product_image_area">
	<div class="container">
		<div class="row s_product_inner">
			<div class="col-lg-6">
				<div class="">
					<div class="single-prd-item">
						<div style="background-image: url('prodimg/<?= $data['image'] ?>'); background-size: cover; background-position: center; height: 500px; width: 100% !important;"></div>
					</div>
				</div>
			</div>
			<div class="col-lg-5 offset-lg-1">
				<div class="s_product_text">
					<h3><?= $data['prodname'] ?></h3>
					<h2>₱<?= $data['price'] ?></h2>
					<ul class="list">
						<li><a href="#!"><i class="fa fa-check <?= $data['status'] !== 'Available' ? 'text-danger' : 'text-success' ?>"></i><?= $data['status'] ?></a></li>
						<li><a href="#!"><span>Type</span> : <?= $data['type'] ?></a></li>
						<li><a href="#!"><span>Gender</span> : <?= $data['gender'] ?></a></li>
					</ul>
					<p><?= $data['description'] ?></p>
					<?php
					if ($in_cart) { ?>
						<form action="removecart.php" method="get">

							<button class="button primary-btn" name="cart_id" value="<?= $cart_id ?>">Remove to Cart</button>
						</form>
					<?php } else { ?>
						<form action="addcart.php" method="get">
							<div class="product_count mr-2">
								<span class="mr-3">Quantity</span>
								<input type="number" name="quantity" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty" min="1">
								<button class="increase items-count" type="button" onclick="increaseQuantity()">
									<i class="lnr lnr-chevron-up"></i>
								</button>
								<button class="reduced items-count" type="button" onclick="decreaseQuantity()">
									<i class="lnr lnr-chevron-down"></i>
								</button>
							</div>
							<button class="button primary-btn" name="prod_no" value="<?= $data['prod_no'] ?>">Add to Cart</button>
						</form>
					<?php	}
					?>



				</div>
			</div>
		</div>
	</div>
</div>
<!--================End Single Product Area =================-->

<!--================Product Description Area =================-->
<section class="product_description_area">
	<div class="container">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Description</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Specification</a>
			</li>

		</ul>
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				<p><?= $data['description'] ?></p>
			</div>
			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<div class="table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<td>
									<h5>Gender</h5>
								</td>
								<td>
									<h5><?= $data['gender'] ?></h5>
								</td>
							</tr>
							<tr>
								<td>
									<h5>Type</h5>
								</td>
								<td>
									<h5><?= $data['type'] ?></h5>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<!--================End Product Description Area =================-->

<!--================ Start related Product area =================-->
<section class="related-product-area section-margin--small mt-0">
	<div class="container">
		<div class="section-intro pb-60px">
			<h2>Related <span class="section-intro__style">Product</span></h2>
		</div>
		<?php
		if (isset($_GET['prod_no'])) {
			$prodno = $_GET['prod_no'];
			$sql = "SELECT * FROM products WHERE prod_no != $prodno ORDER BY RAND() LIMIT 12";
		} else {
			$sql = "SELECT * FROM products ORDER BY RAND() LIMIT 12";
		}
		$result = $conn->query($sql);
		// Check if there are results
		if ($result->num_rows > 0) {
			// Open the row container
		?>
			<div class="row mt-30 d-flex justify-content-center">
				<?php

				$count = 0; 
				while ($row = $result->fetch_assoc()) {
					if ($count % 3 === 0) {
				?>
						<div class="col-sm-6 col-xl-3 mb-4 mb-xl-0">
							<div class="single-search-product-wrapper">
							<?php
						}

							?>
							<div class="single-search-product d-flex">
								<a href="product-details.php?prod_no=<?=$row['prod_no']?>"><img src="prodimg/<?php echo $row['image']; ?>" alt=""></a>
								<div class="desc">
									<a href="product-details.php?prod_no=<?=$row['prod_no']?>" class="title"><?php echo $row['prodname']; ?></a>
									<div class="price">₱<?php echo $row['price']; ?></div>
								</div>
							</div>
							<?php

							if ($count % 3 === 2) {
							?>
							</div>
						</div>
				<?php
							}

							$count++;
						}

				?>
			</div>
		<?php

			$result->free();
		} else {
			echo "0 results";
		}

		$conn->close();
		?>
	</div>
</section>
<!--================ end related Product area =================-->
<script>
	function increaseQuantity() {
		var result = document.getElementById('sst');
		var sst = parseInt(result.value);
		if (!isNaN(sst)) {
			result.value = sst + 1;
		}
		return false;
	}

	function decreaseQuantity() {
		var result = document.getElementById('sst');
		var sst = parseInt(result.value);
		if (!isNaN(sst) && sst > 1) {
			result.value = sst - 1;
		}
		return false;
	}
</script>
<?php include('layouts/footer.php') ?>