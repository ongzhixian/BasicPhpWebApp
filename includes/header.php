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
        <a href="/pages/projection/index.php">Projection</a>
        <a href="/pages/bank/index.php">Banks</a>
        <a href="/pages/login/index.php">Logins</a>
        <a href="/pages/role/index.php">Roles</a>
        <a href="/pages/role-assignment/index.php">Role Assignments</a>
        <a href="/logout.php">Logout</a>
    </nav>
    <?php } ?>
</header>