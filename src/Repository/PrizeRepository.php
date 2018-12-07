<?php

namespace App\Repository;

use App\Entity\Prize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method Prize|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prize|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prize[]    findAll()
 * @method Prize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrizeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Prize::class);
    }

    public function findSumMoneyPrizeByLottery(int $id): int
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('money_sum', 'money_sum');
        $em = $this->getEntityManager();
        $query = $em->createNativeQuery(
            "SELECT SUM(p.prize_sum) as money_sum FROM prize  p 
JOIN prize_type pr ON pr.id = p.prize_type_id WHERE p.lottery_id = :id AND pr.name = 'money' 
AND p.user_id IS NOT NULL AND p.reject_flag IS NOT TRUE",
            $rsm
        );
        $query->setParameter('id', $id);
        $result = $query->getSingleResult();

        return $result['money_sum'] ?? 0;
    }

    public function findAvailableGiftsByLottery(int $id): array
    {
        //TODO: оптимизировать запрос
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(\App\Entity\PrizeItem::class, 'pi');
        $rsm->addFieldResult('pi', 'id', 'id');
        $rsm->addFieldResult('pi', 'name', 'name');
        $rsm->addMetaResult('pi', 'lottery_id', 'lottery_id');
        $em = $this->getEntityManager();
        $query = $em->createNativeQuery(
            'SELECT id,name, lottery_id  FROM prize_item as pi 
WHERE pi.lottery_id = :id AND pi.id  NOT IN (SELECT prize_item_id FROM prize WHERE lottery_id = :id AND prize_item_id 
IS NOT NULL AND reject_flag IS NOT TRUE)',
            $rsm
        );
        $query->setParameter('id', $id);

        return $query->getResult();
    }

    /**
     * @param int $userId
     * @param int $lotteryId
     * @return array
     */
    public function findNotSentMoneyPrizeByUserAndLottery(int $userId, int $lotteryId): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('prize_sum', 'prize_sum');
        $em = $this->getEntityManager();
        $query = $em->createNativeQuery(
            "SELECT p.id, p.prize_sum FROM prize p JOIN prize_type pr 
ON pr.id = p.prize_type_id WHERE p.lottery_id = :id AND p.user_id = :user_id AND pr.name = 'money' 
AND p.send_date IS  NULL AND p.reject_flag IS NOT TRUE",
            $rsm
        );
        $query->setParameter('id', $lotteryId);
        $query->setParameter('user_id', $userId);

        return $query->getResult();
    }

    public function setSendDateForTransferedMoney(array $ids): bool
    {
        $connection = $this->getEntityManager()->getConnection();
        $idsString = implode(",", $ids);
        $statement = $connection->prepare("UPDATE prize SET send_date = now() WHERE id IN  (".$idsString.")");

        return (boolean)$statement->execute();
    }

}
