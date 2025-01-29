<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds\Tests\Unit;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\ReversalRequest;
use VenelinIliev\Borica3ds\Tests\TestCase;

class ReversalRequestTest extends TestCase
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
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setRrn('118601289138')
            ->setIntRef('CEAD95D777876F81')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setPublicKey(__DIR__ . '/../certificates/public.cer')
            ->setSigningSchemaMacExtended()
            ->getData();

        $this->assertEquals([
            'TRTYPE' => TransactionType::REVERSAL,
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
     * @throws ParameterValidationException|SignatureException
     */
    public function testDataMacGeneral()
    {
        $data = (new ReversalRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setMerchantName('Some Company Ltd.')
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201014095541')
            ->setRrn('028701253242')
            ->setIntRef('B7A68A9F37E8586E')
            ->setNonce('7D51498A3C22B86DD57EFB699A175714')
            ->setPublicKey(__DIR__ . '/../certificates/public.cer')
            ->setSigningSchemaMacGeneral()
            ->getData();

        $this->assertEquals([
            'TRTYPE' => TransactionType::REVERSAL,
            'CURRENCY' => 'BGN',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201014095541',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_NAME' => 'Some Company Ltd.',
            'RRN' => '028701253242',
            'INT_REF' => 'B7A68A9F37E8586E',
            'NONCE' => '7D51498A3C22B86DD57EFB699A175714',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '277DC35B76CD5CAA9BB025A7A5B39EEBF1B3005EA5214F6EB781995FE65418378C5AFA60925977E9A3376D937292C7D57928E3F6B635C78C67411683FB38ABDB876A8EB122196D8534B355A9940934BAD88D2B7FBC25B43CD294059FA6BBB7FDFDC5DBDA0D9306D30F4E387EA879FBC59ED50E64569E3D36A068D6BC6CA57F1FC22F8B0373AF7B1612880648C68E428AF74374AE96A8043C99C99ED21C72B7FFB64EDFCD67BDFCC71B1220FF8CD7A2DFA106EDDD8F5D9B92E4AA8B46FA65F1C3849CE31635FEE43B950240FDE0EB3D638644B9066AE83051F96A34D64C8BF94E92A868C33684DD6A56BD2D26D104EDF8462E2585491BA8B65B8C2B9176C80FC8'
        ], $data);
    }
}
