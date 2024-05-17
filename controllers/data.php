<?php
require_once '../src/Authentication.php';
$loginSystem = new \App\Authentication();
$loginSystem->checkLoginStatus();

include '../html/header.html';

include __DIR__ . '/../src/FuelReceiptFilter.php';

include '../html/data.html';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fuelReceiptRequest = new \App\FuelReceiptFilter();
    $fuelReceiptRequest->getSearchInputs();
}
