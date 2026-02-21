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


spl_autoload_register(function ($class) {

    $prefix = 'PineappleFinance\\';
    $base_dir = __DIR__ . '\\'; // adjust to where your classes live

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // not our namespace
    }

    // Get the relative class name
    $relative_class = substr($class, $len);
    print_r("Autoloading class: $class, relative class: $relative_class<br>");

    $includePrefix = 'Includes\\';
    if (strncmp($includePrefix, $class, $len) === 0) {
        $file = $base_dir . 'includes\\header.php';
        if (file_exists($file)) {
            require $file;
        }
    }

    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    print_r("File to include: ". $base_dir . str_replace('\\', '/', $relative_class) . '.php');

    if (file_exists($file)) {
        require $file;
    }
});

?>
