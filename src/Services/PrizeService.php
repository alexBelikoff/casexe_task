<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 02.12.2018
 * Time: 15:43
 */

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prize;
use App\Entity\PrizeType;
use App\Entity\PrizeItem;
use App\Entity\Lottery;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class PrizeService
{
    private $em;
    private $prizeRep;
    private $prizeTypeRep;
    private $user;
    private $currentLottery;
    private $availableMoney = null;
    private $availableGifts = [];
    private $prizeTypeArray = ['loyalty'];

    const MAX_LOYALTY = 5000;
    const PRIZE_TITLE = [
        'loyalty' => 'бонусные баллы',
        'money' => 'денежный приз',
        'gift' => 'подарок',
    ];

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->prizeRep = $this->em->getRepository(Prize::class);
        $this->prizeTypeRep = $this->em->getRepository(PrizeType::class);

        $this->currentLottery = $this->em->getRepository(Lottery::class)->findActive();
        $this->availableMoney = $this->checkAvailableMoney();
        if($this->availableMoney > 0){
            array_push($this->prizeTypeArray,'money');
        }
        $this->availableGifts = $this->checkAvailableGifts();
        if(count($this->availableGifts) > 0){
            array_push($this->prizeTypeArray,'gift');
        }
    }

    private function checkAvailableMoney()
    {
        $totalMoney = $this->currentLottery->getCashTotal();
        $spentMoney = $this->prizeRep->findSumMoneyPrizeByLottery($this->currentLottery->getId());
        return (int)$totalMoney - (int)$spentMoney;
    }

    private function checkAvailableGifts()
    {
        return $this->prizeRep->findAvailableGiftsByLottery($this->currentLottery->getId());
    }

    public function getPrize()
    {
        $prizeKey = array_rand($this->prizeTypeArray);

        $prize = new Prize();
        $prize->setUser($this->user);
        $prize->setLottery($this->currentLottery);
        $prize->setPrizeDate(new \DateTime());

        $prizeType = $this->prizeTypeRep->findOneBy(['name' => $this->prizeTypeArray[$prizeKey]]);
        if($prizeType){
            $prize->setPrizeType($prizeType);
        }

        switch ($this->prizeTypeArray[$prizeKey]){
            case 'loyalty':
                $min = $prizeType ? $prizeType->getRangeMin() : 0;
                $max = $prizeType ? $prizeType->getRangeMax() : self::MAX_LOYALTY;
                $prize->setPrizeSum($this->getRandomMoneyLoyalty($min, $max));
                break;
            case 'money':
                $min = $prizeType ? $prizeType->getRangeMin() : 0;
                $max = $prizeType ? ($prizeType->getRangeMax() > $this->availableMoney ? $this->availableMoney : $prizeType->getRangeMax()) : $this->availableMoney;
                $prize->setPrizeSum($this->getRandomMoneyLoyalty($min, $max));
                break;
            case 'gift':
                $prize->setPrizeItem($this->getRandomGifts());
                break;
        }

        $this->em->persist($prize);
        $this->em->flush();
        return $this->normalizePrize($prize);
    }

    private function getRandomMoneyLoyalty(int $min, int $max):int
    {
        return random_int($min, $max);
    }

    private function getRandomGifts():PrizeItem
    {
        $index = array_rand($this->availableGifts);
        return $this->availableGifts[$index];
    }

    private function normalizePrize(Prize $prize):array
    {
        $normalizedPrize = [];
        $normalizedPrize['id'] = $prize->getId();
        $normalizedPrize['type'] = $prize->getPrizeType() ? $prize->getPrizeType()->getName() : '';
        $normalizedPrize['summ'] = $prize->getPrizeSum();
        $normalizedPrize['gift'] = $prize->getPrizeItem() ? $prize->getPrizeItem()->getName() : null;
        $normalizedPrize['title'] = $normalizedPrize['type'] ? self::PRIZE_TITLE[$normalizedPrize['type']] : '';
        return $normalizedPrize;
    }

    public function rejectPrize(int $id):array
    {
        $prize = $this->prizeRep->find($id);
        if(!$prize){
            return ['message' => 'А тут и не от чего отказываться!'];
        }
        $prize->setRejectFlag(true);
        $this->em->persist($prize);
        $this->em->flush();
        return ['message' => 'Вы успешно отказались. Надеемся, что у Вас всё в порядке!'];
    }


}