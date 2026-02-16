<?php
namespace PineappleFinance\Pages\Login;

if ( !isset($_GET['username']) ) {
    header("Location: /pages/login/index.php");
    exit();
}

require_once "../../_config.php";

require_once "../../modules/login_service.php";
use PineappleFinance\Services\LoginService;
$loginService = new LoginService();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "username" => $_GET['username'],
        "password" => $_POST['password'],
        "session_user_id" => $_SESSION['user_id']
    ];

    $sanitisedInput = $loginService->GetSanitisedInput($input, false);

    
    print_r("Sanitised Input: " . json_encode($sanitisedInput));

    if ($sanitisedInput) {
        $response = $loginService->UpdateLoginPassword($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

$login = $loginService->GetLogin($_GET['username']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Login | Pine</title>
    <meta charset="utf-8" />
    <meta name="description" content="Pineapple Finance Site" />
    <meta name="author" content="Ong Zhi Xian" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/css/normalize.css" />
    <link rel="stylesheet" href="/css/skeleton.css" />
    <link rel="stylesheet" href="/css/site.css" />
</head>
<body>

    <?php include '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

            <nav>
                <!-- 
                <a href="/pages/register-bank.php" class="button button-primary">Register bank</a>
                <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a> -->
            </nav>

            <h1>Update Login Password</h1>

            <form method="post">

                <div class="row">
                    <div class="four columns">
                        <label for="inputUsername">Username</label>
                        <input class="u-full-width" type="text" placeholder="Username" id="inputUsername" name="username" 
                            value="<?php echo isset($login['username']) ? $login['username'] : ''; ?>" readonly />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputPassword">Password</label>
                        <input class="u-full-width" type="password" placeholder="Password" id="inputPassword" name="password" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Update Password">
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Logins</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
