<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Tests;

use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\Sale;

class SaleTest extends TestCase
{
    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testSignature()
    {
        $sale = (new Sale())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3');

        $this->assertEquals(
            '8125E0E604B8BC6430B03B1365B63D91ACB7210F2777776D7587A633D222368CB36936855090C81020318503998499503595EBB32092014A2843C7E6DB75C1AD7FCB018BB4CDA98B379B411E74C62881529A7787B73D8D0E00D1406E1D2A64ADD1A298CCDF3B5A13C14825990010541444122F4A8FBB23BB3747B962BEFB5C57C5737FCF8DC9E61F377777B661B04FFE604EE5E49EB87CA49737FD39AA27639DE0CEF11B527B630070BE97ECC81F0D14D355F37C5C684A040C615563C962CE137A0B7C7F0B3567DEB0A05C4D79F373D7938D4CBFCE86CA6AA5DBAC99081F3AB4C52E0A3B35748A7600ECE4278060B14F5D3ACE5D964A73F49CF8844B6C86E10E',
            $sale->generateSignature()
        );
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testData()
    {
        $saleData = (new Sale())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setMerchantGMT('+03')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->getData();

        $this->assertEquals([
            'TRTYPE' => 1,
            'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => '+03',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            'MERCH_NAME' => null,
            'BACKREF' => 'https://test.com/back-ref-url',
            'AD.CUST_BOR_ORDER_ID' => '145659145659',
            'ADDENDUM' => 'AD,TD',
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '8125E0E604B8BC6430B03B1365B63D91ACB7210F2777776D7587A633D222368CB36936855090C81020318503998499503595EBB32092014A2843C7E6DB75C1AD7FCB018BB4CDA98B379B411E74C62881529A7787B73D8D0E00D1406E1D2A64ADD1A298CCDF3B5A13C14825990010541444122F4A8FBB23BB3747B962BEFB5C57C5737FCF8DC9E61F377777B661B04FFE604EE5E49EB87CA49737FD39AA27639DE0CEF11B527B630070BE97ECC81F0D14D355F37C5C684A040C615563C962CE137A0B7C7F0B3567DEB0A05C4D79F373D7938D4CBFCE86CA6AA5DBAC99081F3AB4C52E0A3B35748A7600ECE4278060B14F5D3ACE5D964A73F49CF8844B6C86E10E'
        ], $saleData);
    }
}
