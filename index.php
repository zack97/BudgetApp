<?php
session_start();

// Initialize session variables if they are not already set
if (!isset($_SESSION['total_amount'])) {
    $_SESSION['total_amount'] = 0;
    $_SESSION['expenditure_value'] = 0;
    $_SESSION['balance_amount'] = 0;
    $_SESSION['expenses'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['set_budget'])) {
        $totalAmount = $_POST['total_amount'];
        if ($totalAmount >= 0) {
            $_SESSION['total_amount'] = $totalAmount;
            $_SESSION['expenditure_value'] = 0;
            $_SESSION['balance_amount'] = $totalAmount;
            $_SESSION['expenses'] = [];
        } else {
            $budgetError = 'Value cannot be empty or negative';
        }
    } elseif (isset($_POST['add_expense'])) {
        $expenseTitle = $_POST['expense_title'];
        $expenseAmount = $_POST['expense_amount'];
        if ($expenseTitle != '' && $expenseAmount >= 0) {
            $_SESSION['expenditure_value'] += $expenseAmount;
            $_SESSION['balance_amount'] = $_SESSION['total_amount'] - $_SESSION['expenditure_value'];
            $_SESSION['expenses'][] = ['title' => $expenseTitle, 'amount' => $expenseAmount];
        } else {
            $expenseError = 'Values cannot be empty';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Budget App | PHP Version</title>
</head>

<body>

<div class="wrapper">
    <div class="container">
        <div class="sub-container">
            <div class="total-amount-container">
                <h3>Budget</h3>
                <?php if (isset($budgetError)): ?>
                    <p class="error"><?php echo $budgetError; ?></p>
                <?php endif; ?>
                <form method="post" action="">
                    <input type="number" name="total_amount" placeholder="Enter Total Amount" required>
                    <button class="submit" type="submit" name="set_budget">Set Budget</button>
                </form>
            </div>

            <div class="user-amount-container">
                <h3>Expenses</h3>
                <?php if (isset($expenseError)): ?>
                    <p class="error"><?php echo $expenseError; ?></p>
                <?php endif; ?>
                <form method="post" action="">
                    <input type="text" name="expense_title" placeholder="Enter Title Of Product" required>
                    <input type="number" name="expense_amount" placeholder="Enter Cost Of Product" required>
                    <button class="submit" type="submit" name="add_expense">Check Amount</button>
                </form>
            </div>
        </div>

        <div class="output-container flex-space">
            <div>
                <p>Total Budget</p>
                <span id="amount">
                    <?php echo $_SESSION['total_amount']; ?>
                </span>
            </div>
            <div>
                <p>Expenses</p>
                <span id="expenditure-value">
                    <?php echo $_SESSION['expenditure_value']; ?>
                </span>
            </div>
            <div>
                <p>Balance</p>
                <span id="balance-amount">
                    <?php echo $_SESSION['balance_amount']; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="list">
        <h3>Expenses List</h3>
        <div class="list-container" id="list">
            <?php foreach ($_SESSION['expenses'] as $expense): ?>
                <div class="sublist-content flex-space">
                    <p class="product"><?php echo $expense['title']; ?></p>
                    <p class="amount"><?php echo $expense['amount']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>

</html>
