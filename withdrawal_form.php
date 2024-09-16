<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #202221;
            color: #fff;
        }
        .card {
            background-color: #2c2f34;
            border: 1px solid #22c55e;
        }
        .card-header {
            background-color: #22c55e;
            color: #fff;
        }
        .card-body {
            color: #fff;
        }
        .btn-primary {
            background-color: #22c55e;
            border-color: #22c55e;
        }
        .btn-primary:hover {
            background-color: #1f9c50;
            border-color: #1f9c50;
        }
        .alert-success {
            color: #22c55e;
            background-color: #fff;
            border-color: #22c55e;
        }
        .alert-error {
            color: #e3342f;
            background-color: #fff;
            border-color: #e3342f;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Withdrawal Form</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        session_start();
                        if (isset($_SESSION['success'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                            unset($_SESSION['success']);
                        }
                        if (isset($_SESSION['error'])) {
                            echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
                            unset($_SESSION['error']);
                        }
                        ?>
                        <form action="withdraw.php" method="POST">
                            <div class="form-group">
                                <label for="amount">Amount (Ksh)</label>
                                <input type="number" class="form-control" id="amount" name="withdraw_amount" min="1" required>
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile Number</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Withdraw</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
