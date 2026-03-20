<?php

namespace App\Models;

use App\Core\Model;

class Hotel extends Model
{

    protected ?int $id = null;
    protected string $name;
    protected int $manager_id;

    protected string $location;
    protected string $adress;
    protected string $image_url;
    protected string $description;
    protected float $price;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getManagerId(): int
    {
        return $this->manager_id;
    }

    public function setManagerId(int $manager_id): void
    {
        $this->manager_id = $manager_id;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getAdress(): string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): void
    {
        $this->adress = $adress;
    }

    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}