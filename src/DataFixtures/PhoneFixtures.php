<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Data\DataGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PhoneFixtures extends Fixture
{
    private DataGenerator $dataGenerator;

    public function __construct(
        DataGenerator $dataGenerator
    ) {
        $this->dataGenerator = $dataGenerator;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (DataGenerator::PHONE_MODELS as $brand => $models) {
            foreach ($models as $model) {
                $phone = $this->createPhone($brand, $model);
                $manager->persist($phone);
            }
        }

        $manager->flush();
    }

    private function createPhone(string $brand, string $model): Phone
    {
        return (new Phone())
            ->setBrand($brand)
            ->setModel($model)
            ->setScreenSize($this->dataGenerator->phoneScreenSize())
            ->setWeight($this->dataGenerator->phoneWeight())
            ->setStorage($this->dataGenerator->phoneStorage())
            ->setBattery($this->dataGenerator->phoneBattery())
        ;
    }
}
