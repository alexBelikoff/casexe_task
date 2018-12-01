<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 01.12.2018
 * Time: 22:37
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Prize;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PrizeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $prize = new Prize();
        $prize->setLottery($this->getReference(LotteryFixtures::CURRENT_LOTTERY));
        $prize->setUser($this->getReference(UserFixtures::CURRENT_USER));
        $prize->setPrizeType($this->getReference(PrizeTypeFixtures::MONEY_PRIZE));
        $prize->getPrizeSum(3000);
        $prize->setPrizeDate(new \DateTime());
        $manager->persist($prize);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PrizeTypeFixtures::class,
            PrizeItemFixtures::class,
            LotteryFixtures::class,
            UserFixtures::class,
        ];
    }
}