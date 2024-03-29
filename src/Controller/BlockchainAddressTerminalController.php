<?php
namespace App\Controller;


use App\Service\CryptoTransactionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class BlockchainAddressTerminalController extends Command
{


    private $cryptotransactionService;

    public function __construct(CryptoTransactionService $cryptotransactionService)
    {
        $this->cryptotransactionService = $cryptotransactionService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('blockchain:address:full')
            ->setDescription('all information available about a particular address');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $networkInput = new Question('Please enter the network main, test3, bcy-test(default:main): ', 'main');
        $network = $helper->ask($input, $output, $networkInput);

        $coinInput = new Question('Please enter the coin btc, ltc, doge, dash, etc.(default: btc): ', 'btc');
        $asset = $helper->ask($input, $output, $coinInput);

        $addressInput = new Question('Please enter the address(default:16Fg2yjwrbtC6fZp61EV9mNVKmwCzGasw5): ', '16Fg2yjwrbtC6fZp61EV9mNVKmwCzGasw5');
        $address = $helper->ask($input, $output, $addressInput);

        $versionInput = new Question('Please enter the version(default:v1): ', 'v1');
        $version = $helper->ask($input, $output, $versionInput);
    
        $output->writeln("You entered: ".$asset ." " . $address);
        $transactions = $this->cryptotransactionService->analyzeAndProcessTransactions($network, $asset, $version, $address);


        // Count the transactions
        $transactionCount = count($transactions);
        // Make app calls to blockcypher using the address client
        $output->writeln("Number of transactions: " . $transactionCount);

        // Write the output to the terminal
        foreach ($transactions as $transaction) {
            $output->writeln($transaction->getHash());
        }

        return Command::SUCCESS;
    }
}