<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

abstract class AbstractDataFixtures extends Fixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    public const USER_FIXTURES = 1;
    public const PHOTO_FIXTURES = 2;
}