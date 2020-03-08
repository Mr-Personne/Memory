<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Person;
use App\Entity\Word;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 personnes
        for ($i = 0; $i < 200; $i++) {
            $person = new Person();
            // $word = new Word();
            // $word->setContent($faker->realText(10));
            $person->setFirstName($faker->firstName);
            $person->setLastName($faker->lastName);
            $person->setAddress($faker->streetAddress);
            $person->setTown($faker->city);
            $person->setPostalCode($faker->postcode);
            $person->setAge($faker->numberBetween(18, 100));
            $person->setJob($faker->jobTitle);
            $manager->persist($person);
            // $manager->persist($word);
        }

        $manager->flush();
    }
}
