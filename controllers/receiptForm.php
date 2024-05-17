<?php
require_once '../src/Authentication.php';
$loginSystem = new \App\Authentication();
$loginSystem->checkLoginStatus();

require __DIR__ . '/../src/FuelReceiptRecord.php';

require '../html/header.html';

require '../html/form.html';

$insert = new \App\FuelReceiptRecord();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $insert->saveFuelReceipt($insert->processFormInput());
}
