<?php
namespace App\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Entity\Transaction;
use App\Service\CryptoAnalyseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class CryptoTransactionService
{
    private $cryptoAnalyseService;
    private $entityManager;
    private $managerRegistry;

    public function __construct(CryptoAnalyseService $cryptoAnalyseService, EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        $this->cryptoAnalyseService = $cryptoAnalyseService;
        $this->entityManager = $entityManager;
        $this->managerRegistry = $managerRegistry;
    }

    public function analyzeAndProcessTransactions($network, $asset, $version, $address)
    {
        //$apiContext = $this->cryptoAnalyseService->createApiContext($network, $asset, $version);
        $txs = $this->cryptoAnalyseService->getTransactionsForAddress($network, $asset, $version, $address);

        foreach ($txs as $tx) {
            $confirmedDate = $tx->confirmed;

            try {
                $transaction = new Transaction();
                $transaction->setAddress($address);
                $transaction->setAsset($asset);
                $transaction->setNetwork($network);
                $transaction->setVersion($version);
                $transaction->setConfirmedDate(new \DateTime($confirmedDate));

                $this->entityManager->getRepository(Transaction::class);
                $this->entityManager->persist($transaction);
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                if (!$this->entityManager->isOpen()) {
                    $this->entityManager = $this->managerRegistry->resetManager();
                }
            }



        }

        return $txs;


    }
}