<?php

namespace App\Models;

use Framework\Core\Model;

class Booking extends Model
{

    protected ?int $id = null;
    protected int $room_id;
    protected int $user_id;
    protected string $from;
    protected string $until;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomId(): int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): void
    {
        $this->room_id = $room_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    public function getUntil(): string
    {
        return $this->until;
    }

    public function setUntil(string $until): void
    {
        $this->until = $until;
    }
}