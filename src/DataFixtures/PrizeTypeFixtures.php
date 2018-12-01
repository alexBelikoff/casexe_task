<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 01.12.2018
 * Time: 22:22
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\PrizeType;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PrizeTypeFixtures extends Fixture  implements DependentFixtureInterface
{
    public const MONEY_PRIZE = 'money';
    public const GIFT_PRIZE = 'gift';
    public const LOYALTY_PRIZE = 'loyalty';

    public function load(ObjectManager $manager)
    {
        $prizeType = new PrizeType();
        $prizeType->setRangeMin(500);
        $prizeType->setRangeMax(5000);
        $prizeType->setName('money');
        $prizeType->setLottery($this->getReference(LotteryFixtures::CURRENT_LOTTERY));
        $manager->persist($prizeType);
        $manager->flush();
        $this->addReference(self::MONEY_PRIZE, $prizeType);

        $prizeTypeG = new PrizeType();
        $prizeTypeG->setName('gift');
        $prizeTypeG->setLottery($this->getReference(LotteryFixtures::CURRENT_LOTTERY));
        $manager->persist($prizeTypeG);
        $manager->flush();
        $this->addReference(self::GIFT_PRIZE, $prizeTypeG);

        $prizeTypeL = new PrizeType();
        $prizeTypeL->setRangeMin(1000);
        $prizeTypeL->setRangeMax(15000);
        $prizeTypeL->setName('loyalty');
        $prizeTypeL->setLottery($this->getReference(LotteryFixtures::CURRENT_LOTTERY));
        $manager->persist($prizeTypeL);
        $manager->flush();
        $this->addReference(self::LOYALTY_PRIZE, $prizeTypeL);
    }

    public function getDependencies()
    {
        return [
            LotteryFixtures::class,
        ];
    }
}