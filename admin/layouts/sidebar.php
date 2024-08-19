<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Define an array with the menu items and their corresponding href attributes
$menu_items = array(
    "index.php" => "Dashboard",
    "orders.php" => "Orders",
    "users.php" => "Users",
    "products.php" => "Products",
    "transactions.php" => "Transactions",
    "profile.php" => "Profile",
);

// Function to add the 'active' class if the current page matches the menu item
function is_active($page, $current_page)
{
    if ($page === $current_page) {
        echo 'mm-active';
    }
}

?>
<nav class="sidebar">
    <div class="logo d-flex justify-content-between">
        <a href="index-2.html"><img src="img/logo.png" alt></a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    <ul id="sidebar_menu">
        <?php foreach ($menu_items as $href => $label) : ?>
            <li class="<?php is_active($href, $current_page); ?>">
                <a href="<?php echo $href; ?>">
                    <img src="img/menu-icon/<?php if ($label === "Dashboard") : ?>dashboard<?php elseif ($label === "Orders") : ?>4<?php elseif ($label === "Users") : ?>users<?php elseif ($label === "Products") : ?>2<?php elseif ($label === "Profile") : ?>myprofile<?php elseif ($label === "Transactions") : ?>7<?php endif; ?>.svg" alt>


                    <span><?php echo $label; ?></span>
                </a>

            </li>
        <?php endforeach; ?>
</nav>