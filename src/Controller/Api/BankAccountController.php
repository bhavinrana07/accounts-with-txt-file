<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * MartianCoordinatedTime controller.
 * @Route("/api", name="api_")
 */
class BankAccountController extends FOSRestController
{

    const RESET_AMOUNT = 10000;
    const DIR_ACC = '\accounts';
    /**
     * Lists all accounts.
     * @Rest\Get("/accounts")
     *
     * @return Response
     */
    public function getAccountsAction()
    {
        $listFiles = self::getAccounts();

        $serializer = $this->get('jms_serializer');
        $response = $serializer->serialize($listFiles, 'json');
        return new Response($response);
    }

    /**
     * getAccounts function
     * to get the list of account in that directory
     * 
     * @return void
     */
    public function getAccounts()
    {
        $dir = getcwd() . self::DIR_ACC;
        $files = scandir($dir);
        $listFiles = [];
        foreach ($files as $file) {
            $filename = $dir . "\\" . $file;
            if (is_file($filename)) {
                $myfile = fopen($filename, "r");
                $content =  fread($myfile, filesize($filename));
                fclose($myfile);
                $listFiles[] = ['name' => $file, 'balance' => $content];
            }
        }
        return $listFiles;
    }



    /**
     * Reset all Accounts.
     * @Rest\Get("/resetAccounts")
     *
     * @return Response
     */
    public function resetAccountsAction()
    {
        $dir = getcwd() . self::DIR_ACC;
        $files = scandir($dir);
        $listFiles = [];
        foreach ($files as $file) {
            $filename = $dir . "\\" . $file;
            if (is_file($filename)) {
                $myfile = file_put_contents($filename, self::RESET_AMOUNT);
                $listFiles[] = ['name' => $file, 'balance' => self::RESET_AMOUNT];
            }
        }

        $serializer = $this->get('jms_serializer');
        $response = $serializer->serialize($listFiles, 'json');
        return new Response($response);
    }


    /**
     * Transfer Fund.
     * @Rest\Post("/transfer")
     *
     * @return Response
     */
    public function postTransferFundAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $transfer_from = $data['transfer_from'];
        $transfer_to = $data['transfer_to'];
        $amount = $data['amount'];

        if (!self::validateAmount($amount)) {
            return self::errorResponse(0, 'Not a valid amount.');
        }
        if ($transfer_from  == $transfer_to) {
            return self::errorResponse(0, 'Transfer From and Transfer to account can not be the same.');
        }
        if ((int)$transfer_from ==0  || (int) $transfer_to == 0) {
            return self::errorResponse(0, 'Transfer From or Transfer To Account is not valid.');
        }

        $dir = getcwd() . '\accounts';
        $filename_from = $dir . "\\" . 'user' . $transfer_from . '.txt';
        $filename_to = $dir . "\\" . 'user' . $transfer_to . '.txt';

        if (is_file($filename_from)) {

            $transfer_from_amount = self::getAmount($filename_from);
            $transfer_to_amount = self::getAmount($filename_to);

            if ((int) $transfer_from_amount < (int) $amount) {
                return self::errorResponse(0, 'not enough fund.');
            }

            $newAmountFrom = (int) $transfer_from_amount - $amount;
            self::setAmount($filename_from, $newAmountFrom);

            $newAmountTo = (int) $transfer_to_amount + $amount;
            self::setAmount($filename_to, $newAmountTo);

            $listFiles = self::getAccounts();

            $serializer = $this->get('jms_serializer');
            $response = $serializer->serialize($listFiles, 'json');
            return new Response($response);
        }
    }

    /**
     * errorResponse function
     * basic function to give the standard output while there is an error
     * @param [type] $status
     * @param [type] $mesaage
     * @return void
     */
    private function errorResponse($status, $mesaage)
    {
        $resp = ['status' => $status, 'message' => $mesaage];
        $serializer = $this->get('jms_serializer');
        $response = $serializer->serialize($resp, 'json');
        return new Response($response);
    }

    /**
     * validateAmount function
     * to validate the amount passed from the API
     * @param [type] $amount
     * @return void
     */
    public  function validateAmount($amount)
    {
        return (int)$amount > 0;
    }

    /**
     * getAmount function
     * to get the value from users account
     *
     * @param string $filename_from
     * @return void
     */
    public function getAmount(string $filename_from)
    {
        if (!is_file($filename_from)) {
            return false;
        }
        $myfile = fopen($filename_from, "r");
        $amount =  fread($myfile, filesize($filename_from));
        fclose($myfile);
        return $amount;
    }
    /**
     * setAmount function
     * to set the amount value in the user account
     *
     * @param string $filename
     * @param integer $amount
     * @return void
     */
    public function setAmount(string $filename, int $amount)
    {
        if (is_file($filename)) {
            return file_put_contents($filename, $amount);
        }
        return false;
    }
}
