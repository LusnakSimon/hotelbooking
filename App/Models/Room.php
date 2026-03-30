<?php

namespace App\Models;

use Framework\Core\Model;

class Room extends Model
{

    protected ?int $id = null;
    protected int $hotel_id;
    protected int $beds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotelId(): int
    {
        return $this->hotel_id;
    }

    public function setHotelId(int $hotel_id): void
    {
        $this->hotel_id = $hotel_id;
    }

    public function getBeds(): int
    {
        return $this->beds;
    }

    public function setBeds(int $beds): void
    {
        $this->beds = $beds;
    }

}