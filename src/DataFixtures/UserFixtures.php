<?php

namespace App\DataFixtures;

use Faker\Generator;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Data\DataGenerator;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private DataGenerator $dataGenerator;
    private Generator $faker;
    private SluggerInterface $slugger;

    private const GENDER = [
        'Mr.' => 'male',
        'Ms.' => 'female'
    ];

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        DataGenerator $dataGenerator,
        SluggerInterface $slugger
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->dataGenerator = $dataGenerator;
        $this->faker = Factory::create();
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $user = $this->createUser();

            for ($i2 = 0; $i2 < 15; $i2++) {
                $customer = $this->addNewCustomerToUser($user);
                $manager->persist($customer);
            }

            $manager->persist($user);
            $manager->flush();
            $manager->clear();
        }
    }

    private function createUser(): User
    {
        $companyName = ucfirst($this->faker->company());

        $user = new User();
        $user->setName($companyName)
            ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
            ->setBusiness($this->dataGenerator->companyBusiness())
            ->setLegalStatus($this->dataGenerator->companyLegalStatus())
            ->setSize($this->dataGenerator->companySize())
            ->setEmail('contact@' . strtolower($this->slugger->slug($companyName)) . '.com')
            ->setPhoneNumber($this->faker->e164PhoneNumber())
            ->setAddress($this->dataGenerator->getNewAddress())
        ;

        return $user;
    }

    private function addNewCustomerToUser(User $user): Customer
    {
        $gender = self::GENDER[array_rand(self::GENDER)];
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName();

        return (new Customer())
            ->setGender(array_flip(self::GENDER)[$gender])
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail(strtolower($firstName . '.' . $lastName . '@example.com'))
            ->setPhoneNumber($this->faker->e164PhoneNumber())
            ->setAddress($this->dataGenerator->getNewAddress())
            ->setUser($user)
        ;
    }
}
