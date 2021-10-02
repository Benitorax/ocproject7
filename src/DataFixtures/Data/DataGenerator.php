<?php

namespace App\DataFixtures\Data;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Address;

class DataGenerator
{
    private Generator $faker;

    public const COMPANY = [
        'business' => ['mobile operator', 'internet service provider', 'hypermarket', 'supermarket', 'mobile shop'],
        'legalStatus' => [
            'Sole Trader', 'Partnership', 'Limited Company',
            'Limited Liability Partnership', 'Community Interest Company'
        ],
        'size' => ['micro', 'small', 'medium'],
    ];

    public const PHONE = [
        'screenSize' => [5.8, 6.1, 6.5, 6.7, 6.9],
        'weight' => [151, 164, 188, 189, 190, 192,  220],
        'storage' => [64, 128, 256, 512],
        'battery' => [2815, 3190, 4000, 4500, 5000],
    ];

    public const PHONE_MODELS = [
        'Samsung' => [
            'Galaxy S10', 'Galaxy S20', 'Galaxy S20 Ultra', 'Galaxy S21',
            'Galaxy S21 Ultra', 'Galaxy A72', 'Galaxy A52', 'Galaxy A32'
        ],
        'iPhone' => [
            'iPhone 11', 'iPhone 11 Pro', 'iPhone 11 pro Max', 'iPhone SE 2',
            'iPhone 12', 'iPhone 12 Pro', 'iPhone 12 Pro Max'
        ],
        'Pixel' => ['Pixel 4', 'Pixel 4A', 'Pixel 4A 5G', 'Pixel 5'],
    ];

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function companyBusiness(): string
    {
        return self::COMPANY['business'][array_rand(self::COMPANY['business'])];
    }

    public function companyLegalStatus(): string
    {
        return self::COMPANY['legalStatus'][array_rand(self::COMPANY['legalStatus'])];
    }

    public function companySize(): string
    {
        return self::COMPANY['size'][array_rand(self::COMPANY['size'])];
    }

    public function getNewAddress(): Address
    {
        return (new Address())
            ->setAddress($this->faker->streetAddress())
            ->setCity($this->faker->city())
            ->setZipCode($this->faker->postcode())
        ;
    }

    public function phoneScreenSize(): float
    {
        return self::PHONE['screenSize'][array_rand(self::PHONE['screenSize'])];
    }

    public function phoneWeight(): int
    {
        return self::PHONE['weight'][array_rand(self::PHONE['weight'])];
    }

    public function phoneStorage(): int
    {
        return self::PHONE['storage'][array_rand(self::PHONE['storage'])];
    }

    public function phoneBattery(): int
    {
        return self::PHONE['battery'][array_rand(self::PHONE['battery'])];
    }
}
