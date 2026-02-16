session_start();
// Database connection
// Query to get hashed password
if (password_verify($_POST['password'], $hashed_password_from_db)) {
    $_SESSION['loggedin'] = true;
    header("Location: dashboard.php");
} else {
    echo "Invalid credentials";
}
