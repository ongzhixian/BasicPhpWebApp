<?php
namespace PineappleFinance\Pages\BankAccount;

if ( !isset($_GET['id']) ) {
    header("Location: /pages/investment/index.php");
    exit();
}
$investment_id = htmlspecialchars($_GET['id']);

require_once "../../_config.php"; // NOSONAR: Manual auto-loader
use PineappleFinance\Includes\DefaultPageHeader;

require_once "../../modules/investment_service.php";
use PineappleFinance\Services\InvestmentService;
$investmentService = new InvestmentService();

session_start();
$session_user_id = require_authenticated_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
    $input = (object) [
        "id" => $_GET['id'],
        "description" => $_POST['description'],
        "reference_code" => $_POST['reference_code'],
        "investment_amount" => $_POST['investment_amount'],
        "effective_date" => $_POST['effective_date'],
        "current_value" => $_POST['current_value'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $investmentService->GetSanitisedInput($input, false);

    if ($sanitisedInput) {
        $response = $investmentService->UpdateInvestment($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}


$investment = $investmentService->GetInvestment($investment_id)[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Investment | Pine</title>
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

    <?php DefaultPageHeader::render(); ?>

    <article class="with-aside">

        <main>

            <h1>Update Investment</h1>

            <form method="post">

<!-- 
[id]
,[description]
,[reference_code]
,[investment_amount]
,[effective_date]
,[current_value]
-->

                <div class="row">
                    <div class="four columns">
                        <label for="inputDescription">Description</label>
                        <input class="u-full-width" type="text" placeholder="Descriptive text" id="inputDescription" name="description" 
                            value="<?php echo isset($investment['description']) ? $investment['description'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputReferenceCode">Reference Code</label>
                        <input class="u-full-width" type="text" placeholder="Reference code" id="inputReferenceCode" name="reference_code" 
                            value="<?php echo isset($investment['reference_code']) ? $investment['reference_code'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputPlacementAmount">Investment Amount</label>
                        <input class="u-full-width" type="text" placeholder="Investment amount" id="inputPlacementAmount" name="investment_amount" 
                            value="<?php echo isset($investment['investment_amount']) ? $investment['investment_amount'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputEffectiveDate">Effective Date</label>
                        <input class="u-full-width" type="text" placeholder="Effective date" id="inputEffectiveDate" name="effective_date" 
                            value="<?php echo isset($investment['effective_date']) ? $investment['effective_date']->format('Y-m-d') : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputCurrentValue">Current Value</label>
                        <input class="u-full-width" type="text" placeholder="Current value" id="inputCurrentValue" name="current_value" 
                            value="<?php echo isset($investment['current_value']) ? $investment['current_value'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Update Investment" />
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
                </div>

            </form>

        </main>

        <aside>
            <h1>Investments</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

</body>
</html>
