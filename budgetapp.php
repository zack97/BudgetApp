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
    } elseif (isset($_POST['edit_index'])) {
        $editIndex = $_POST['edit_index'];
        $editingExpense = $_SESSION['expenses'][$editIndex];
        echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    var myModal = new bootstrap.Modal(document.getElementById("editModal"));
                    myModal.show();
                });
              </script>';
    } elseif (isset($_POST['reset_budget'])) {
        // Reset all session variables
        $_SESSION['total_amount'] = 0;
        $_SESSION['expenditure_value'] = 0;
        $_SESSION['balance_amount'] = 0;
        $_SESSION['expenses'] = [];
    } elseif (isset($_POST['export_csv'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="expenses.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Title', 'Amount']); // CSV headers
        foreach ($_SESSION['expenses'] as $expense) {
            fputcsv($output, [$expense['title'], $expense['amount']]);
        }
        fclose($output);
        exit();
    }
}
?>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Set Budget</div>
            <div class="card-body">
                <?php if (isset($budgetError)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($budgetError); ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <input type="number" class="form-control" name="total_amount" placeholder="Enter Total Amount" required>
                    </div>
                    <button class="btn btn-primary" type="submit" name="set_budget">Set Budget</button>
                </form>
                <form method="post" action="" class="mt-3">
                    <button class="btn btn-warning" type="submit" name="reset_budget">Reset Budget</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Add Expense</div>
            <div class="card-body">
                <?php if (isset($expenseError)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($expenseError); ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="expense_title" placeholder="Enter Title Of Product" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" class="form-control" name="expense_amount" placeholder="Enter Cost Of Product" required>
                    </div>
                    <button class="btn btn-primary" type="submit" name="add_expense">Add Expense</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Summary</div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">Total Budget:</div>
                    <div class="col-6 text-end"><?php echo htmlspecialchars($_SESSION['total_amount']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">Expenses:</div>
                    <div class="col-6 text-end"><?php echo htmlspecialchars($_SESSION['expenditure_value']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">Balance:</div>
                    <div class="col-6 text-end"><?php echo htmlspecialchars($_SESSION['balance_amount']); ?></div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Expenses List</div>
            <div class="card-body">
                <?php foreach ($_SESSION['expenses'] as $index => $expense): ?>
                    <div class="row mb-2">
                        <div class="col-4"><?php echo htmlspecialchars($expense['title']); ?></div>
                        <div class="col-4 text-end"><?php echo htmlspecialchars($expense['amount']); ?></div>
                        <div class="col-4 text-end">
                            <form method="post" action="" class="d-inline">
                                <input type="hidden" name="index" value="<?php echo htmlspecialchars($index); ?>">
                                <button class="btn btn-danger btn-sm" type="submit" name="delete_expense">Delete</button>
                            </form>
                            <form method="post" action="" class="d-inline">
                                <input type="hidden" name="edit_index" value="<?php echo htmlspecialchars($index); ?>">
                                <button class="btn btn-secondary btn-sm" type="submit">Edit</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <a href="generate_pdf.php" target="_blank" class="btn btn-success">Download PDF</a>
                <form method="post" action="" class="d-inline">
                    <button class="btn btn-info" type="submit" name="export_csv">Export CSV</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($editingExpense)): ?>
                    <form method="post" action="">
                        <input type="hidden" name="index" value="<?php echo htmlspecialchars($editIndex); ?>">
                        <div class="mb-3">
                            <label for="expense_title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="expense_title" value="<?php echo htmlspecialchars($editingExpense['title']); ?>" placeholder="Enter Title Of Product" required>
                        </div>
                        <div class="mb-3">
                            <label for="expense_amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" name="expense_amount" value="<?php echo htmlspecialchars($editingExpense['amount']); ?>" placeholder="Enter Cost Of Product" required>
                        </div>
                        <button class="btn btn-primary" type="submit" name="edit_expense">Save Changes</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
