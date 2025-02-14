<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */


use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\PreAuthorisationCompletionRequest;
use VenelinIliev\Borica3ds\Tests\TestCase;

class PreAuthorisationCompletionRequestTest extends TestCase
{
    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testData()
    {
        $saleData = (new PreAuthorisationCompletionRequest())
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
            'TRTYPE' => TransactionType::PRE_AUTHORISATION_COMPLETION,
            'CURRENCY' => 'BGN',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_NAME' => 'Some Company Ltd.',
            'RRN' => '118601289138',
            'INT_REF' => 'CEAD95D777876F81',
            'AD.CUST_BOR_ORDER_ID' => '145659',
            'ADDENDUM' => 'AD,TD',
            'MERCH_GMT' => date('O'),
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '53D671EBF0F8EDA6ECF262DC9C7E0A3710597AC7CE78E3F40FC8333E24C6866A4327BFCBC8E9C819E1BAF9837C3CF27DF8E87E141032BD1CB0FA3D448217EF326AE25D433CEAF9BA2D08EB45F869DB271D75938AE131973DB7E196309930A1768C4F1CBD6BA7CB0FC2578284C4C738F71352D62816FEA8EF893D9961597D65B00A250E67B4C337BDB049CFCE39407E07EEEF6728D6C72DBA23780F88CDB0554A7D59F1A4E4D110BD96E96B8935213DA18C98C71DEC9A7EA9A9D2D13EF4EA7FE9607E5B913377C9F4988B81A055A2804EF718E6E9801133BBC63C334EE660DDA209331A96679472F5EAFE950D23ED56D33E2FB85C6EDF3E50FEB7DFFA233CFAFB'
        ], $saleData);
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testDataMacGeneral()
    {
        $data = (new PreAuthorisationCompletionRequest())
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
            'TRTYPE' => TransactionType::PRE_AUTHORISATION_COMPLETION,
            'CURRENCY' => 'BGN',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201014095541',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_NAME' => 'Some Company Ltd.',
            'RRN' => '028701253242',
            'INT_REF' => 'B7A68A9F37E8586E',
            'AD.CUST_BOR_ORDER_ID' => '145659',
            'ADDENDUM' => 'AD,TD',
            'MERCH_GMT' => date('O'),
            'NONCE' => '7D51498A3C22B86DD57EFB699A175714',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '6C96B9DAB776A77FE7FCA62F2D8CE3A8D1D1E6406004561DB1137940B9D5409328963544FDE85C50C8C3FB3F194C10A0480CD2A0DFE3DB2C1459BBF100BFC6464F67A5A3AB13709F78B8128619BCB7A7E747D1449630CE6B1C39776900C108959F749C98F11FC042C6FEA027384458A42D039B4667A01F5D2CF8AFD4A0D676C39871487525D1AEE8046B3FBEEEBEBBB48DCB7EE1BB4A9ED63FE38DE457FC6B7070B50DE2A60A6094699BDE7A4FA914C2DCF34B98E67FDE5B6EC9C9D3119E39C63F3074BEF41C084941DB680C09FD497F359B6A012D99EE36461C9311C7FDAE10D83A1C480BA9AD41E156145E03FC6C5E4E47F6276122F280A0895004EEA17424'
        ], $data);
    }
}
