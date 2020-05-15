<!DOCTYPE html>
<html lang="en">
<head>
  <title>Center for Zakat Management (CZM)</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>


<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once(__DIR__ . "/../lib/SslCommerzNotification.php");
include(__DIR__ . "/../db_connection.php");
include(__DIR__ . "/../OrderTransaction.php");

use SslCommerz\SslCommerzNotification;

$sslc = new SslCommerzNotification();
$tran_id = $_POST['tran_id'];
$amount =  $_POST['amount'];
$currency =  $_POST['currency'];

$query = new OrderTransaction();
$sql = $query->getRecordQuery($tran_id);
$result = $conn_integration->query($sql);
$row = $result->fetch_array(MYSQLI_ASSOC);

if ($row['status'] == 'Pending') {

    $validation = $sslc->orderValidate($tran_id, $amount, $currency, $_POST);
    $tran_id = (string)$tran_id;

    if ($validation == TRUE) {
        $query = new OrderTransaction();
        $sql = $query->updateTransactionQuery($tran_id, 'Success');

        if ($conn_integration->query($sql) === TRUE) {
            //echo "Payment Record Updated Successfully";
        } else {
            echo "Error updating record: " . $conn_integration->error;
        }

        echo "<h2 style='color: green; text-align: center;'>Congratulations! Your Transaction is Successful.</h2>";
        ?>
        <div class="container">
          <h2>Center for Zakat Management (CZM)</h2>         
          <table class="table table-striped">
            <thead>
            <tr>
                <th colspan="2">Payment Status</th>
            </tr>
            </thead>
            <tr>
                <td>Transaction ID</td>
                <td><?php echo $_POST['tran_id'] ?></td>
            </tr>
            <tr>
                <td>Card Type</td>
                <td><?php echo $_POST['card_type'] ?></td>
            </tr>
            <tr>
                <td>Bank Transaction ID</td>
                <td><?php echo $_POST['bank_tran_id'] ?></td>
            </tr>
            <tr>
                <td>Card Type</td>
                <td><?php echo $_POST['card_type'] ?></td>
            </tr>
            <tr>
                <td>Amount</td>
                <td><?php echo $_POST['currency_amount'] ?></td>
            </tr>
        </table>
        </div>
        <?php
    } else {
        $query = new OrderTransaction();
        $sql = $query->updateTransactionQuery($tran_id, 'Failed');
        echo $sql;
        echo "<h2 style='color: #ff0000; text-align: center'>Payment was not valid. Please contact with the merchant.</h2>";
    }
    unset($_SESSION['payment_values']);
} else if ($row['status'] == 'Success') {
    echo "These order is already Successful";
} else {
    echo "Invalid Information";
}
?>