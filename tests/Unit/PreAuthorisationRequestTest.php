<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */


use VenelinIliev\Borica3ds\Enums\Language;
use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\PreAuthorisationRequest;
use VenelinIliev\Borica3ds\Tests\TestCase;

class PreAuthorisationRequestTest extends TestCase
{
    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testSignature()
    {
        $sale = (new PreAuthorisationRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли предварително упълномощаване.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setEmailAddress('test@test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setAdCustBorOrderId('test')
            ->setSigningSchemaMacExtended();

        $this->assertEquals(
            '11798BAC7661D340742A12557F8D5A4E8DB0A7CF5577BF59072E4AD296D93C55D6C09CE8E14CFCE99F845099270BC5ADA7D856E24F8CCE7C036C8F4A5D234C29D3E3F19A881D28A3B097284A53E3CDF2552D3B2E5604DAFCBFF42ECE012B7B8367667CA25D91B1D3AD9CFD4F68F03186F0DE0BFA151B7F998B83F9B9EBA9C965BC8D1C9413363252B425905F71E59DD711608C58BE93BDE27B8E0C8CB0E2E493432B151B084AA53BAB1303DC2D50A53246EAB593ACD8990590067D7189B972D789A11FB6E22DCD6C44128F724E3A2692621CA4CDF8B272D4027052DE21011FB11B4CB9E5FD4FE1139774AF520DA276C317FBE9DA400B8DD0040D107C6892DF0F',
            $sale->generateSignature()
        );
    }

    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testSignatureMacGeneral()
    {
        $sale = (new PreAuthorisationRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли предварително упълномощаване.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setEmailAddress('test@test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setAdCustBorOrderId('test')
            ->setSigningSchemaMacGeneral();

        $this->assertEquals(
            '560BBF6E9C66501B7FE5EAE22D2321906C1908B53F1E369F2E8876BAE75C8A728A7ECED150EE85E46FEA74FD70C2F0CBF73D4360CF75337682997F11632CD6CE69AF5FFD20F47BD6270564E1B44B10AB5A825F648C00D2998441114C4A78F5575174B7A5BD2A2828FD8D3AB2160D740ACB7B2FD2C8BDEA50D9D65773EB0F07FCC40F660E3B21C3FA8E6323FAB0BDCF91FF49F3DD6C5CD011E37F0EEBC92FBA0D85452B15257009E982F4CB985C7D8DD85C7068AD0958FB3451FDF8767696B9782118845E51F0B36A65DD9F883CAF517756DDFD601959ED5379F46602543E1F3B3460DD005EE5FBC46719E1986A72225D5513D986A708A9519D1B32ED4C062976',
            $sale->generateSignature()
        );
    }

    /**
     * Set the language for the request.
     *
     * The signature must be same as without setting a language, because the language value is not included in signature.
     *
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testSignatureMacGeneralWithLang()
    {
        $sale = (new PreAuthorisationRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли предварително упълномощаване.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setEmailAddress('test@test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setAdCustBorOrderId('test')
            ->setLang(Language::BG)
            ->setSigningSchemaMacGeneral();

        $this->assertEquals(
            '560BBF6E9C66501B7FE5EAE22D2321906C1908B53F1E369F2E8876BAE75C8A728A7ECED150EE85E46FEA74FD70C2F0CBF73D4360CF75337682997F11632CD6CE69AF5FFD20F47BD6270564E1B44B10AB5A825F648C00D2998441114C4A78F5575174B7A5BD2A2828FD8D3AB2160D740ACB7B2FD2C8BDEA50D9D65773EB0F07FCC40F660E3B21C3FA8E6323FAB0BDCF91FF49F3DD6C5CD011E37F0EEBC92FBA0D85452B15257009E982F4CB985C7D8DD85C7068AD0958FB3451FDF8767696B9782118845E51F0B36A65DD9F883CAF517756DDFD601959ED5379F46602543E1F3B3460DD005EE5FBC46719E1986A72225D5513D986A708A9519D1B32ED4C062976',
            $sale->generateSignature()
        );
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testDataMacGeneral()
    {
        $saleData = (new PreAuthorisationRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли предварително упълномощаване.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setEmailAddress('test@test.com')
            ->setAdCustBorOrderId('test')
            ->setLang(Language::BG)
            ->setSigningSchemaMacGeneral()
            ->getData();

        $this->assertEquals([
            'TRTYPE' => TransactionType::PRE_AUTHORISATION,
            //'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => '+03',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли предварително упълномощаване.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            //'MERCH_NAME' => null,
            'EMAIL' => 'test@test.com',
            'BACKREF' => 'https://test.com/back-ref-url',
            'AD.CUST_BOR_ORDER_ID' => 'test',
            'LANG' => Language::BG,
            'ADDENDUM' => 'AD,TD',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '560BBF6E9C66501B7FE5EAE22D2321906C1908B53F1E369F2E8876BAE75C8A728A7ECED150EE85E46FEA74FD70C2F0CBF73D4360CF75337682997F11632CD6CE69AF5FFD20F47BD6270564E1B44B10AB5A825F648C00D2998441114C4A78F5575174B7A5BD2A2828FD8D3AB2160D740ACB7B2FD2C8BDEA50D9D65773EB0F07FCC40F660E3B21C3FA8E6323FAB0BDCF91FF49F3DD6C5CD011E37F0EEBC92FBA0D85452B15257009E982F4CB985C7D8DD85C7068AD0958FB3451FDF8767696B9782118845E51F0B36A65DD9F883CAF517756DDFD601959ED5379F46602543E1F3B3460DD005EE5FBC46719E1986A72225D5513D986A708A9519D1B32ED4C062976'
        ], $saleData);
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testData()
    {
        $saleData = (new PreAuthorisationRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли предварително упълномощаване.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setEmailAddress('test@test.com')
            ->setAdCustBorOrderId('test')
            ->setSigningSchemaMacExtended()
            ->getData();

        $this->assertEquals([
            'TRTYPE' => TransactionType::PRE_AUTHORISATION,
            //'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => '+03',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли предварително упълномощаване.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            //'MERCH_NAME' => null,
            'EMAIL' => 'test@test.com',
            'BACKREF' => 'https://test.com/back-ref-url',
            'AD.CUST_BOR_ORDER_ID' => 'test',
            'ADDENDUM' => 'AD,TD',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '11798BAC7661D340742A12557F8D5A4E8DB0A7CF5577BF59072E4AD296D93C55D6C09CE8E14CFCE99F845099270BC5ADA7D856E24F8CCE7C036C8F4A5D234C29D3E3F19A881D28A3B097284A53E3CDF2552D3B2E5604DAFCBFF42ECE012B7B8367667CA25D91B1D3AD9CFD4F68F03186F0DE0BFA151B7F998B83F9B9EBA9C965BC8D1C9413363252B425905F71E59DD711608C58BE93BDE27B8E0C8CB0E2E493432B151B084AA53BAB1303DC2D50A53246EAB593ACD8990590067D7189B972D789A11FB6E22DCD6C44128F724E3A2692621CA4CDF8B272D4027052DE21011FB11B4CB9E5FD4FE1139774AF520DA276C317FBE9DA400B8DD0040D107C6892DF0F'
        ], $saleData);
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testDataWithMInfo()
    {
        $mInfo = [
            'email' => 'user@sample.com',
            'cardholderName' => 'CARDHOLDER NAME',
            'mobilePhone' => [
                'cc' => '359',
                'subscriber' => '8939999888'
            ],
            'threeDSRequestorChallengeInd' => '04',
        ];

        $saleData = (new PreAuthorisationRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли предварително упълномощаване.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setEmailAddress('test@test.com')
            ->setAdCustBorOrderId('test')
            ->setSigningSchemaMacExtended()
            ->setMInfo($mInfo)
            ->getData();

        $this->assertEquals([
            'TRTYPE' => TransactionType::PRE_AUTHORISATION,
            //'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => '+03',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли предварително упълномощаване.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            //'MERCH_NAME' => null,
            'EMAIL' => 'test@test.com',
            'BACKREF' => 'https://test.com/back-ref-url',
            'AD.CUST_BOR_ORDER_ID' => 'test',
            'ADDENDUM' => 'AD,TD',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'M_INFO' => 'eyJlbWFpbCI6InVzZXJAc2FtcGxlLmNvbSIsImNhcmRob2xkZXJOYW1lIjoiQ0FSREhPTERFUiBOQU1FIiwibW9iaWxlUGhvbmUiOnsiY2MiOiIzNTkiLCJzdWJzY3JpYmVyIjoiODkzOTk5OTg4OCJ9LCJ0aHJlZURTUmVxdWVzdG9yQ2hhbGxlbmdlSW5kIjoiMDQifQ==',
            'P_SIGN' => '11798BAC7661D340742A12557F8D5A4E8DB0A7CF5577BF59072E4AD296D93C55D6C09CE8E14CFCE99F845099270BC5ADA7D856E24F8CCE7C036C8F4A5D234C29D3E3F19A881D28A3B097284A53E3CDF2552D3B2E5604DAFCBFF42ECE012B7B8367667CA25D91B1D3AD9CFD4F68F03186F0DE0BFA151B7F998B83F9B9EBA9C965BC8D1C9413363252B425905F71E59DD711608C58BE93BDE27B8E0C8CB0E2E493432B151B084AA53BAB1303DC2D50A53246EAB593ACD8990590067D7189B972D789A11FB6E22DCD6C44128F724E3A2692621CA4CDF8B272D4027052DE21011FB11B4CB9E5FD4FE1139774AF520DA276C317FBE9DA400B8DD0040D107C6892DF0F'
        ], $saleData);
    }

    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testBackRefValidation()
    {
        $this->expectException(ParameterValidationException::class);

        (new PreAuthorisationRequest())
            ->setBackRefUrl('wrong url value');
    }
}
