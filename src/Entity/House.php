<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\SummerHouseController;
use App\Repository\HouseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HouseRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/summer-house',
            controller: SummerHouseController::class . '::getAll',
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new Get(
            uriTemplate: '/summer-house/{id}',
            controller: SummerHouseController::class . '::getById',
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new Post(
            uriTemplate: '/summer-house',
            controller: SummerHouseController::class . '::create',
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
    ]
)]
class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $area = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $bedrooms = null;

    #[ORM\Column]
    private ?int $distanceToSea = null;

    #[ORM\Column]
    private ?bool $hasShower = null;

    #[ORM\Column]
    private ?bool $hasBathroom = null;

    public static function create(
        float $area, 
        string $address, 
        int $price, 
        int $bedrooms, 
        int $distanceToSea, 
        bool $hasShower, 
        bool $hasBathroom
    ) {
        $house = new self(); 
        $house->area = $area; 
        $house->address = $address; 
        $house->price = $price; 
        $house->bedrooms = $bedrooms; 
        $house->distanceToSea = $distanceToSea; 
        $house->hasShower = $hasShower; 
        $house->hasBathroom = $hasBathroom; 

        return $house; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): static
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getDistanceToSea(): ?int
    {
        return $this->distanceToSea;
    }

    public function setDistanceToSea(int $distanceToSea): static
    {
        $this->distanceToSea = $distanceToSea;

        return $this;
    }

    public function hasShower(): ?bool
    {
        return $this->hasShower;
    }

    public function setHasShower(bool $hasShower): static
    {
        $this->hasShower = $hasShower;

        return $this;
    }

    public function hasBathroom(): ?bool
    {
        return $this->hasBathroom;
    }

    public function setHasBathroom(bool $hasBathroom): static
    {
        $this->hasBathroom = $hasBathroom;

        return $this;
    }
}
