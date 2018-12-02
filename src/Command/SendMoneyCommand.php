<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 02.12.2018
 * Time: 21:06
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prize;
use App\Entity\User;
use App\Entity\Lottery;


class SendMoneyCommand extends Command
{
    private $em;
    private $userRep;
    private $priceRep;
    private $currentLottery;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRep = $this->em->getRepository(User::class);
        $this->priceRep = $this->em->getRepository(Prize::class);
        $this->currentLottery = $this->em->getRepository(Lottery::class)->findActive();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Sending money prizes to user bank account')
            ->setHelp('This command allows to send money prizes to user bank account, using id user and part')
            ->addArgument('user_id', InputArgument::REQUIRED, 'User id')
            ->addArgument('count', InputArgument::OPTIONAL, 'Count prizes into transfer');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $input->getArgument('count') ?? null;
        $userId = $input->getArgument('user_id');
        $user = $this->userRep->find($userId);
        if(!$user){
            $output->writeln([
                'Error',
                'User with id '.$userId . ' not found!',
                '<------------------------------------>',
            ]);
        }else{
            $prizes = $this->priceRep->findNotSentMoneyPrizeByUserAndLottery($userId, $this->currentLottery->getId());
            $prizeCount = count($prizes);
            if($prizeCount > 0){
                $output->writeln([
                    'Money transfer for ' . $prizeCount . ' prizes',
                    '================================================'
                ]);
                $preparedPrizes = $this->prepareMoneyPrizes($prizes,$count);

                for($i = 0; $i < count($preparedPrizes); ++$i){
                    $output->writeln([
                        'transaction number ' . ($i + 1) . '; transfering ' .array_sum($preparedPrizes[$i]['sum']),
                        '================================================'
                    ]);
                    //Здесь должен быть вызов API перевода в банк
                    $output->writeln([
                        'successfully: ' . ($this->priceRep->setSendDateForTransferedMoney($preparedPrizes[$i]['ids']) ? 'Yes' : 'No')
                    ]);
                }
            }else{
                $output->writeln([
                    'Sorry, you have no prize money for transfer',
                    '   GOOD BYE   '
                ]);
            }

        }

    }

    /**
     * @param array $prizes
     * @param int|null $count
     * @return array
     */
    private function prepareMoneyPrizes(array $prizes, ?int $count):array
    {
        $preparedPrizes = [];
        $countFlag = 0;
        $index = 0;
        for($i = 0; $i < count($prizes); ++$i){
            if(is_null($count)){
                $preparedPrizes[0]['ids'][] = $prizes[$i]['id'];
                $preparedPrizes[0]['sum'][] = $prizes[$i]['prize_sum'];
            }else{
                $preparedPrizes[$index]['ids'][] = $prizes[$i]['id'];
                $preparedPrizes[$index]['sum'][] = $prizes[$i]['prize_sum'];
                $countFlag++;
                if($countFlag === $count){
                    $countFlag = 0;
                    $index++;
                }
            }
        }

        return $preparedPrizes;
    }
}