<?php
namespace App\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Entity\Transaction;
use App\Service\CryptoAnalyseService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;




class CryptoTransactionService
{
    private $cryptoAnalyseService;
    private $entityManager;
    private $logger;

    public function __construct(CryptoAnalyseService $cryptoAnalyseService, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->cryptoAnalyseService = $cryptoAnalyseService;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function analyzeAndProcessTransactions($network, $asset, $version, $address)
    {
        $txs = $this->cryptoAnalyseService->getTransactionsForAddress($network, $asset, $version, $address);

        foreach ($txs as $tx) {
            $confirmedDate = $tx->confirmed;
    
            // Check if the transaction already exists in the database
            $existingTransaction = $this->entityManager->getRepository(Transaction::class)->findOneBy([
                'address' => $address,
                'asset' => $asset,
                'network' => $network,
                'version' => $version,
                'confirmedDate' => new \DateTime($confirmedDate),
            ]);
    
            if ($existingTransaction) {
                // If the transaction already exists, skip to the next iteration
                continue;
            }
    
            try {
                $transaction = new Transaction();
                $transaction->setAddress($address);
                $transaction->setAsset($asset);
                $transaction->setNetwork($network);
                $transaction->setVersion($version);
                $transaction->setConfirmedDate(new \DateTime($confirmedDate));
    
                $this->entityManager->persist($transaction);
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
               $this->logger->error(''. $e->getMessage());
            }
        }

        return $txs;


    }
}