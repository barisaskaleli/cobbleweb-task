<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 */
class UserFixtures extends AbstractDataFixtures
{
    private const DEFAULT_PASSWORD = '123456';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $user = (new User())
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setFullName(sprintf('%s %s', $firstName, $lastName))
                ->setEmail($faker->unique()->email)
                ->setAvatar($faker->url)
                ->setActive($faker->boolean)
                ->setCreatedAt($faker->dateTime)
                ->setUpdatedAt($faker->dateTime);

            $hash = $this->passwordEncoder->encodePassword($user, self::DEFAULT_PASSWORD);
            $user->setPassword($hash);

            $manager->persist($user);
            $this->addReference('user' . $i, $user);
        }

        $manager->flush();
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
        return self::USER_FIXTURES;
    }
}
