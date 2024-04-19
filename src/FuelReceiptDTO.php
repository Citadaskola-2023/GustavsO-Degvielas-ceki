<?php

namespace App;

class FuelReceiptDTO
{
    public function __construct(
        public string $licensePlate,
        public string $dateTime,
        public string $odometer,
        public string $petrolStation,
        public string $fuelType,
        public string $refueled,
        public string $total,
        public string $currency,
        public string $fuelPrice,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'license_plate' => $this->licensePlate,
            'date_time' => $this->dateTime,
            'odometer' => $this->odometer,
            'petrol_station' => $this->petrolStation,
            'fuel_type' => $this->fuelType,
            'refueled' => $this->refueled,
            'total' => $this->total,
            'currency' => $this->currency,
            'fuel_price' => $this->fuelPrice
        ];
    }
}