<?php
/*
 * Copyright (c) 2021. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds\Tests;

use VenelinIliev\Borica3ds\SaleRequest;
use VenelinIliev\Borica3ds\ReversalRequest;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;

class ReversaslRequestTest extends TestCase
{
    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testData()
    {
        $saleData = (new ReversalRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setMerchantName('Some Company Ltd.')
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setRrn('118601289138')
            ->setIntRef('CEAD95D777876F81')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setPublicKey(__DIR__ . '/certificates/public.cer')
            ->setSigningSchemaMacExtended()
            ->getData();

        $this->assertEquals([
            'TRTYPE' => 24,
            'CURRENCY' => 'BGN',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_NAME' => 'Some Company Ltd.',
            'RRN' => '118601289138',
            'INT_REF' => 'CEAD95D777876F81',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '765C034CC1AC9213B59AE0D251C48B3883AA44DF73A57E4E12F73786B4847F42D40AF5CAFC33F2A065DF5EF28C095FBA373F4A9D08CD27CCEA9447CFB49A80404A1CB3067223C8AF0964C6A7E7960C43CB4B3C75FF9063C8931C0457CB6B43D9F536DD32950AF90E05A887079149415D1FFA1017ED716696D256FE9DBF69D6C4CB10AB71F60C7405A90E4E111CE37C62901A02188A74D7BA84FABB02E0D877DA998E29DEF0057CD5EAF5CAD0C6132EDB2A9CAE0556FD360F26BD47869802B0EB3C40D1205524AEA0FF5CF413A887F66DD032FCD09C5281C834B072BAEDF92950F4BCE2DA32A4A6E9392B46F9FA55BD08E64F16EE096CCD43DA4F077FFC5A9700'
        ], $saleData);
    }

    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testBackRefValidation()
    {
        $this->expectException(ParameterValidationException::class);

        (new SaleRequest())
            ->setBackRefUrl('wrong url value');
    }
}
