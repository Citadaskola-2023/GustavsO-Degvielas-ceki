<?php

namespace App;

require __DIR__ . '/../src/Database.php';

class FuelReceiptFilter
{
    public string $idInputMin;
    public string $idInputMax;
    public string $licensePlateInput;
    public string $dateTimeInputMin;
    public string $dateTimeInputMax;
    public string $odometerInputMin;
    public string $odometerInputMax;
    public string $petrolStationInput;
    public string $fuelTypeInput;
    public string $refueledInputMin;
    public string $refueledInputMax;
    public string $fuelPriceInputMin;
    public string $fuelPriceInputMax;
    public string $currencyInput;
    public string $totalInputMin;
    public string $totalInputMax;

    public function getSearchInputs(): void
    {
        $this->idInputMin = $_POST['idInputMin'];
        $this->idInputMax = $_POST['idInputMax'];
        $this->licensePlateInput = $_POST['licensePlateInput'];
        $this->dateTimeInputMin = $_POST['dateTimeInputMin'];
        $this->dateTimeInputMax = $_POST['dateTimeInputMax'];
        $this->odometerInputMin = $_POST['odometerInputMin'];
        $this->odometerInputMax = $_POST['odometerInputMax'];
        $this->petrolStationInput = $_POST['petrolStationInput'];
        $this->fuelTypeInput = $_POST['fuelTypeInput'];
        $this->refueledInputMin = $_POST['refueledInputMin'];
        $this->refueledInputMax = $_POST['refueledInputMax'];
        $this->fuelPriceInputMin = $_POST['fuelPriceInputMin'];
        $this->fuelPriceInputMax = $_POST['fuelPriceInputMax'];
        $this->currencyInput = $_POST['currencyInput'];
        $this->totalInputMin = $_POST['totalInputMin'];
        $this->totalInputMax = $_POST['totalInputMax'];

        $sqlQuery = 'SELECT * FROM Form WHERE 1=1';
        if (!empty($this->idInputMin)) {
            $sqlQuery .= ' AND id >= ' . $this->idInputMin;
        }
        if (!empty($this->idInputMax)) {
            $sqlQuery .= ' AND id <= ' . $this->idInputMax;
        }
        if (!empty($this->licensePlateInput)) {
            $sqlQuery .= ' AND license_plate = "' . $this->licensePlateInput . '"';
        }
        if (!empty($this->dateTimeInputMin)) {
            $sqlQuery .= ' AND date_time >= "' . $this->dateTimeInputMin . '"';
        }
        if (!empty($this->dateTimeInputMax)) {
            $sqlQuery .= ' AND date_time <= "' . $this->dateTimeInputMax . '"';
        }
        if (!empty($this->odometerInputMin)) {
            $sqlQuery .= ' AND odometer >= ' . $this->odometerInputMin;
        }
        if (!empty($this->odometerInputMax)) {
            $sqlQuery .= ' AND odometer <= ' . $this->odometerInputMax;
        }
        if (!empty($this->petrolStationInput)) {
            $sqlQuery .= ' AND petrol_station = "' . $this->petrolStationInput . '"';
        }
        if (!empty($this->fuelTypeInput)) {
            $sqlQuery .= ' AND fuel_type = "' . $this->fuelTypeInput . '"';
        }
        if (!empty($this->refueledInputMin)) {
            $sqlQuery .= ' AND refueled >= ' . $this->refueledInputMin;
        }
        if (!empty($this->refueledInputMax)) {
            $sqlQuery .= ' AND refueled <= ' . $this->refueledInputMax;
        }
        if (!empty($this->fuelPriceInputMin)) {
            $sqlQuery .= ' AND fuel_price >= ' . $this->fuelPriceInputMin;
        }
        if (!empty($this->fuelPriceInputMax)) {
            $sqlQuery .= ' AND fuel_price <= ' . $this->fuelPriceInputMax;
        }
        if (!empty($this->currencyInput)) {
            $sqlQuery .= ' AND currency = "' . $this->currencyInput . '"';
        }
        if (!empty($this->totalInputMin)) {
            $sqlQuery .= ' AND total >= ' . $this->totalInputMin;
        }
        if (!empty($this->totalInputMax)) {
            $sqlQuery .= ' AND total <= ' . $this->totalInputMax;
        }

        $this->displayData($sqlQuery);
    }

    private function displayData(string $query): void
    {
        $db = new Database();
        foreach (Database::BANNED_WORDS as $bannedWord) {
            $pattern = '/\b' . preg_quote($bannedWord, '/') . '\b/i';
            if (preg_match($pattern, $query)) {
                echo "<script>window.location.replace('/')</script>";
                exit;
            }
        }

        $conn = $db->connect();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $dom = new \DOMDocument();
        $dom->loadHTMLFile('../html/data.html');
        $dataTable = $dom->getElementById('dataTable');

        if ($dataTable->hasChildNodes()) {
            while ($dataTable->hasChildNodes()) {
                $dataTable->removeChild($dataTable->firstChild);
            }
        }
        if (!empty($results)) {
            $table = $dom->createElement('table');
            foreach ($results as $row) {
                $tableRow = $dom->createElement('tr');
                foreach ($row as $value) {
                    $tableData = $dom->createElement('td', htmlspecialchars($value));
                    $tableRow->appendChild($tableData);
                }
                $table->appendChild($tableRow);
            }
            $dataTable->appendChild($table);
        }

        file_put_contents('../html/data.html', $dom->saveHTML());
        echo "<script>window.location.replace('/data?')</script>";
        exit;
    }
}
