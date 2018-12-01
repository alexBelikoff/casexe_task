<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;
    public const CURRENT_USER = 'user';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('casexe');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'casexe'
        ));
        $user->setAddress($this->getReference(AddressFixtures::ADDRESS));
        $user->setBankAccountNum('7777 7777 7777 7777 7777');
        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::CURRENT_USER, $user);
    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
        ];
    }
}
