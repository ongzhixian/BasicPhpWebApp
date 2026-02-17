<?php
define('DBSERVER', 'localhost');    // Database server
define('DBUSERNAME', 'root');       // Database username
define('DBPASSWORD', 'Pass1234');   // Database password
define('DBNAME', 'school');         // Database name

define('SQLSERVER', '(local)');             // Database server
define('PINEAPPLE_DB', 'PineappleFinance'); // Database server

define('DEBUG', true); // debug query

function require_authenticated_user() {
    if (!isset($_SESSION['username'])) {
        header("Location: /login.php");
        exit();
    }
    return $_SESSION['user_id'];
}

?>