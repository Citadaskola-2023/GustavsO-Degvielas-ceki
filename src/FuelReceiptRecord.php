<?php

namespace App;

use DateTime;
use DateTimeZone;

require __DIR__ . '/../src/Database.php';

class FuelReceiptRecord
{
    private const CURRENCIES = ['EUR', 'USD', 'KWD', 'BHD', 'OMR', 'JOD', 'KYD', 'GBP', 'CHF', 'BSD',
        'PAB', 'BMD', 'CAD', 'SGD', 'BND', 'AUD', 'NZD', 'BGN', 'FJD', 'BRL'];

    public function processFormInput(): array
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $inputData = [
                'license_plate' => $_POST['license_plate'],
                'date_time' => $_POST['date_time'] ?? '',
                'odometer' => $_POST['odometer'] ?? '',
                'petrol_station' => $_POST['petrol_station'] ?? '',
                'fuel_type' => $_POST['fuel_type'] ?? '',
                'refueled' => $_POST['refueled'] ?? '',
                'fuel_price' => $_POST['fuel_price'] ?? '',
                'currency' => $_POST['currency'] ?? '',
                'total' => ''
            ];

            // Convert to UTC time zone
            $dateTime = new DateTime($inputData['date_time'], new DateTimeZone(date_default_timezone_get()));
            $dateTime->setTimezone(new DateTimeZone('UTC'));
            $inputData['date_time'] = $dateTime->format('Y-m-d\TH:i');


            // Validate input
            if (!$this->validateInput($inputData)) {
                die("Input validation failed");
            }

            $inputData['total'] = $inputData['fuel_price'] * $inputData['refueled'];
            return $inputData;
        }

        die("<h3>Could not retrieve form input data</h3>");
    }

    private function validateInput(array $data): bool
    {
        if (!$this->checkBannedWords($data)) {
            die("Input contains banned words");
        }

        if (!is_string($data['license_plate']) || empty($data['license_plate'])) {
            die("Invalid input: License plate");
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $data['date_time'])) {
            die("Invalid input: Date time");
        }

        if (!preg_match('/^\d+$/', $data['odometer'])) {
            die("Invalid input: Odometer");
        }

        if (!is_string($data['petrol_station']) || empty($data['petrol_station'])) {
            die("Invalid input: Petrol station");
        }

        if (!is_string($data['fuel_type']) || empty($data['fuel_type'])) {
            die("Invalid input: Fuel type");
        }

        if (!preg_match('/^\d+(\.\d+)?$/', $data['refueled'])) {
            die("Invalid input: Refueled");
        }

        if (!preg_match('/^\d+(\.\d+)?$/', $data['fuel_price'])) {
            die("Invalid input: Fuel price");
        }

        if (!in_array($data['currency'], self::CURRENCIES)) {
            die("Invalid input: Currency");
        }

        return true;
    }

    private function checkBannedWords(array $data): bool
    {
        $db = new Database();
        foreach ($data as $value) {
            foreach ($db::BANNED_WORDS as $bannedWord) {
                if (stristr($value, $bannedWord) !== false) {
                    return false;
                }
            }
        }
        return true;
    }

    public function saveFuelReceipt(array $data): void
    {
        $db = new Database();
        $connection = $db->connect();
        $query = "INSERT INTO Form(license_plate, date_time, odometer, petrol_station, fuel_type, refueled, fuel_price, currency, total)
                  VALUES(:license_plate, :date_time, :odometer, :petrol_station, :fuel_type, :refueled, :fuel_price, :currency, :total)";
        $statement = $connection->prepare($query);
        $statement->execute($data);
    }
}
