<?php
session_start();

// Redirect to index if not editing an existing expense
if (!isset($_GET['index']) || !isset($_SESSION['expenses'][$_GET['index']])) {
    header("Location: index.php");
    exit();
}

$index = $_GET['index'];

// Handle edit expense form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_expense'])) {
        $expenseTitle = $_POST['expense_title'];
        $expenseAmount = $_POST['expense_amount'];
        $oldAmount = $_SESSION['expenses'][$index]['amount'];

        $_SESSION['expenditure_value'] = $_SESSION['expenditure_value'] - $oldAmount + $expenseAmount;
        $_SESSION['balance_amount'] = $_SESSION['total_amount'] - $_SESSION['expenditure_value'];

        $_SESSION['expenses'][$index] = ['title' => $expenseTitle, 'amount' => $expenseAmount];

        header("Location: index.php");
        exit();
    }
}

$expense = $_SESSION['expenses'][$index];
?>


<head>
   
    <link rel="stylesheet" href="style.css">
    <title>Edit Expense</title>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <h3>Edit Expense</h3>
        <form method="post" action="">
            <input type="hidden" name="index" value="<?php echo htmlspecialchars($index); ?>">
            <input type="text" name="expense_title" value="<?php echo htmlspecialchars($expense['title']); ?>" placeholder="Enter Title Of Product" required>
            <input type="number" name="expense_amount" value="<?php echo htmlspecialchars($expense['amount']); ?>" placeholder="Enter Cost Of Product" required>
            <button class="submit" type="submit" name="edit_expense">Save Changes</button>
        </form>
    </div>
</div>

<style>
    .wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f9f9f9;
    }
    .container {
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }
    .submit {
        padding: 10px 20px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
    }
</style>

</body>
