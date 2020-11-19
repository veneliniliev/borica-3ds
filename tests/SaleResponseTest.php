<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Tests;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\DataMissingException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\SaleResponse;

class SaleResponseTest extends TestCase
{

    /**
     * @throws DataMissingException
     * @throws ParameterValidationException|SignatureException
     */
    public function testSuccessFalseResponse()
    {
        $post = [
            'ACTION' => 1,
            'RC' => '00',
            'APPROVAL' => 'S97539',
            'TERMINAL' => self::TERMINAL_ID,
            'TRTYPE' => TransactionType::SALE,
            'AMOUNT' => '9.00',
            'CURRENCY' => 'BGN',
            'ORDER' => '154744',
            'RRN' => '028601253152',
            'INT_REF' => '97E2F39EFCA1CAF1',
            'PARES_STATUS' => '-',
            'ECI' => '-',
            'TIMESTAMP' => '20201012160009',
            'NONCE' => '9EADBD70C0A5AFBAD3DF405902602F79',
            'P_SIGN' => '6DC2281BC85300FFB2AE5EC766E51B706FE19E411173A096A1A631D7539BECE5FB5E033F2854F9F723BB169A67EA76869590DBDE160293A50488C48726532A646E5C76ECDC11A5B9B95839E82322523335A832EB40CDFA38EA61F7C0B5521AE9852A88862398BB1FCAC4BEC765E9BF32848022B07F2DCB1D57BB40DB73F30493E6B75FF69D6C12C757730703492CCB200E3E5E7C59D219D1970F86FD38860F37C22099E2E108F552458C11B6FFD1C3834DF72CBEBE154EBC98AA23CEFF5EF084492DB661E7BDF1E960D54593EAC4CE8CF597ECAC5A61E16C1C3BD5353033FBFABE145C238CC3FA2044DBFF9B8E10F81D6A5E94D2014C964598ED4E87995537BB'
        ];

        $this->expectException(SignatureException::class);

        (new SaleResponse())
            ->setPublicKey(__DIR__ . '/certificates/public.cer')
            ->setResponseData($post)
            ->isSuccessful();
    }
}
