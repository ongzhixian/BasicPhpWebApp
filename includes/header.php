<?php
namespace PineappleFinance\Includes;

class DefaultPageHeader {
    public static function render() {
        $is_authenticated = isset($_SESSION['username']);
        $assigned_roles = $_SESSION['assigned_roles'] ?? array();
        ?>
        <header class="page">
            <h1>Pineapple Finance
                <?php if ($is_authenticated) { ?> <sup><?= $_SESSION['username'] ?></sup>
                <?php } ?> 
            </h1>
            
            <?php if ($is_authenticated) { ?>
            <nav class="navbar">
                <?php if ( in_array("Application Administrator", $assigned_roles) ) { ?>
                <a href="/pages/login/index.php">Logins</a>
                <a href="/pages/role/index.php">Roles</a>
                <a href="/pages/role-assignment/index.php">Role Assignments</a>
                
                <?php } else { ?>
                <a href="/index.php">Home</a>
                <a href="/pages/bank-account/index.php">Accounts</a>
                <a href="/pages/fixed-deposit/index.php">Fixed Deposits</a>
                <a href="/pages/Investment/index.php">Investments</a>
                <a href="/pages/projection/index.php">Projection</a>
                <a href="/pages/bank/index.php">Banks</a>

                <?php } ?>
                <a href="/logout.php">Logout</a>
            </nav>
            <?php } ?>
            
        </header>
        <?php
    }
}
?>
