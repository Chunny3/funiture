<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside>
    <ul class="navbar-nav bg-menu sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="d-flex justify-content-center my-5" href="index.php">
            <img src="../index/img/Oakly-logo.png" alt="Oakly" class="logo">
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - 會員列表要改路徑 -->
        <li class="nav-item <?= $current_page == 'index.html' ? 'active' : '' ?>" >
            <a class="nav-link" href="index.html">
                <i class="fa-solid fa-user"></i>
                <span>會員資料列表</span></a>
        </li>


        <!-- Nav Item - 商品管理路徑要改 -->
        <li class="nav-item <?= $current_page == 'productlist.php' ? 'active' : '' ?>">
            <a class="nav-link" href="../product/productlist.php">
                <i class="fa-brands fa-product-hunt"></i>
                <span>商品資料</span>
            </a>

        </li>




        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item <?= $current_page == 'couponsList.php' ? 'active' : '' ?>">
            <a class="nav-link" href="../coupons/couponsList.php">
                <i class="fa-solid fa-tags"></i>
                <span>優惠券管理</span>
            </a>
        </li>

        <!-- Nav Item - Charts -->
        <li class="nav-item <?= $current_page == 'index.php' ? 'active' : '' ?>">
            <a class="nav-link" href="../article/index.php">
                <i class="fa-solid fa-newspaper"></i>
                <span>文章管理</span></a>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">


    </ul>
</aside>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>