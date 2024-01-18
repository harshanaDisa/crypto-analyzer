<?php
namespace App\Service;

use BlockCypher\Client\AddressClient;
use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Rest\ApiContext;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Psr\Log\LoggerInterface;

class CryptoAnalyseService
{
    private $blockcypherToken;
    private $logEnabled;
    private $logFileName;
    private $logLevel;
    private $logger;
    private $session;

    public function __construct(string $blockcypherToken, bool $logEnabled, string $logFileName, string $logLevel, LoggerInterface $logger, SessionInterface $session)
    {
        $this->blockcypherToken = $blockcypherToken;
        $this->logEnabled = $logEnabled;
        $this->logFileName = $logFileName;
        $this->logLevel = $logLevel;
        $this->logger = $logger;
        $this->session = $session;
    }

    public function createApiContext(string $network, string $asset, string $version): ApiContext
    {
        $apiContext = ApiContext::create(
            $network,
            $asset,
            $version,   
            new SimpleTokenCredential($this->blockcypherToken),
            array(
                'log.LogEnabled' => $this->logEnabled,
                'log.FileName' => $this->logFileName,
                'log.LogLevel' => $this->logLevel
            )
        );

        return $apiContext;
    }

    public function getTransactionsForAddress(string $network, string $asset, string $version, string $address)
    {
        $apiContext = $this->createApiContext($network, $asset, $version);

        $addressClient = new AddressClient($apiContext);
        try {
            $fullAddress = $addressClient->getFullAddress($address);
        } catch (\Exception $e) {
            // Log the error message
            $this->logger->error('Failed to get full address: ' . $e->getMessage());

            // Add a flash message to inform the user
            //$this->session->getFlashBag()->add('error', 'Failed to get full address. Please try again.');

        
            // Return or redirect to handle the error
            return [];
        }
        return $fullAddress->getTxs();
    }
}