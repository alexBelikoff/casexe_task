<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Lottery;

class LotteryFixtures extends Fixture
{
    public const CURRENT_LOTTERY = 'current-lottery';

    public function load(ObjectManager $manager)
    {
        $lottery = new Lottery();
        $startDate = new \DateTime();
        $endDate = new \DateTime();
        $lottery->setActive(true);
        $lottery->setStartDate($startDate->sub(new \DateInterval('P10D')));
        $lottery->setEndDate($endDate->add(new \DateInterval('P10D')));
        $lottery->setCashTotal(80000);
        $manager->persist($lottery);
        $manager->flush();
        $this->addReference(self::CURRENT_LOTTERY, $lottery);
    }
}
