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
        }
    } elseif (isset($_POST['delete_expense'])) {
        $index = $_POST['index'];
        if (isset($_SESSION['expenses'][$index])) {
            $expenseAmount = $_SESSION['expenses'][$index]['amount'];

            $_SESSION['expenditure_value'] -= $expenseAmount;
            $_SESSION['balance_amount'] = $_SESSION['total_amount'] - $_SESSION['expenditure_value'];

            array_splice($_SESSION['expenses'], $index, 1);
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
            <?php foreach ($_SESSION['expenses'] as $index => $expense): ?>
                <div class="sublist-content flex-space">
                    <p class="product"><?php echo htmlspecialchars($expense['title']); ?></p>
                    <p class="amount"><?php echo htmlspecialchars($expense['amount']); ?></p>
                    <button class="edit" onclick="editExpense(<?php echo $index; ?>)"><i class="fa fa-edit"></i></button>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button class="delete" type="submit" name="delete_expense"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Edit Expense Form (Hidden by default) -->
<div id="editExpenseForm" class="edit-form">
    <h3>Edit Expense</h3>
    <form method="post" action="">
        <input type="hidden" name="index" id="editIndex">
        <input type="text" name="expense_title" id="editTitle" placeholder="Enter Title Of Product" required>
        <input type="number" name="expense_amount" id="editAmount" placeholder="Enter Cost Of Product" required>
        <button class="submit" type="submit" name="edit_expense">Save Changes</button>
    </form>
</div>

<div class="download-pdf">
    <a href="generate_pdf.php" target="_blank" class="download-button">Download PDF</a>
</div>

<script>
    function editExpense(index) {
        // Show the edit form
        document.getElementById('editExpenseForm').style.display = 'block';

        // Populate the form with the existing data
        let expenseTitle = document.querySelectorAll('.sublist-content .product')[index].innerText;
        let expenseAmount = document.querySelectorAll('.sublist-content .amount')[index].innerText;

        document.getElementById('editIndex').value = index;
        document.getElementById('editTitle').value = expenseTitle;
        document.getElementById('editAmount').value = expenseAmount;
    }
</script>

<style>
    .edit-form {
        display: none;
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

</body>

</html>
