<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 01.12.2018
 * Time: 22:12
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Address;

class AddressFixtures extends Fixture
{
    public const ADDRESS = 'address';

    public function load(ObjectManager $manager)
    {
        $address = new Address();
        $address->setCity('sankt petersburg');
        $address->setSteet('Nevsky Prospect ');
        $address->setHouse(1);
        $address->setFlat(1);
        $manager->persist($address);
        $manager->flush();
        $this->addReference(self::ADDRESS, $address);
    }
}