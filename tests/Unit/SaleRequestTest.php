<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds\Tests\Unit;

use VenelinIliev\Borica3ds\Enums\Language;
use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\SaleRequest;
use VenelinIliev\Borica3ds\Tests\TestCase;

class SaleRequestTest extends TestCase
{
    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testSignature()
    {
        $sale = (new SaleRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setEmailAddress('test@test.com')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setAdCustBorOrderId('test')
            ->setSigningSchemaMacExtended();

        $this->assertEquals(
            '8125E0E604B8BC6430B03B1365B63D91ACB7210F2777776D7587A633D222368CB36936855090C81020318503998499503595EBB32092014A2843C7E6DB75C1AD7FCB018BB4CDA98B379B411E74C62881529A7787B73D8D0E00D1406E1D2A64ADD1A298CCDF3B5A13C14825990010541444122F4A8FBB23BB3747B962BEFB5C57C5737FCF8DC9E61F377777B661B04FFE604EE5E49EB87CA49737FD39AA27639DE0CEF11B527B630070BE97ECC81F0D14D355F37C5C684A040C615563C962CE137A0B7C7F0B3567DEB0A05C4D79F373D7938D4CBFCE86CA6AA5DBAC99081F3AB4C52E0A3B35748A7600ECE4278060B14F5D3ACE5D964A73F49CF8844B6C86E10E',
            $sale->generateSignature()
        );
    }

    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testSignatureMacGeneral()
    {
        $sale = (new SaleRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setEmailAddress('test@test.com')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setAdCustBorOrderId('test')
            ->setSigningSchemaMacGeneral();

        $this->assertEquals(
            '95B299B9706ED8D9FDA2F3EC3ADCBF0346A1299C512CFB498321DB8AFAE853F6A96BE472B54A75231F894D19F488E2BD3803D893E0924B678BD9777DDF922BCB0BD8F38E887E2FDEF675C428E7C023C420679D93E72A90A51B9B21E2209C5751813754F3ACC30F35BA3E61298D43BFBB2902B59B3B226F71BFA2DB8A17488B42FB60466983B421442DD4C9799C612579DECC32192153B62EF2AF02C24BD3433BE02AE7AB5976C7B769666DE5984293AE1CA814C9FB2E0D2B45FA098F0B08591832AEC8A334C6783A274F4C2D25E1B0296139439D41B313E1CDB4C730DBC2E32812135FE7E7F0CB97E535D1742EBA848B5F6D20259D364B46D9449955CE46B335',
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
        $sale = (new SaleRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setEmailAddress('test@test.com')
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
            '95B299B9706ED8D9FDA2F3EC3ADCBF0346A1299C512CFB498321DB8AFAE853F6A96BE472B54A75231F894D19F488E2BD3803D893E0924B678BD9777DDF922BCB0BD8F38E887E2FDEF675C428E7C023C420679D93E72A90A51B9B21E2209C5751813754F3ACC30F35BA3E61298D43BFBB2902B59B3B226F71BFA2DB8A17488B42FB60466983B421442DD4C9799C612579DECC32192153B62EF2AF02C24BD3433BE02AE7AB5976C7B769666DE5984293AE1CA814C9FB2E0D2B45FA098F0B08591832AEC8A334C6783A274F4C2D25E1B0296139439D41B313E1CDB4C730DBC2E32812135FE7E7F0CB97E535D1742EBA848B5F6D20259D364B46D9449955CE46B335',
            $sale->generateSignature()
        );
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testDataMacGeneral()
    {
        $saleData = (new SaleRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
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
            'TRTYPE' => TransactionType::SALE,
            //'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => '+03',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            //'MERCH_NAME' => null,
            'EMAIL' => 'test@test.com',
            'AD.CUST_BOR_ORDER_ID' => 'test',
            'LANG' => Language::BG,
            'ADDENDUM' => 'AD,TD',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '95B299B9706ED8D9FDA2F3EC3ADCBF0346A1299C512CFB498321DB8AFAE853F6A96BE472B54A75231F894D19F488E2BD3803D893E0924B678BD9777DDF922BCB0BD8F38E887E2FDEF675C428E7C023C420679D93E72A90A51B9B21E2209C5751813754F3ACC30F35BA3E61298D43BFBB2902B59B3B226F71BFA2DB8A17488B42FB60466983B421442DD4C9799C612579DECC32192153B62EF2AF02C24BD3433BE02AE7AB5976C7B769666DE5984293AE1CA814C9FB2E0D2B45FA098F0B08591832AEC8A334C6783A274F4C2D25E1B0296139439D41B313E1CDB4C730DBC2E32812135FE7E7F0CB97E535D1742EBA848B5F6D20259D364B46D9449955CE46B335'
        ], $saleData);
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testData()
    {
        $saleData = (new SaleRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
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
            'TRTYPE' => TransactionType::SALE,
            //'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => '+03',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            //'MERCH_NAME' => null,
            'EMAIL' => 'test@test.com',
            'AD.CUST_BOR_ORDER_ID' => 'test',
            'ADDENDUM' => 'AD,TD',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '8125E0E604B8BC6430B03B1365B63D91ACB7210F2777776D7587A633D222368CB36936855090C81020318503998499503595EBB32092014A2843C7E6DB75C1AD7FCB018BB4CDA98B379B411E74C62881529A7787B73D8D0E00D1406E1D2A64ADD1A298CCDF3B5A13C14825990010541444122F4A8FBB23BB3747B962BEFB5C57C5737FCF8DC9E61F377777B661B04FFE604EE5E49EB87CA49737FD39AA27639DE0CEF11B527B630070BE97ECC81F0D14D355F37C5C684A040C615563C962CE137A0B7C7F0B3567DEB0A05C4D79F373D7938D4CBFCE86CA6AA5DBAC99081F3AB4C52E0A3B35748A7600ECE4278060B14F5D3ACE5D964A73F49CF8844B6C86E10E'
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

        $saleData = (new SaleRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
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
            'TRTYPE' => TransactionType::SALE,
            //'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => '+03',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            //'MERCH_NAME' => null,
            'EMAIL' => 'test@test.com',
            'AD.CUST_BOR_ORDER_ID' => 'test',
            'ADDENDUM' => 'AD,TD',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'M_INFO' => 'eyJlbWFpbCI6InVzZXJAc2FtcGxlLmNvbSIsImNhcmRob2xkZXJOYW1lIjoiQ0FSREhPTERFUiBOQU1FIiwibW9iaWxlUGhvbmUiOnsiY2MiOiIzNTkiLCJzdWJzY3JpYmVyIjoiODkzOTk5OTg4OCJ9LCJ0aHJlZURTUmVxdWVzdG9yQ2hhbGxlbmdlSW5kIjoiMDQifQ==',
            'P_SIGN' => '8125E0E604B8BC6430B03B1365B63D91ACB7210F2777776D7587A633D222368CB36936855090C81020318503998499503595EBB32092014A2843C7E6DB75C1AD7FCB018BB4CDA98B379B411E74C62881529A7787B73D8D0E00D1406E1D2A64ADD1A298CCDF3B5A13C14825990010541444122F4A8FBB23BB3747B962BEFB5C57C5737FCF8DC9E61F377777B661B04FFE604EE5E49EB87CA49737FD39AA27639DE0CEF11B527B630070BE97ECC81F0D14D355F37C5C684A040C615563C962CE137A0B7C7F0B3567DEB0A05C4D79F373D7938D4CBFCE86CA6AA5DBAC99081F3AB4C52E0A3B35748A7600ECE4278060B14F5D3ACE5D964A73F49CF8844B6C86E10E'
        ], $saleData);
    }
}
