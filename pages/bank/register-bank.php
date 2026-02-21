<?php
namespace PineappleFinance\Pages\Bank;

require_once "../../_config.php";

require_once "../../modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();

session_start();
require_authenticated_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bank_code = $_POST['bank_code'];
    $bank_full_name = $_POST['bank_full_name'];

    $input = (object) [
        "bank_code" => $_POST['bank_code'],
        "full_name" => $_POST['bank_full_name'],
        "session_user_id" => $_SESSION['user_id']
    ];

    $sanitisedInput = $bankService->GetSanitisedInput($input, true);

    if ($sanitisedInput) {
        $response = $bankService->RegisterBank($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = implode(" ", $sanitisedInput["messages"]);
        // Handle invalid input case, e.g., display error message
        // For now, we will just return early to avoid processing invalid data
        return;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register Banks | Pine</title>
  <meta charset="utf-8" />
  <meta name="description" content="Pineapple Finance Site" />
  <meta name="author" content="Ong Zhi Xian" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="/css/normalize.css" />
  <link rel="stylesheet" href="/css/skeleton.css" />
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
  <link rel="stylesheet" href="/css/site.css" />
</head>
<body>

    <?php include_once '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

            <nav>
                <!-- 
                <a href="/pages/register-bank.php" class="button button-primary">Register bank</a>
                <a href="/pages/transactions.php" class="button-primary">Transactions</a>
                <a href="/pages/budgets.php" class="button-primary">Budgets</a>
                <a href="/pages/reports.php" class="button-primary">Reports</a> -->
            </nav>

            <form method="post">
                <div class="row">
                    <div class="four columns">
                        <label for="inputBankCode">Bank Code</label>
                        <input class="u-full-width" type="text" placeholder="Bank code" id="inputBankCode" name="bank_code">
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputBankFullName">Bank Full Name</label>
                        <input class="u-full-width" type="text" placeholder="Bank full name" id="inputBankFullName" name="bank_full_name">
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Register Bank">
                    <span class="feedback-message"><?php echo isset($feedbackMessage) ? $feedbackMessage : ''; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Banks</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
