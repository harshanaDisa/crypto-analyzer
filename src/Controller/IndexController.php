<?php

namespace App\Controller;

use App\Entity\Transaction;

use App\Form\Type\CryptoAnalyseType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CryptoTransactionService;


class IndexController extends AbstractController {

    /**
     * @Route("/", name="index")
    */
    public function index(Request $request, CryptoTransactionService $cryptoTransactionService): Response {

        // Create a new Transaction object
        $transaction = new Transaction();

        // Create the form
        $form = $this->createForm(CryptoAnalyseType::class, $transaction);

        // Handle the request
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $cryptoAnalyse = $request->request->get('crypto_analyse');

            $asset = $cryptoAnalyse['asset'];
            $address = $cryptoAnalyse['address'];

            $fromDate = $cryptoAnalyse['fromDate'];
            $toDate = $cryptoAnalyse['toDate'];
            $network = "main";
            $version = "v1";


            $transactions = $cryptoTransactionService->analyzeAndProcessTransactions($network, $asset, $version, $address);
            if($fromDate) {
                $transactions = array_filter($transactions, function ($transaction) use ($fromDate, $toDate) {
                    // Assuming $transaction->getDate() returns a DateTime object
                    $transactionDate = new \DateTime($transaction->getConfirmed());
                
                    // Define the start and end dates of the range
                    $startDate = new \DateTime($fromDate);
                    if($toDate == null) {
                        $endDate = new \DateTime($toDate);
                    }else {
                        $endDate = new \DateTime('now');
                    }
                
                    // Return true if the transaction date is within the range
                    return $transactionDate >= $startDate && $transactionDate <= $endDate;
                });
            }

            // Add a flash message
            if(count($transactions) == 0) {
                $this->addFlash('error', 'No transactions found for the given address.');
            }else{
                $this->addFlash('success', 'Transaction analyzed successfully.');
            }

            return $this->render('index.html.twig', [
                'form' => $form->createView(),
                'transactions' => $transactions
            ]);
        }

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
        ]);
    }  
    

}



