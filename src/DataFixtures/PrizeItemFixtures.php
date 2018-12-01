<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 01.12.2018
 * Time: 22:33
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\PrizeItem;

class PrizeItemFixtures extends Fixture
{
    public const GIFT_IPHONE = 'iphone';
    public const GIFT_TESLA = 'tesla';

    public function load(ObjectManager $manager)
    {
        $item = new PrizeItem();
        $item->setName('Iphone');
        $item->setLottery($this->getReference(LotteryFixtures::CURRENT_LOTTERY));
        $manager->persist($item);
        $manager->flush();
        $this->addReference(self::GIFT_IPHONE, $item);

        $item1 = new PrizeItem();
        $item1->setName('Tesla car');
        $item1->setLottery($this->getReference(LotteryFixtures::CURRENT_LOTTERY));
        $manager->persist($item1);
        $manager->flush();
        $this->addReference(self::GIFT_TESLA, $item1);
    }

    public function getDependencies()
    {
        return [
            LotteryFixtures::class,
        ];
    }
}