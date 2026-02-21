<?php
namespace PineappleFinance\Pages\BankAccount;

require_once "../../_config.php";

require_once "../../modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();

require_once "../../modules/fixed_deposit_service.php";
use PineappleFinance\Services\FixedDepositService;
$fixedDepositService = new FixedDepositService();

session_start();
$session_user_id = require_authenticated_user();

$bankIdNamePairList = $bankService->GetBankIdNamePairList($session_user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = (object) [
        "bank_code" => $_POST['bank_code'],
        "description" => $_POST['description'],
        "reference_code" => $_POST['reference_code'],
        "placement_amount" => $_POST['placement_amount'],

        "interest_rate_percentage" => $_POST['interest_rate_percentage'],
        "effective_date" => $_POST['effective_date'],
        "tenor_in_days" => $_POST['tenor_in_days'],
        "session_user_id" => $session_user_id
    ];

    $sanitisedInput = $fixedDepositService->GetSanitisedInput($input, true);

    if ($sanitisedInput) {
        $response = $fixedDepositService->RegisterFixedDeposit($sanitisedInput);
        $feedbackMessage = $response["message"];
    } else {
        $feedbackMessage = "Invalid input. Please check your entries.";
    }
}

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
                        <select class="u-full-width" id="selectBankCode" name="bank_code" value="">
                            <option value="">Select Bank</option>
                            <?php foreach ($bankIdNamePairList as $idNamePair) {?>
                            <option value="<?php echo $idNamePair['id']; ?>"><?php echo $idNamePair['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!--       ,fd.description
      ,fd.reference_code
      ,fd.placement_amount
      ,fd.interest_per_annum_percentage
      ,fd.effective_date
      ,fd.tenor_in_days 
-->

                <div class="row">
                    <div class="four columns">
                        <label for="inputDescription">Description</label>
                        <input class="u-full-width" type="text" placeholder="Descriptive text" id="inputDescription" name="description" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputReferenceCode">Reference Code</label>
                        <input class="u-full-width" type="text" placeholder="Reference code" id="inputReferenceCode" name="reference_code" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputPlacementAmount">Placement Amount</label>
                        <input class="u-full-width" type="text" placeholder="Placement amount" id="inputPlacementAmount" name="placement_amount" value="0" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputInterestRate">Interest Rate</label>
                        <input class="u-full-width" type="text" placeholder="Interest rate" id="inputInterestRate" name="interest_rate_percentage" value="0" />
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        <label for="inputEffectiveDate">Effective Date</label>
                        <input class="u-full-width" type="text" placeholder="Effective date" id="inputEffectiveDate" name="effective_date" />
                    </div>
                </div>


                <div class="row">
                    <div class="four columns">
                        <label for="inputTenor">Tenor (in days)</label>
                        <input class="u-full-width" type="text" placeholder="Tenor in days" id="inputTenor" name="tenor_in_days" />
                    </div>
                </div>

                <div class="row">
                    <input class="button-primary" type="submit" value="Register Bank Account">
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
