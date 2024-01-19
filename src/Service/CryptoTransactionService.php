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
 
            // Try to find the transaction in the database
            $transactionsData = $this->entityManager->getRepository(Transaction::class)->findBy([
                'network' => $network,
                'asset' => $asset,
                'version' => $version,
                'address' => $address
            ]);

            
            // If the transaction doesn't exist in the database
            if (!count($transactionsData) > 0) {
  
                // Use the cryptoAnalyseService to get the transaction
                $transactionsData = $this->cryptoAnalyseService->getTransactionsForAddress($network, $asset, $version, $address);
                foreach ($transactionsData as $tx) {
                    $confirmedDate = $tx->confirmed;
                        
                    
                try {
                    $transaction = new Transaction();
                    $transaction->setAddress($address);
                    $transaction->setAsset($asset);
                    $transaction->setNetwork($network);
                    $transaction->setVersion($version);
                    $transaction->setConfirmedDate(new \DateTime($confirmedDate));
                    $transaction->setHash($tx->hash);
        
                    $this->entityManager->persist($transaction);
                    $this->entityManager->flush();
                } catch (UniqueConstraintViolationException $e) {
                   $this->logger->error(''. $e->getMessage());
                }
            }           

        }
        return $transactionsData;



    }
}