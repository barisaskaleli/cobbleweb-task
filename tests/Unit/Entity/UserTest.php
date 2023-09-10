<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Photo;
use App\Entity\User;
use CRM\Entity\Reminder;
use DateTime;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testUserEntity()
    {
        $user = new User();
        $photo = new Photo();

        $user->setFirstName('John');
        static::assertEquals('John', $user->getFirstName());

        $user->setLastName('Doe');
        static::assertEquals('Doe', $user->getLastName());

        $user->setFullName();
        static::assertEquals('John Doe', $user->getFullName());

        $user->setEmail('john.doe@example.com');
        static::assertEquals('john.doe@example.com', $user->getEmail());

        $user->setPassword('secure123');
        static::assertEquals('secure123', $user->getPassword());

        $user->setActive(true);
        static::assertTrue($user->getActive());

        $user->setAvatar('avatar.jpg');
        static::assertEquals('avatar.jpg', $user->getAvatar());

        $user->addPhoto($photo);
        static::assertCount(1, $user->getPhotos());
        static::assertSame($photo, $user->getPhotos()->first());
    }
}
