<?php

    require __DIR__ . '/../src/FuelReceiptDTO.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $receipt = new \App\FuelReceiptDTO(
            licensePlate: $_POST['license_plate'],
            dateTime: $_POST['date_time'],
            odometer: $_POST['odometer'],
            petrolStation: $_POST['petrol_station'],
            fuelType: $_POST['fuel_type'],
            refueled: $_POST['refueled'],
            total: $_POST['total'],
            currency: $_POST['currency'],
            fuelPrice: $_POST['fuel_price'],
        );

        try {
            $pdo = new PDO("mysql:host=db;dbname=fuel;charset=utf8mb4", 'root', 'root', [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $sql = <<<MySQL
            INSERT INTO fuel_receipts (license_plate, date_time, odometer, petrol_station, fuel_type, refueled, total, currency, fuel_price)
            VALUES (:licensePlate, :dateTime, :odometer, :petrolStation, :fuelType, :refueled, :total, :currency, :fuelPrice)
        MySQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($receipt->toArray());

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Receipt Form</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css" />
</head>
<body>
<h1>Fuel Receipt Form</h1>
<form action="process.php" method="post">
    License Plate: <input type="text" name="license_plate" placeholder="License Plate"><br>
    Date and Time: <input type="datetime-local" name="date_time"><br>
    Odometer: <input type="number" name="odometer" placeholder="Odometer"><br>
    Petrol Station: <input type="text" name="petrol_station" placeholder="Petrol Station"><br>
    Fuel Type: <input type="text" name="fuel_type" placeholder="Fuel Type"><br>
    Refueled (liters): <input type="number" step="0.01" name="refueled" placeholder="Refueled (liters)"><br>
    Total (currency): <input type="number" step="0.01" name="total" placeholder="Total (currency)"><br>
    Currency: <select type="text" name="currency" >
        <option value="Choose currency" disabled selected>Choose currency</option>
        <option value="eur">EUR</option>
        <option value="usd">USD</option>
        <option value="kwd">KWD</option>
        <option value="bhd">BHD</option>
        <option value="omr">OMR</option>
        <option value="jod">JOD</option>
        <option value="kyd">KYD</option>
        <option value="gbp">GBP</option>
        <option value="chf">CHF</option>
        <option value="bsd">BSD</option>
        <option value="pab">PAB</option>
        <option value="bmd">BMD</option>
        <option value="cad">CAD</option>
        <option value="sgd">SGD</option>
        <option value="bnd">BND</option>
        <option value="aud">AUD</option>
        <option value="nzd">NZD</option>
        <option value="bgn">BGN</option>
        <option value="fjd">FJD</option>
        <option value="brl">BRL</option><br></select>
        Fuel Price: <input type="number" step="0.01" name="fuel_price" placeholder="Enter Fuel Price"><br>
        <input type="submit" value="Submit">
</form>
</body>
</html>
