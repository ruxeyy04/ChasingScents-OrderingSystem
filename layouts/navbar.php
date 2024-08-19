<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Define an array with the menu items and their corresponding href attributes
$menu_items = array(
    "index.php" => "Dashboard",
    "contact.php" => "Orders",
    "products.php" => "Users",
    "products.php" => "Products",
);

// Function to add the 'active' class if the current page matches the menu item
function is_active($page, $current_page)
{
    if ($page === $current_page) {
        echo 'active';
    }
}

?>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand logo_h" href="index.php"><img src="/images/logo.png" alt="" height="23"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
            <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
                <li class="nav-item <?php is_active("index.php", $current_page); ?>"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item <?php is_active("contact.php", $current_page); ?>"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item <?php is_active("products.php", $current_page); ?>"><a class="nav-link" href="products.php">Products</a></li>
                <?php
                if (isset($_SESSION['userid'])) { ?>


                    <li class="nav-item submenu dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile</a>
                        <ul class="dropdown-menu">
                            <li class="nav-item "><a class="nav-link" href="#!"><i class="fas fa-user"></i> <?=$userinfo['fname']?> <?=$userinfo['lname']?></a></li>
                            <li class="nav-item <?php is_active("myorder.php", $current_page); ?>"><a class="nav-link" href="myorder.php">Orders</a></li>
                            <li class="nav-item <?php is_active("profile.php", $current_page); ?>"><a class="nav-link" href="profile.php">Profile</a>
                            </li>
                        </ul>
                    </li>
                <?php
                }
                ?>
            </ul>


            <ul class="nav-shop">
                <li class="nav-item"><button data-target="#search" data-toggle="modal"><i class="ti-search"></i></button></li>
                <?php
                if (isset($_SESSION['userid'])) { ?>
                    <?php
                        $userId = $_SESSION['userid'];
                        // Assuming you have a connection to the database in $conn
                        $query = "SELECT carts.*, products.*, carts.quantity AS cart_quantity
                                FROM carts 
                                JOIN products ON carts.prod_no = products.prod_no 
                                WHERE carts.userid = ?";

                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                    ?>
                    <?php
                    
                    ?>
                    <li class="nav-item"><button onclick="window.location = 'cart.php'"><i class="ti-shopping-cart"></i><span class="nav-shop__circle"><?= $result->num_rows ?></span></button> </li>

                <?php
                }
                ?>
                <?php
                if (!isset($_SESSION['userid'])) { ?>

                    <li class="nav-item"><a class="button button-header" href="/login.php">Login</a></li>
                <?php
                } else { ?>
                    <li class="nav-item"><a class="button button-header" href="?logout">Logout</a></li>
                <?php  }
                ?>

            </ul>
        </div>
    </div>
</nav><!-- Modal -->
<div class="modal fade" id="search" tabindex="-1" aria-labelledby="searchLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchLabel">Search Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="products.php" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="seeee">Search</label>
                        <input type="text" class="form-control" id="seeee" name="search">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

        </div>
    </div>
</div>