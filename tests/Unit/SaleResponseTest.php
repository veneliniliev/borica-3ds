<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds\Tests\Unit;

use VenelinIliev\Borica3ds\Enums\Action;
use VenelinIliev\Borica3ds\Enums\ResponseCode;
use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\DataMissingException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\SaleResponse;
use VenelinIliev\Borica3ds\Tests\TestCase;

class SaleResponseTest extends TestCase
{

    /**
     * @throws DataMissingException
     * @throws ParameterValidationException|SignatureException
     */
    public function testSuccessFalseResponse()
    {
        $post = [
            'ACTION' => Action::DUPLICATE,
            'RC' => ResponseCode::SUCCESS,
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
            ->setPublicKey(__DIR__ . '/../certificates/public.cer')
            ->setResponseData($post)
            ->isSuccessful();
    }

    /**
     * @throws DataMissingException
     * @throws ParameterValidationException|SignatureException
     */
    public function testSuccessMacGeneralResponse()
    {
        $this->markTestSkipped('Да се провери защо не верифицира добре подписа!');

        $post = [
            'ACTION' => Action::SUCCESS,
            'RC' => ResponseCode::SUCCESS,
            'APPROVAL' => 'S19527',
            'TERMINAL' => self::TERMINAL_ID,
            'TRTYPE' => TransactionType::SALE,
            'AMOUNT' => '1.00',
            'CURRENCY' => 'BGN',
            'ORDER' => '170403',
            'RRN' => '028701253242',
            'INT_REF' => 'B7A68A9F37E8586E',
            'PARES_STATUS' => '',
            'ECI' => '',
            'TIMESTAMP' => '20201013140707',
            'NONCE' => '22EA51788AFE61A9D814B771A8FA6379',
            'P_SIGN' => '31C6507191249D361086E1CA70A2A0374ACF9191D765055E10ACB93D720E934FEBE44E59D41D19C7B976CF358FA572B12EB08556EA602141E983F6FC93F106B0249780C192FAD7BC6411C33E966317804681D692CCDAF42F7494B1B7A7ED8AB23CB8DE5F0621E0C3582671BD222A3E5409538D9BD93F11B150B75D0C59AAC5E77D439FE14A6B494C8FECB1C23867A77D291E34425B5F1A6E9CBA9B92E3BC344E2C9AFAD45E2AE2D1313200A80DE26C2DD870E63AFEADA9EDAEF4DF5B32AD533D68665CB8F7F6E42D8ED7FFE31415FFAED25B3BA159063A9FC542FA958719016697CE9760954A58A2AF077BA049D1DD2216242D80572AA0EA98A39CD7C8DDB5BE'
        ];

        $isSuccess = (new SaleResponse())
            ->setPublicKey(__DIR__ . '/../certificates/public.cer')
            ->setResponseData($post)
            ->isSuccessful();

        $this->assertTrue($isSuccess);
    }
}
