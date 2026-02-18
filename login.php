<?php

require_once "_config.php";

require_once "modules/login_service.php";
use PineappleFinance\Services\LoginService;
$loginService = new LoginService();

require_once "modules/role_assignment_service.php";
use PineappleFinance\Services\RoleAssignmentService;
$roleAssignmentService = new RoleAssignmentService();

require_once "modules/bank_account_service.php";
use PineappleFinance\Services\BankAccountService;
$bankAccountService = new BankAccountService();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $feedbackMessage = "Username and password cannot be empty.";
    } else {
        
        $input = (object) [
            "username" => $_POST['username'],
            "password" => $_POST['password'],
            "session_user_id" => $_SESSION['user_id'] ?? null
        ];

        $response = $loginService->GetValidatedLogin($input);

        if ($response) {
            $session_user_id = $response['user_id'];
            $_SESSION['username'] = $response['username'];
            $_SESSION['user_id'] = $response['user_id'];

            $login_roles = $roleAssignmentService->GetLoginRoles($_SESSION['user_id']);
            $_SESSION['assigned_roles'] = array_column($login_roles, 'role_name');

            $historicalBankAccountList = $bankAccountService->GetBankAccountHistory($session_user_id);
            $historicalBankAccountRecordCount = count($historicalBankAccountList);
            if ($historicalBankAccountRecordCount <= 0) {
                $bankAccountService->ArchiveBankAccount($session_user_id);
            }
            header("Location: /index.php");
            exit();
        } else {
            $feedbackMessage = "Invalid input. Please check your entries.";
        }
    }
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login | Pine</title>
  <meta charset="utf-8" />
  <meta name="description" content="Pineapple Finance Site" />
  <meta name="author" content="Ong Zhi Xian" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/skeleton.css" />
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
  <link rel="stylesheet" href="css/site.css" />
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <article class="with-aside">

        <main>

        <form method="POST">
            <div class="row">
                <div class="four columns">
                    <label for="usernameInput">Username</label>
                    <input class="u-full-width" type="text" placeholder="Username" id="usernameInput" name="username" autocomplete="off" />
                </div>
            </div>
            <div class="row">
                <div class="four columns">
                    <label for="passwordInput">Password</label>
                    <input class="u-full-width" type="password" placeholder="Password" id="passwordInput" name="password"  autocomplete="off" />
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    <input class="button-primary" type="submit" value="Submit">
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>
            </div>
        </form>

        </main>

        <aside>
            <h1>Log In</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>

