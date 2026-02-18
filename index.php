<?php
namespace PineappleFinance\Pages\Dashboard;

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
$session_user_id = require_authenticated_user();

$totalBankBalance = $bankAccountService->GetTotalBankBalance($session_user_id)[0]['balance'];
$totalFixedDepositPlacementAmount = $fixedDepositService->GetTotalFixedDepositPlacementAmount($session_user_id)[0]['totalPlacementAmount'];

$totalEquityInvestmentAmount = $equityService->GetTotalEquityInvestmentAmount($session_user_id)[0]['totalEquityInvestmentAmount'];
$totalOtherInvestmentAmount = $investmentService->GetTotalInvestmentAmount($session_user_id)[0]['totalInvestmentAmount'];

$totalInvestmentAmount = $totalEquityInvestmentAmount + $totalOtherInvestmentAmount;

$grandTotal = $totalBankBalance + $totalFixedDepositPlacementAmount 
    + $totalEquityInvestmentAmount + $totalOtherInvestmentAmount;

if ($grandTotal !== 0) {
    $cashPercentage = ($totalBankBalance / $grandTotal) * 100;
    $fixedDepositPercentage = ($totalFixedDepositPlacementAmount / $grandTotal) * 100;
    $investmentPercentage = ($totalInvestmentAmount / $grandTotal) * 100;
} else {
    $cashPercentage = 0;
    $fixedDepositPercentage = 0;
    $investmentPercentage = 0;
}



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

        <main style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">

            <section class="current">
                <h1 style="text-align: center;">Financial Summary</h1>
                <div style="width: 300px; margin: auto;">
                    <canvas id="myChart"></canvas>
                </div>
            

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
                    <td><?= number_format($totalBankBalance ?? 0, 2) ?></td>
                    <td><?= number_format($cashPercentage, 2) ?>%</td>
                </tr>
                <tr>
                    <td>Fixed Deposit Placement Amount</td>
                    <td><?= number_format($totalFixedDepositPlacementAmount ?? 0, 2) ?></td>
                    <td><?= number_format($fixedDepositPercentage, 2) ?>%</td>
                </tr>
                <tr>
                    <td>Equity Investment Amount</td>
                    <td><?= number_format($totalEquityInvestmentAmount ?? 0, 2) ?></td>
                    <td><?= number_format($grandTotal !== 0 ? $totalEquityInvestmentAmount / $grandTotal * 100 : 0, 2) ?>%</td>
                </tr>
                <tr>
                    <td>Other Investment Amount</td>
                    <td><?= number_format($totalOtherInvestmentAmount ?? 0, 2) ?></td>
                    <td><?= number_format($grandTotal !== 0 ? $totalOtherInvestmentAmount / $grandTotal * 100 : 0, 2) ?>%</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>Grand Total</td>
                    <td><?= number_format($grandTotal, 2) ?></td>
                    <td></td>
                </tr>
            </table>
            </section>

            <section class="projection">
                <h1 style="text-align: center;">Financial Projection</h1>
                <p style="text-align: center;">Coming soon...</p>
            </section>
        </main>

        <aside>
            <h1>Dashboard</h1>
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
