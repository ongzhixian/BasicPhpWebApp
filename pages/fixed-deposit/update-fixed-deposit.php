<?php
namespace PineappleFinance\Pages\FixedDeposit;

if ( !isset($_GET['id']) ) {
    header("Location: /pages/fixed-deposit/index.php");
    exit();
}
$fixed_deposit_id = htmlspecialchars($_GET['id']);

require_once "../../_config.php";

require_once "../../modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();

require_once "../../modules/fixed_deposit_service.php";
use PineappleFinance\Services\FixedDepositService;
$fixedDepositService = new FixedDepositService();

session_start();
$session_user_id = require_authenticated_user();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input = (object) [
        "id" => $_GET['id'],
        "bank_code" => $_POST['bank_code'],
        "description" => $_POST['description'],
        "reference_code" => $_POST['reference_code'],
        "placement_amount" => $_POST['placement_amount'],

        "interest_rate_percentage" => $_POST['interest_rate_percentage'],
        "effective_date" => $_POST['effective_date'],
        "tenor_in_days" => $_POST['tenor_in_days'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $fixedDepositService->GetSanitisedInput($input, false);

    if ($sanitisedInput) {
        $response = $fixedDepositService->UpdateFixedDeposit($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

$bankIdNamePairList = $bankService->GetBankIdNamePairList($session_user_id);

$fixed_deposit = $fixedDepositService->GetFixedDeposit($fixed_deposit_id)[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Fixed Deposit | Pine</title>
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

    <?php include_once '../../includes/header.php'; ?>

    <article class="with-aside">

        <main>

            <h1>Register Fixed Deposit</h1>

            <form method="post">
                <div class="row">
                    <div class="four columns">
                        <label for="inputBankCode">Bank Code</label>
                        <input class="u-full-width" type="text" placeholder="Bank code" id="inputBankCode" name="bank_code" readonly
                            value="<?php echo isset($fixed_deposit['name']) ? $fixed_deposit['name'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputDescription">Description</label>
                        <input class="u-full-width" type="text" placeholder="Descriptive text" id="inputDescription" name="description" 
                            value="<?php echo isset($fixed_deposit['description']) ? $fixed_deposit['description'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputReferenceCode">Reference Code</label>
                        <input class="u-full-width" type="text" placeholder="Reference code" id="inputReferenceCode" name="reference_code" 
                            value="<?php echo isset($fixed_deposit['reference_code']) ? $fixed_deposit['reference_code'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputPlacementAmount">Placement Amount</label>
                        <input class="u-full-width" type="text" placeholder="Placement amount" id="inputPlacementAmount" name="placement_amount" 
                            value="<?php echo isset($fixed_deposit['placement_amount']) ? $fixed_deposit['placement_amount'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputInterestRate">Interest Rate</label>
                        <input class="u-full-width" type="text" placeholder="Interest rate" id="inputInterestRate" name="interest_rate_percentage"
                            value="<?php echo isset($fixed_deposit['interest_per_annum_percentage']) ? $fixed_deposit['interest_per_annum_percentage'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputEffectiveDate">Effective Date</label>
                        <input class="u-full-width" type="text" placeholder="Effective date" id="inputEffectiveDate" name="effective_date" 
                            value="<?php echo isset($fixed_deposit['effective_date']) ? $fixed_deposit['effective_date']->format('Y-m-d') : ''; ?>" />
                    </div>
                </div>


                <div class="row">
                    <div class="four columns">
                        <label for="inputTenor">Tenor (in days)</label>
                        <input class="u-full-width" type="text" placeholder="Tenor in days" id="inputTenor" name="tenor_in_days" 
                            value="<?php echo isset($fixed_deposit['tenor_in_days']) ? $fixed_deposit['tenor_in_days'] : ''; ?>" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Update Fixed Deposit">
                    <span class="feedback-message"><?php echo $feedbackMessage ?? ""; ?></span>
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
