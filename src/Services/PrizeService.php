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
    }

    private function init(): void
    {
        $this->availableMoney = $this->checkAvailableMoney();
        if ($this->availableMoney > 0) {
            array_push($this->prizeTypeArray, 'money');
        }
        $this->availableGifts = $this->checkAvailableGifts();
        if (count($this->availableGifts) > 0) {
            array_push($this->prizeTypeArray, 'gift');
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
        $this->init();
        $prizeKey = array_rand($this->prizeTypeArray);

        $prize = new Prize();
        $prize->setUser($this->user);
        $prize->setLottery($this->currentLottery);
        $prize->setPrizeDate(new \DateTime());

        $prizeType = $this->prizeTypeRep->findOneBy(['name' => $this->prizeTypeArray[$prizeKey]]);
        if ($prizeType) {
            $prize->setPrizeType($prizeType);
        }

        switch ($this->prizeTypeArray[$prizeKey]) {
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

    private function getRandomMoneyLoyalty(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    private function getRandomGifts(): PrizeItem
    {
        $index = array_rand($this->availableGifts);
        return $this->availableGifts[$index];
    }

    private function normalizePrize(Prize $prize): array
    {
        $normalizedPrize = [];
        $normalizedPrize['id'] = $prize->getId();
        $normalizedPrize['type'] = $prize->getPrizeType() ? $prize->getPrizeType()->getName() : '';
        $normalizedPrize['summ'] = $prize->getPrizeSum();
        $normalizedPrize['gift'] = $prize->getPrizeItem() ? $prize->getPrizeItem()->getName() : null;
        $normalizedPrize['title'] = $normalizedPrize['type'] ? self::PRIZE_TITLE[$normalizedPrize['type']] : '';
        return $normalizedPrize;
    }

    public function rejectPrize(int $id): array
    {
        $prize = $this->prizeRep->find($id);
        if (!$prize) {
            return ['message' => 'А тут и не от чего отказываться!'];
        }
        $prize->setRejectFlag(true);
        $this->em->persist($prize);
        $this->em->flush();
        return ['message' => 'Вы успешно отказались. Надеемся, что у Вас всё в порядке!'];
    }

    public function convertMoneyToLoyalty(int $id): array
    {
        $prize = $this->prizeRep->find($id);
        if (!$prize || $prize->getPrizeType()->getName() !== 'money') {
            return ['message' => 'А тут нечего конвертировать'];
        }
        if ($prize->getConvertDate()) {
            return ['message' => 'Уже сконвертированы ' . $prize->getConvertDate()->format('d-m-Y H:i')];
        }
        $coefficient = $this->currentLottery->getExchangeCoefficient() ? $this->currentLottery->getExchangeCoefficient() : 1;
        $sum = (int)($prize->getPrizeSum() * $coefficient);
        $this->user->setLoyaltyPoints($sum);
        $this->em->persist($this->user);
        $this->em->flush();
        return ['message' => 'Вам зачисленно ' . $sum . ' баллов. Ваш балланс: ' . $this->user->getLoyaltyPoints()];

    }

    public function sentGiftByPost(int $id): array
    {
        $prize = $this->prizeRep->find($id);
        if (!$prize || $prize->getPrizeType()->getName() !== 'gift') {
            return ['message' => 'А тут нечего отсылать'];
        }

        if (!$this->user->getAddress()) {
            return ['message' => 'Введите свой адрес в личном кабинете'];
        }

        $prize->setSendDate(new \DateTime());
        $this->em->persist($prize);
        $this->em->flush();
        return ['message' => 'Подарок успешно отправлен. Не забывайте проверять почтовый ящик.'];
    }

    public function sentMoneyToBank(int $id): array
    {
        $prize = $this->prizeRep->find($id);
        if (!$prize || $prize->getPrizeType()->getName() !== 'money' || $prize->getPrizeSum() <= 0) {
            return ['message' => 'А тут нечего перечислять'];
        }
        if (!$this->user->getBankAccountNum()) {
            return ['message' => 'Уточните свой банковский счет в личном кабинете'];
        }
        $prize->setSendDate(new \DateTime());

        if (true) {
            //Вызываем API банка, перечисляем туда деньги, если все ок
            $this->em->persist($prize);
            $this->em->flush();
            return ['message' => 'На Ваш банковский счет перечисленно ' . $prize->getPrizeSum()];
        } else {
            return ['message' => 'Проблемы с доступом к Вашему счету, попробуйте позже'];
        }
    }
}