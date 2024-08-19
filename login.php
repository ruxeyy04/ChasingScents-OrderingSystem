<?php include('layouts/header.php') ?>
<!-- ================ start banner area ================= -->
<section class="blog-banner-area" id="category">
	<div class="container h-100">
		<div class="blog-banner">
			<div class="text-center">
				<h1>Login</h1>
				<nav aria-label="breadcrumb" class="banner-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Login</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</section>
<!-- ================ end banner area ================= -->

<!--================Login Box Area =================-->
<section class="login_box_area section-margin">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="login_box_img">
					<div class="hover">
						<h4>New to our website?</h4>
						<p>Create an account for free</p>
						<a class="button button-account" href="register.php">Create an Account</a>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="login_form_inner">
					<h3>Log in to enter</h3>
					<form class="row login_form" action="" id="login" method="post">
						<div class="col-md-12 form-group">
							<input type="text" class="form-control"  name="username" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'" required>
						</div>
						<div class="col-md-12 form-group">
							<input type="password" class="form-control" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required>
						</div>
						<div class="col-md-12 form-group">
							<button type="submit" value="submit" class="button button-login w-100" name="login">Log In</button>
						</div>
					</form>
					<?php

					if (isset($_POST['login'])) {
						$uname = $_POST['username'];
						$password = $_POST['password'];

						// Query to check username and password
						$sql = "SELECT userid, usertype FROM userinfo WHERE username = '$uname' AND password = '$password'";
						$result = $conn->query($sql);

						if ($result->num_rows > 0) {
							$row = $result->fetch_assoc();
							$usertype = $row['usertype'];
							$userid = $row['userid'];
							$_SESSION['userid'] = $userid;
							$_SESSION['usertype'] = $usertype;
							switch ($usertype) {
								case 'Admin':
									$_SESSION['alert'] = "<script>
										Swal.fire({
											icon: 'success',
											title: 'Login Successful',
											text: 'Welcome Admin',
											allowOutsideClick: false
										}).then((result) => {
											if (result.isConfirmed) {
												window.location.href = '/admin/';
											}
										});
									</script>";
									echo '<meta http-equiv="refresh" content="0;url=login.php">';
									exit();
									break;
								case 'Incharge':
									$_SESSION['alert'] = "<script>
										Swal.fire({
											icon: 'success',
											title: 'Login Successful',
											text: 'Welcome Incharge',
											allowOutsideClick: false
										}).then((result) => {
											if (result.isConfirmed) {
												window.location.href = '/incharge/';
											}
										});
									</script>";
									echo '<meta http-equiv="refresh" content="0;url=login.php">';
									exit();
									break;
								case 'Client':
									$_SESSION['alert'] = "<script>
										Swal.fire({
											icon: 'success',
											title: 'Login Successful',
											text: 'Welcome Customer',
											allowOutsideClick: false
										}).then((result) => {
											if (result.isConfirmed) {
												window.location.href = '/index.php';
											}
										});
									</script>";
									echo '<meta http-equiv="refresh" content="0;url=login.php">';
									exit();
									break;
								default:
									$_SESSION['alert'] = "<script>
																	Swal.fire({
																		icon: 'error',
																		title: 'error',
																		text: 'Unknown usertype',
																		allowOutsideClick: false
																	})
																</script>";
									echo '<meta http-equiv="refresh" content="0;url=login.php">';
									exit();
							}
						} else {
							$_SESSION['alert'] = "<script>
							Swal.fire({
								icon: 'info',
								title: 'Invalid',
								text: 'Invalid username or password',
							})
						</script>";
							echo '<meta http-equiv="refresh" content="0;url=login.php">';
							exit();
						}

						$conn->close();
					}
					?>

				</div>
			</div>
		</div>
	</div>
</section>
<!--================End Login Box Area =================-->

<?php include('layouts/footer.php') ?>