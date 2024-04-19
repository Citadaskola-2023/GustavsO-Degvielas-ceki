CREATE TABLE IF NOT EXISTS fuel_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    license_plate VARCHAR(20) NOT NULL,
    date_time DATETIME NOT NULL,
    odometer INT NOT NULL,
    petrol_station VARCHAR(100) NOT NULL,
    fuel_type VARCHAR(32) NOT NULL,
    refueled DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    currency CHAR(3) NOT NULL,
    fuel_price DECIMAL(10,6) NOT NULL
    );