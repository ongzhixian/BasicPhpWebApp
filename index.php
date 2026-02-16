<?php
//namespace PineappleFinance\Pages\BankAccount;

require_once "_config.php";

require_once "modules/bank_account_service.php";
use PineappleFinance\Services\BankAccountService;
$bankAccountService = new BankAccountService();

require_once "modules/fixed_deposit_service.php";
use PineappleFinance\Services\FixedDepositService;
$fixedDepositService = new FixedDepositService();

require_once "modules/investment_service.php";
use PineappleFinance\Services\InvestmentService;
$investmentService = new InvestmentService();

require_once "modules/equity_service.php";
use PineappleFinance\Services\EquityService;
$equityService = new EquityService();

session_start();
require_authenticated_user();

$totalBankBalance = $bankAccountService->GetTotalBankBalance()[0]['balance'];
$totalFixedDepositPlacementAmount = $fixedDepositService->GetTotalFixedDepositPlacementAmount()[0]['totalPlacementAmount']
    -200000;

$totalEquityInvestmentAmount = $equityService->GetTotalEquityInvestmentAmount()[0]['totalEquityInvestmentAmount'];
$totalOtherInvestmentAmount = $investmentService->GetTotalInvestmentAmount()[0]['totalInvestmentAmount'];

$totalInvestmentAmount = $totalEquityInvestmentAmount + $totalOtherInvestmentAmount;

$grandTotal = $totalBankBalance + $totalFixedDepositPlacementAmount 
    + $totalEquityInvestmentAmount + $totalOtherInvestmentAmount;


$cashPercentage = ($totalBankBalance / $grandTotal) * 100;
$fixedDepositPercentage = ($totalFixedDepositPlacementAmount / $grandTotal) * 100;
$investmentPercentage = ($totalInvestmentAmount / $grandTotal) * 100;
// $feedbackMessage = "";
// $login = new LoginModule();

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     $loginIsAdded = $login->AddLogin($username, $password);
//     if ($loginIsAdded) {
//         $feedbackMessage = "Login added successfully.";
//     } else {
//         $feedbackMessage = "Failed to add login.";
//     }
// }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pine</title>
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

            <section>
                <h1>Financial Summary</h1>
                <div style="width: 300px; margin: auto;">
                    <canvas id="myChart"></canvas>
                </div>
            </section>

            <table class="u-full-width financial-summary">
            <thead>
                <tr>
                    <th>Total</th>
                    <th>Value</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bank Balance</td>
                    <td><?= number_format($totalBankBalance, 2) ?></td>
                    <td><?= number_format($cashPercentage, 2) ?>%</td>
                </tr>
                <tr>
                    <td>Fixed Deposit Placement Amount (ex)</td>
                    <td><?= number_format($totalFixedDepositPlacementAmount, 2) ?></td>
                    <td><?= number_format($fixedDepositPercentage, 2) ?>%</td>
                </tr>
                <tr>
                    <td>Equity Investment Amount</td>
                    <td><?= number_format($totalEquityInvestmentAmount, 2) ?></td>
                    <td><?= number_format($totalEquityInvestmentAmount / $grandTotal * 100, 2) ?>%</td>
                </tr>
                <tr>
                    <td>Other Investment Amount</td>
                    <td><?= number_format($totalOtherInvestmentAmount, 2) ?></td>
                    <td><?= number_format($totalOtherInvestmentAmount / $grandTotal * 100, 2) ?>%</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>Grand Total</td>
                    <td><?= number_format($grandTotal, 2) ?></td>
                    <td></td>
                </tr>
            </table>

        </main>

        <aside>
            <h1>Welcome</h1>
            <img src="/images/kim-yoo-jung.jpg" alt="Kim Yoo Jung" style="width: 100%; height: 100%; object-fit: cover;" />
        </aside>

    </article>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'pie',
    data:  {
        labels: [
            'Cash',
            'Fixed Deposit',
            'Investments'
        ],
        datasets: [{
            label: 'Financial Summary',
            data: [<?= $cashPercentage ?>, <?= $fixedDepositPercentage ?>, <?= $investmentPercentage ?>],
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    }
  });
</script>
</body>
</html>
