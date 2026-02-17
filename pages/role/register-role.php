<?php
namespace PineappleFinance\Pages\BankAccount;

require_once "../../_config.php";

require_once "../../modules/role_service.php";
use PineappleFinance\Services\RoleService;
$roleService = new RoleService();

session_start();
$session_user_id = require_authenticated_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "name" => $_POST['name'],
        "description" => $_POST['description'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $roleService->GetSanitisedInput($input, true);

    print_r($sanitisedInput);

    if ($sanitisedInput) {
        $response = $roleService->RegisterRole($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Role | Pine</title>
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

            <h1>Register Role</h1>

            <form method="post">

                <div class="row">
                    <div class="four columns">
                        <label for="inputName">Name</label>
                        <input class="u-full-width" type="text" placeholder="Name" id="inputName" name="name" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputDescription">Description</label>
                        <input class="u-full-width" type="text" placeholder="Description" id="inputDescription" name="description" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Register Login">
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Roles</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
