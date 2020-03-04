<?php

use App\Controller\Api\BankAccountController;
use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    /**
     * testSetAmountWithPath function
     * will test the set amount function for path
     * @return void
     */
    public function testSetAmountWithPath()
    {
        $BankAccountController = new BankAccountController;
        $result = $BankAccountController->setAmount('wrongpath\User1.txt',10000);
        $this->assertFalse($result);
    }

    /**
     * testGetAmountWithPath function
     * will test the GetAmount function for path
     * @return void
     */
    public function testGetAmountWithPath()
    {
        $BankAccountController = new BankAccountController;
        $result = $BankAccountController->getAmount('wrongpath\User1.txt');
        $this->assertFalse($result);
    }

    /**
     * testValidateAmountWithPath function
     * will test the valiateAmount function for valid amount
     * @return void
     */
    public function testValidateAmountWithPath()
    {
        $BankAccountController = new BankAccountController;
        $result = $BankAccountController->validateAmount('####');
        $this->assertFalse($result);
    } 
}
