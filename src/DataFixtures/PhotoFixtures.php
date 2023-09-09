<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PhotoFixtures extends AbstractDataFixtures
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $photo = (new Photo())
                ->setName($faker->word)
                ->setUrl($faker->url)
                ->setUser($this->getReference('user' . random_int(0, 4)))
                ->setCreatedAt($faker->dateTime)
                ->setUpdatedAt($faker->dateTime);

            $manager->persist($photo);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['base_fixture'];
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return self::PHOTO_FIXTURES;
    }
}
