<?php include('layouts/header.php') ?>
<!-- ================ start banner area ================= -->
<section class="blog-banner-area" id="category">
	<div class="container h-100">
		<div class="blog-banner">
			<div class="text-center">
				<h1>Register</h1>
				<nav aria-label="breadcrumb" class="banner-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Register</li>
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
						<h4>Already have an account?</h4>
						<p>Login to view the latest products to our e-commerce website</p>
						<a class="button button-account" href="login.php">Login Now</a>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="login_form_inner register_form_inner">
					<h3>Create an account</h3>
					<form class="row login_form" action="" id="register_form" method="post">
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" name="fname" placeholder="First Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'First Name'" required>
						</div>
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" name="lname" placeholder="Last Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Last Name'" required>
						</div>
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" id="username" name="username" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'" required>
						</div>
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" id="contact" name="contact" placeholder="Contact No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Contact No.'" required>
						</div>
						<div class="col-md-12 form-group">
							<input type="text" class="form-control" id="email" name="email" placeholder="Email Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address'" required>
						</div>
						<div class="col-md-12 form-group">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required>
						</div>

						<div class="col-md-12 form-group">
							<button type="submit" value="submit" class="button button-register w-100" name="register">Register</button>
						</div>
					</form>
					<?php
					if (isset($_POST['register'])) {
						$fname = $_POST['fname'];
						$lname = $_POST['lname'];
						$username = $_POST['username'];
						$email = $_POST['email'];
						$contact = $_POST['contact'];
						$password = $_POST['password'];

						// Check if the username already exists
						$username_check_sql = "SELECT * FROM `userinfo` WHERE `username` = '$username'";
						$username_result = $conn->query($username_check_sql);

						// Check if the email already exists
						$email_check_sql = "SELECT * FROM `userinfo` WHERE `email` = '$email'";
						$email_result = $conn->query($email_check_sql);

						if ($username_result->num_rows > 0) {
							// Username already exists, show alert
							$_SESSION['alert'] = "<script>
								Swal.fire({
									icon: 'error',
									title: 'Error',
									text: 'Username already exists'
								});
							</script>";
							echo '<meta http-equiv="refresh" content="0;url=register.php">';
							exit();
						} elseif ($email_result->num_rows > 0) {
							// Email already exists, show alert
							$_SESSION['alert'] = "<script>
								Swal.fire({
									icon: 'error',
									title: 'Error',
									text: 'Email already exists'
								});
							</script>";
							echo '<meta http-equiv="refresh" content="0;url=register.php">';
							exit();
						} else {
							// Username and email are unique, proceed with insertion
							$sql = "INSERT INTO `userinfo`(`fname`, `lname`, `username`, `email`, `password` , `contact_number`) VALUES ('$fname', '$lname', '$username', '$email', '$password', '$contact')";
							if ($conn->query($sql) === TRUE) {
								// Record inserted successfully
								$_SESSION['alert'] = "<script>
									Swal.fire({
										icon: 'success',
										title: 'Success',
										text: 'New record created successfully',
										allowOutsideClick: false
									}).then((result) => {
										if (result.isConfirmed) {
											window.location.href = 'login.php';
										}
									});
								</script>";
							} else {
								// Error inserting record
								$_SESSION['alert'] = "<script>
									Swal.fire({
										icon: 'error',
										title: 'Error',
										text: 'Error: " . $sql . "<br>" . $conn->error . "'
									});
								</script>";
								echo '<meta http-equiv="refresh" content="0;url=register.php">';
								exit();
							}
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