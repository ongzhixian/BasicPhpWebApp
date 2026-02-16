<?php
$is_authenticated = isset($_SESSION['username']);
?>
<header class="page">
    <h1>Pineapple Finance
        <?php if ($is_authenticated) { ?>
        <sup><?= $_SESSION['username'] ?></sup>
        <?php } ?>
    </h1>

    <?php if ($is_authenticated) { ?>
    <nav class="navbar">
        <a href="/index.php">Home</a>
        <a href="/pages/bank-account/index.php">Accounts</a>
        <a href="/pages/fixed-deposit/index.php">Fixed Deposits</a>
        <a href="/pages/Investment/index.php">Investments</a>
        <a href="/pages/bank/index.php">Banks</a>
        <a href="/pages/login/index.php">Logins</a>
        <a href="/logout.php">Logout</a>
        <!-- 
        <a href="/page-2.php">Page 2</a>
        <a href="/administer/index.php">Administer</a>
        <a href="php-info.php">PHP Info</a>
        -->
    </nav>
    <?php } ?>
</header>