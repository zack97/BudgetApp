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
    } elseif (isset($_POST['edit_expense'])) {
        $index = $_POST['index'];
        if (isset($_SESSION['expenses'][$index])) {
            $expenseTitle = $_POST['expense_title'];
            $expenseAmount = $_POST['expense_amount'];
            $oldAmount = $_SESSION['expenses'][$index]['amount'];

            $_SESSION['expenditure_value'] = $_SESSION['expenditure_value'] - $oldAmount + $expenseAmount;
            $_SESSION['balance_amount'] = $_SESSION['total_amount'] - $_SESSION['expenditure_value'];

            $_SESSION['expenses'][$index] = ['title' => $expenseTitle, 'amount' => $expenseAmount];
            $editIndex = null; // Close the edit form
        }
    } elseif (isset($_POST['delete_expense'])) {
        $index = $_POST['index'];
        if (isset($_SESSION['expenses'][$index])) {
            $expenseAmount = $_SESSION['expenses'][$index]['amount'];

            $_SESSION['expenditure_value'] -= $expenseAmount;
            $_SESSION['balance_amount'] = $_SESSION['total_amount'] - $_SESSION['expenditure_value'];

            array_splice($_SESSION['expenses'], $index, 1);
        }
    } elseif (isset($_POST['edit_index'])) {
        $editIndex = $_POST['edit_index'];
    }
}

// Determine which expense (if any) to edit
$editingExpense = isset($editIndex) ? $_SESSION['expenses'][$editIndex] : null;

// Output variables
$totalAmount = htmlspecialchars($_SESSION['total_amount']);
$expenditureValue = htmlspecialchars($_SESSION['expenditure_value']);
$balanceAmount = htmlspecialchars($_SESSION['balance_amount']);
$expenses = $_SESSION['expenses'];
$editIndex = isset($editIndex) ? htmlspecialchars($editIndex) : '';
$editingExpenseTitle = isset($editingExpense['title']) ? htmlspecialchars($editingExpense['title']) : '';
$editingExpenseAmount = isset($editingExpense['amount']) ? htmlspecialchars($editingExpense['amount']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Budget App | PHP Version</title>
    <style>
    .edit-form {
        display: block;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .download-pdf {
        text-align: center;
        margin-top: 20px;
    }
    .download-button {
        display: inline-block;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
</style>
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

        <div class="output-container">
            <div>
                <p>Total Budget</p>
                <span><?php echo $totalAmount; ?></span>
            </div>
            <div>
                <p>Expenses</p>
                <span><?php echo $expenditureValue; ?></span>
            </div>
            <div>
                <p>Balance</p>
                <span><?php echo $balanceAmount; ?></span>
            </div>
        </div>
    </div>

    <div class="list">
        <h3>Expenses List</h3>
        <div class="list-container">
            <?php foreach ($expenses as $index => $expense): ?>
                <div class="sublist-content">
                    <p class="product"><?php echo $expense['title']; ?></p>
                    <p class="amount"><?php echo $expense['amount']; ?></p>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button class="submit" type="submit" name="delete_expense">Delete</button>
                    </form>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="edit_index" value="<?php echo $index; ?>">
                        <button class="submit" type="submit">Edit</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if ($editingExpense): ?>
    <div class="edit-form">
        <h3>Edit Expense</h3>
        <form method="post" action="">
            <input type="hidden" name="index" value="<?php echo $editIndex; ?>">
            <input type="text" name="expense_title" value="<?php echo $editingExpenseTitle; ?>" placeholder="Enter Title Of Product" required>
            <input type="number" name="expense_amount" value="<?php echo $editingExpenseAmount; ?>" placeholder="Enter Cost Of Product" required>
            <button class="submit" type="submit" name="edit_expense">Save Changes</button>
        </form>
    </div>
<?php endif; ?>

<div class="download-pdf">
    <a href="generate_pdf.php" target="_blank" class="download-button">Download PDF</a>
</div>

</body>
</html>
