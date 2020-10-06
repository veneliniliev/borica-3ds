<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Tests;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\SaleResponse;

class SaleResponseTest extends TestCase
{

    public function testReponse()
    {
        $post = [
            'TERMINAL' => self::TERMINAL_ID,
            'TRTYPE' => TransactionType::SALE,
            'ORDER' => 'testtt',
            'AMOUNT' => '123.32',
            'CURRENCY' => 'BGN',
            'ACTION' => '3',
            'RC' => '-2',
            'APPROVAL' => null,
            'RRN' => null,
            'INT_REF' => null,
            'TIMESTAMP' => '20201005100731',
            'NONCE' => '78B638454721BAF6984EE1BD91B8012E',
            'P_SIGN' => '6DC2281BC85300FFB2AE5EC766E51B706FE19E411173A096A1A631D7539BECE5FB5E033F2854F9F723BB169A67EA76869590DBDE160293A50488C48726532A646E5C76ECDC11A5B9B95839E82322523335A832EB40CDFA38EA61F7C0B5521AE9852A88862398BB1FCAC4BEC765E9BF32848022B07F2DCB1D57BB40DB73F30493E6B75FF69D6C12C757730703492CCB200E3E5E7C59D219D1970F86FD38860F37C22099E2E108F552458C11B6FFD1C3834DF72CBEBE154EBC98AA23CEFF5EF084492DB661E7BDF1E960D54593EAC4CE8CF597ECAC5A61E16C1C3BD5353033FBFABE145C238CC3FA2044DBFF9B8E10F81D6A5E94D2014C964598ED4E87995537BB'
        ];

        $paymentSuccess = (new SaleResponse())
            ->setPublicKey(__DIR__ . '/certificates/public.cer')
            ->setResponseData($post)
            ->isSuccess();

        $this->assertEquals(false, $paymentSuccess);
    }
}
